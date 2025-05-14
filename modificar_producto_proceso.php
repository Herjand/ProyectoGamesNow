<?php 
    print_r($_POST);

    if (!isset($_POST['codigo'])) {
        header('Location: productos.php?mensaje=error');
        exit();
    }

    include_once 'model/conexion.php'; // Conexión a la BD
    
    // Guardar en variables
    $id = $_POST['codigo'];
    $titulo = $_POST['inputNombr']; 
    $descrip = $_POST['inputDesc']; 
    $cant = $_POST['inputCant'];
    $precio = $_POST['inputPrecio'];

    // Imprime los datos recibidos para depurar
    print_r($_POST);
    
    // Prepara la consulta SQL para actualizar los datos
    $sentencia = $bd->prepare("UPDATE producto SET nombre = ?, descripcion = ?, cantidad = ?, precio = ? WHERE id = ?;");

    // Ejecuta la sentencia SQL
    $resultado = $sentencia->execute([$titulo, $descrip, $cant, $precio, $id]);

    // Verifica si la consulta fue exitosa
    if ($resultado) {
        header('Location: productos.php?mensaje=modificado'); // Redirecciona si fue exitoso
        exit();
    } else {
        header('Location: productos.php?mensaje=error'); // Redirecciona si hubo un error
        exit();
    }
?>
