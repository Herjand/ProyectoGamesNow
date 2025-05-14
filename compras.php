<?php
include_once "model/conexion.php"; // Llamada al archivo de conexión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Consultar productos
$sql = "SELECT p.id, p.nombre AS producto, p.descripcion, p.precio, p.cantidad, 
               f.nombre AS fabricante, f.pais 
        FROM producto p
        INNER JOIN fabricante f ON p.fabricante_id = f.id";
$stmt = $bd->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Añadir al carrito
if (isset($_POST['agregar_carrito'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad_solicitada = $_POST['cantidad'];

    // Consultar el producto seleccionado
    $sql = "SELECT id, nombre, precio, cantidad FROM producto WHERE id = :producto_id";
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        // Verificar que la cantidad solicitada no exceda el stock disponible
        if ($cantidad_solicitada <= $producto['cantidad']) {
            // Si el producto ya está en el carrito, actualizamos la cantidad
            if (isset($_SESSION['carrito'][$producto_id])) {
                $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad_solicitada;
            } else {
                // Agregar producto al carrito
                $_SESSION['carrito'][$producto_id] = [
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'cantidad' => $cantidad_solicitada
                ];
            }
            // Mensaje de éxito
            echo '<div class="alert alert-success" role="alert">Producto agregado al carrito.</div>';
        } else {
            // Mensaje de error si la cantidad solicitada excede el stock
            echo '<div class="alert alert-danger" role="alert">La cantidad solicitada supera el stock disponible. Solo hay ' . $producto['cantidad'] . ' unidades disponibles.</div>';
        }
    } else {
        // Mensaje de error si el producto no existe
        echo '<div class="alert alert-danger" role="alert">Producto no encontrado.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Videojuegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #1a1a2e;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background: linear-gradient(135deg, #4b0082, #6a0dad);
            color: white;
            border: none;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-title {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #ff007f;
            border: none;
        }
        .btn-custom:hover {
            background-color: #ff1493;
        }
        .icon {
            font-size: 50px;
            color: #ffcc00;
            display: block;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <h1 class="mb-4">🎮 Catálogo de Videojuegos</h1>
    
    <div class="row">
        <?php foreach ($productos as $producto): ?>
        <div class="col-md-4 mb-4">
            <div class="card p-3">
                <i class="fas fa-gamepad icon"></i>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($producto['producto']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($producto['descripcion']) ?></p>
                    <p class="card-text"><strong>Fabricante:</strong> <?= htmlspecialchars($producto['fabricante']) ?> (<?= htmlspecialchars($producto['pais']) ?>)</p>
                    <p class="card-text"><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>
                    <p class="card-text"><strong>Stock:</strong> <?= htmlspecialchars($producto['cantidad']) ?> unidades</p>
                    <form method="POST">
                        <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" name="cantidad" min="1" max="<?= $producto['cantidad'] ?>" value="1" required>
                            <button type="submit" name="agregar_carrito" class="btn btn-custom">🛒 Agregar al carrito</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Ver el carrito -->
    <a href="ver_carrito.php" class="btn btn-outline-light mt-4">Ver Carrito (<?= count($_SESSION['carrito'] ?? []) ?>)</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
