<?php
session_start();

// Verificar si el carrito tiene productos
if (empty($_SESSION['carrito'])) {
    $carrito_vacio = true;
} else {
    $carrito_vacio = false;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
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
    <h1 class="mb-4">🛒 Tu Carrito de Compras</h1>

    <?php if ($carrito_vacio): ?>
        <div class="alert alert-warning" role="alert">
            ¡Tu carrito está vacío! <a href="compras.php" class="btn btn-custom mt-2">Ir a Compras</a>
        </div>
    <?php else: ?>
        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($_SESSION['carrito'] as $producto_id => $item): 
                    $total += $item['precio'] * $item['cantidad'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td>$<?= number_format($item['precio'], 2) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                    <td>
                        <a href="eliminar_del_carrito.php?id=<?= $producto_id ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: $<?= number_format($total, 2) ?></h3>

        <a href="procesar_pago.php" class="btn btn-success">Proceder al Pago</a>
        <a href="compras.php" class="btn btn-secondary mt-2">Volver a Compras</a>
    <?php endif; ?>
</div>

<!-- Modal de Carrito Vacío -->
<?php if ($carrito_vacio): ?>
    <div class="modal fade" id="carritoVacioModal" tabindex="-1" aria-labelledby="carritoVacioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carritoVacioModalLabel">¡Tu carrito está vacío!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¡Parece que no has agregado productos aún! Puedes explorar nuestra tienda para encontrar productos geniales.
                </div>
                <div class="modal-footer">
                    <a href="compras.php" class="btn btn-custom">Ir a Compras</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar el modal cuando el carrito esté vacío
        var myModal = new bootstrap.Modal(document.getElementById('carritoVacioModal'), {
            keyboard: false
        });
        myModal.show();
    </script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
