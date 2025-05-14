<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "model/conexion.php";  // Conexión a la base de datos

    // Recibir los datos del formulario
    $nombre = $_POST["inputTituloProd"]; // Nombre del producto
    $descripcion = $_POST["inputDescProd"]; // Descripción del producto
    $precio = $_POST["inputPrecioProd"]; // Precio del producto
    $fabricante_id = $_POST["inputFabricanteId"]; // ID del fabricante
    $cantidad = $_POST["inputCant"]; // Cantidad del producto

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($descripcion) || empty($precio) || empty($fabricante_id) || empty($cantidad)) {
        header("Location: productos.php?mensaje=FaltaProd");
        exit();
    }

    // Verificar si el ID del fabricante existe
    $sentencia = $bd->prepare("SELECT COUNT(*) FROM fabricante WHERE id = ?");
    $sentencia->execute([$fabricante_id]);
    $existe_fabricante = $sentencia->fetchColumn();

    if ($existe_fabricante == 0) {
        // Si no existe, redirigir con mensaje de error
        header("Location: productos.php?mensaje=Ingrese un ID de un fabricante registrado");
        exit();
    }

    // Preparar la consulta de inserción
    $sentencia = $bd->prepare("INSERT INTO producto(nombre, descripcion, precio, fabricante_id, cantidad) VALUES (?, ?, ?, ?, ?);");
    $resultado = $sentencia->execute([$nombre, $descripcion, $precio, $fabricante_id, $cantidad]);

    // Redirigir según el resultado de la inserción
    if ($resultado) {
        header("Location: productos.php?mensaje=registradoProd");
    } else {
        header("Location: productos.php?mensaje=errorProd");
        exit();
    }
}
?>
