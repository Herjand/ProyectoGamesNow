<?php
session_start();
include_once "model/conexion.php"; // Conexión a la base de datos

// Verificar si el ID del producto y la cantidad fueron enviados
if (isset($_GET['id']) && isset($_POST['cantidad'])) {
    $producto_id = $_GET['id'];
    $cantidad_solicitada = (int) $_POST['cantidad'];

    // Consulta para obtener el producto con el ID proporcionado
    $sql = "SELECT * FROM productos WHERE id = :id";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_OBJ);

    // Verificar si el producto existe
    if ($producto) {
        // Verificar si la cantidad solicitada es válida
        if ($cantidad_solicitada <= $producto->cantidad) {
            // Si el producto ya está en el carrito, actualizamos la cantidad
            if (isset($_SESSION['carrito'][$producto_id])) {
                $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad_solicitada;
            } else {
                // Si el producto no está en el carrito, lo agregamos
                $_SESSION['carrito'][$producto_id] = [
                    'nombre' => $producto->nombre,
                    'precio' => $producto->precio,
                    'cantidad' => $cantidad_solicitada
                ];
            }

            // Mensaje de éxito
            echo '<div class="alert alert-success" role="alert">Producto agregado al carrito.</div>';
        } else {
            // Mensaje de error si la cantidad es mayor a la disponible
            echo '<div class="alert alert-danger" role="alert">Cantidad no disponible. Solo hay ' . $producto->cantidad . ' unidades de este producto.</div>';
        }
    } else {
        // Mensaje de error si el producto no existe
        echo '<div class="alert alert-danger" role="alert">Producto no encontrado.</div>';
    }
} else {
    // Mensaje si no se ha enviado la cantidad o el ID del producto
    echo '<div class="alert alert-warning" role="alert">No se especificó la cantidad o el producto.</div>';
}
?>
