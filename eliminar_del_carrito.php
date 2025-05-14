<?php
session_start();

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];
    unset($_SESSION['carrito'][$producto_id]);
}

header("Location: ver_carrito.php");
exit();
