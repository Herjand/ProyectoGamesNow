<?php 
    print_r($_POST);

    if(!isset($_POST['codigo'])){
        header('Location: fabricante.php?mensaje=error');
        exit();
    }

    include_once 'model/conexion.php'; // conexión a la BD

    // guardar en variables
    $id = $_POST['codigo'];
    $name = $_POST['inputNombre'];
    $pais = $_POST['inputPais']; 

    // Imprime los datos recibidos para depurar
    print_r($_POST);

    // Prepara la consulta SQL para actualizar los datos en la tabla FABRICANTE
    $sentencia = $bd->prepare("UPDATE fabricante SET nombre = ?, pais = ? WHERE id = ?;");

    // Ejecuta la sentencia SQL
    $resultado = $sentencia->execute([$name, $pais, $id]);

    // Verifica si la consulta fue exitosa
    if ($resultado === TRUE) {
        header('Location: fabricante.php?mensaje=modificado'); // Redirecciona si fue exitoso
        exit();
    } else {
        header('Location: fabricante.php?mensaje=error'); // Redirecciona si hubo un error
        exit();
    }
?>
