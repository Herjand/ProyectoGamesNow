<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "model/conexion.php";  // Conexión a la base de datos

    // Recibir los datos del formulario
    $producto_id = $_POST["inputProductoId"]; // ID del producto
    $cantidad = $_POST["inputCantidadVenta"]; // Cantidad de productos vendidos
    $precio = $_POST["inputPrecioVenta"]; // Precio del producto
    $fecha_venta = $_POST["inputFechaVenta"]; // Fecha de la venta

    // Validar que los campos no estén vacíos
    if (empty($producto_id) || empty($cantidad) || empty($precio) || empty($fecha_venta)) {
        header("Location: ventas.php?mensaje=FaltaVenta");
        exit();
    }

    // Verificar si el producto existe
    $sentencia = $bd->prepare("SELECT COUNT(*) FROM producto WHERE id = ?");
    $sentencia->execute([$producto_id]);
    $existe_producto = $sentencia->fetchColumn();

    if ($existe_producto == 0) {
        // Si no existe, redirigir con mensaje de error
        header("Location: ventas.php?mensaje=ProductoNoExiste");
        exit();
    }

    // Verificar si hay suficiente stock
    $sentencia = $bd->prepare("SELECT cantidad FROM producto WHERE id = ?");
    $sentencia->execute([$producto_id]);
    $stock = $sentencia->fetchColumn();

    if ($cantidad > $stock) {
        // Si no hay suficiente stock, redirigir con mensaje de error
        header("Location: ventas.php?mensaje=StockInsuficiente");
        exit();
    }

    // Preparar la consulta de inserción en la tabla 'venta'
    $sentencia = $bd->prepare("INSERT INTO venta(producto_id, cantidad, precio, fecha_venta) VALUES (?, ?, ?, ?);");
    $resultado = $sentencia->execute([$producto_id, $cantidad, $precio, $fecha_venta]);

    // Redirigir según el resultado de la inserción
    if ($resultado) {
        // Actualizar el stock del producto después de registrar la venta
        $nuevo_stock = $stock - $cantidad;
        $sentencia = $bd->prepare("UPDATE producto SET cantidad = ? WHERE id = ?");
        $sentencia->execute([$nuevo_stock, $producto_id]);

        header("Location: ventas.php?mensaje=RegistradoVenta");
    } else {
        header("Location: ventas.php?mensaje=ErrorVenta");
        exit();
    }
}
?>
