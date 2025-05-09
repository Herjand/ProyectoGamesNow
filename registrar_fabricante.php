<?php
// Mostrar los datos recibidos
print_r($_POST);

// Validar si faltan datos
if (empty($_POST["inputNombreFab"]) || empty($_POST["inputPaisFab"])) {
    // Redirigir con mensaje de error
    header('Location: fabricante.php?mensaje=Falta');
    exit();
}

// Incluir la conexión a la base de datos
include_once 'model/conexion.php';

// Guardar en variables los datos ingresados
$nombreFab = $_POST["inputNombreFab"];
$direccionFab = $_POST["inputPaisFab"];

// Preparar la consulta SQL
$sentencia = $bd->prepare("INSERT INTO fabricante(nombre, pais) VALUES(?, ?);");
$resultado = $sentencia->execute([$nombreFab, $direccionFab]);

// Verificar el resultado de la inserción
if ($resultado) {
    // Redirigir con mensaje de éxito
    header('Location: fabricante.php?mensaje=registradoFab');
} else {
    // Redirigir con mensaje de error
    header('Location: fabricante.php?mensaje=error');
}
exit(); // Asegurarse de que no se ejecute más código
?>

