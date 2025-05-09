<?php
    // Verificar que se haya pasado el ID de la venta a eliminar
    if (!isset($_GET['id'])){
        header('Location: ventas.php?mensaje=error');
        exit();
    }

    include 'model/conexion.php'; // Llamada a la conexión
    $id = $_GET['id']; // Guardar en variable el dato que obtenga de 'id'

    // Verificar si la venta existe en la base de datos
    $consulta = $bd->prepare("SELECT * FROM venta WHERE id = ?");
    $consulta->execute([$id]);
    $venta = $consulta->fetch(PDO::FETCH_OBJ);

    if (!$venta) {
        // Si no se encuentra la venta, redirigir con mensaje de error
        header('Location: ventas.php?mensaje=error');
        exit();
    } else {
        // Proceder a eliminar la venta
        $sentencia = $bd->prepare("DELETE FROM venta WHERE id = ?");
        $resultado = $sentencia->execute([$id]);

        if ($resultado == TRUE){
            header('Location: ventas.php?mensaje=eliminado');
        } else {
            header('Location: ventas.php?mensaje=error');
            exit();
        }
    }
?>

