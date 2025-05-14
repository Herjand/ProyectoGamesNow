<?php
if (!isset($_GET['codigo'])) {
    header('Location: fabricante.php?mensaje=error');
    exit();
}

include 'model/conexion.php'; // Llamada a la conexión
$id = $_GET['codigo']; // Guardar en variable el dato que obtenga de codigo

// Verificar si el fabricante tiene productos asociados
$consulta = $bd->prepare("SELECT COUNT(*) FROM producto WHERE fabricante_id = ?");
$consulta->execute([$id]);
$productosAsociados = $consulta->fetchColumn();

if ($productosAsociados > 0) {
    // Si hay productos asociados, redirigir con mensaje de error
    header('Location: fabricante.php?mensaje=fabricanteConProductos');
    exit();
}

// Si no hay productos asociados, proceder con la eliminación
$sentencia = $bd->prepare("DELETE FROM fabricante WHERE id = ?;");
$resultado = $sentencia->execute([$id]);

if ($resultado) {
    header('Location: fabricante.php?mensaje=eliminado');
} else {
    header('Location: fabricante.php?mensaje=error');
    exit();
}
?>
