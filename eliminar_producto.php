<?php
    if (!isset($_GET['codigo'])){
        header('Location: productos.php?mensaje=error');
        exit();
    }

    include 'model/conexion.php'; // Llamada a la conexión
    $id = $_GET['codigo']; // Guardar en variable el dato que obtenga de codigo

    // Verificar si el producto tiene ventas asociadas
    $consulta = $bd->prepare("SELECT COUNT(*) FROM venta WHERE producto_id = ?");
    $consulta->execute([$id]);
    $ventas = $consulta->fetchColumn(); // Obtiene la cantidad de ventas asociadas al producto

    if ($ventas > 0) {
        // Si hay ventas asociadas, no eliminar el producto
        header('Location: productos.php?mensaje=noEliminar');
        exit();
    } else {
        // Si no hay ventas asociadas, proceder a eliminar el producto
        $sentencia = $bd->prepare("DELETE FROM producto WHERE id = ?");
        $resultado = $sentencia->execute([$id]);

        if ($resultado == TRUE){
            header('Location: productos.php?mensaje=eliminado');
        } else {
            header('Location: productos.php?mensaje=error');
            exit();
        }
    }
?>
