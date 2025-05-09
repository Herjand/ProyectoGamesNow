<?php
session_start();

// Verificar si el carrito tiene productos
if (empty($_SESSION['carrito'])) {
    $carrito_vacio = true;
    $total = 0; // En caso de que el carrito esté vacío, establecer el total como 0
} else {
    $carrito_vacio = false;
    $total = 0; // Inicializar la variable $total
    // Calcular el total
    foreach ($_SESSION['carrito'] as $producto_id => $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
        }
        .btn-custom {
            background-color: #8e44ad;
            color: white;
        }
        .btn-custom:hover {
            background-color: #6f2a91;
        }
        .modal-header {
            background-color: #8e44ad;
            color: white;
        }
        .modal-footer a {
            background-color: #8e44ad;
            color: white;
        }
        .modal-footer a:hover {
            background-color: #6f2a91;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">💳 Procesar Pago</h1>

    <?php if ($carrito_vacio): ?>
        <div class="alert alert-warning" role="alert">
            ¡Tu carrito está vacío! <a href="compras.php" class="btn btn-custom mt-2">Ir a Compras</a>
        </div>
    <?php else: ?>
        <h3>Total a pagar: $<?= number_format($total, 2) ?></h3>

        <form action="confirmar_pago.php" method="POST">
            <div class="mb-3">
                <label for="nombre_tarjeta" class="form-label">Nombre en la tarjeta</label>
                <input type="text" class="form-control" id="nombre_tarjeta" name="nombre_tarjeta" required>
            </div>

            <div class="mb-3">
                <label for="numero_tarjeta" class="form-label">Número de tarjeta</label>
                <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" required>
            </div>

            <div class="mb-3">
                <label for="fecha_expiracion" class="form-label">Fecha de expiración</label>
                <input type="month" class="form-control" id="fecha_expiracion" name="fecha_expiracion" required>
            </div>

            <div class="mb-3">
                <label for="codigo_seguridad" class="form-label">Código de seguridad (CVV)</label>
                <input type="text" class="form-control" id="codigo_seguridad" name="codigo_seguridad" required>
            </div>

            <button type="submit" class="btn btn-success">Pagar</button>
        </form>

        <a href="ver_carrito.php" class="btn btn-secondary mt-2">Volver al carrito</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
