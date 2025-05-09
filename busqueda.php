<?php
include_once "model/conexion.php"; 

$consultaFabricantes = [];
$consultaProductos = [];
$consultaVentas = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Búsqueda para Fabricantes
    if (isset($_POST['buscarFabricantes'])) {
        $busquedaFab = trim($_POST['inputbuscarFabricantes']);
        if (!empty($busquedaFab)) {
            $sentenciaFab = $bd->prepare("SELECT * FROM fabricante WHERE id LIKE ? OR nombre LIKE ? OR pais LIKE ?");
            $sentenciaFab->execute(["%$busquedaFab%", "%$busquedaFab%", "%$busquedaFab%" ]);
            $consultaFabricantes = $sentenciaFab->fetchAll(PDO::FETCH_OBJ);
        }
    }

    // Búsqueda para Productos
    if (isset($_POST['buscarProductos'])) {
        $busquedaProd = trim($_POST['inputbuscarProductos']);
        if (!empty($busquedaProd)) {
            $sentenciaProd = $bd->prepare("SELECT * FROM producto WHERE id LIKE ? OR nombre LIKE ? OR precio LIKE ?");
            $sentenciaProd->execute(["%$busquedaProd%", "%$busquedaProd%", "%$busquedaProd%"]);
            $consultaProductos = $sentenciaProd->fetchAll(PDO::FETCH_OBJ);
        }
    }

    // Búsqueda para Ventas
    if (isset($_POST['buscarVentas'])) {
        $busquedaVenta = trim($_POST['inputbuscarVentas']);
        if (!empty($busquedaVenta)) {
            $sentenciaVenta = $bd->prepare("SELECT * FROM venta WHERE id LIKE ? OR producto_id LIKE ? OR cantidad LIKE ?");
            $sentenciaVenta->execute(["%$busquedaVenta%", "%$busquedaVenta%", "%$busquedaVenta%"]);
            $consultaVentas = $sentenciaVenta->fetchAll(PDO::FETCH_OBJ);
        }
    }
}
?>

<?php include 'template/header.php' ?>

<nav class="navbar navbar-expand navbar-light bg-light">
  <div class="nav navbar-nav">
    <a class="nav-item nav-link active" href="index.php" aria-current="page">Página principal</a>
    <a class="nav-item nav-link" href="fabricante.php">Fabricantes</a>
    <a class="nav-item nav-link" href="productos.php">Productos</a>
    <!--<a class="nav-item nav-link" href="ventas.php">Ventas</a>-->
  </div>
</nav>

<!-- Estructura HTML para mostrar los resultados de la búsqueda -->
<div class="container mt-5">
    <h2 class="text-center">Búsqueda de Fabricantes, Productos y Ventas</h2>

    <!-- Barra de búsqueda para Fabricantes -->
    <form method="POST" action="busqueda.php" class="d-flex mb-4">
        <input type="text" class="form-control me-2" name="inputbuscarFabricantes" placeholder="Buscar fabricantes..." required>
        <button class="btn btn-outline-success" type="submit" name="buscarFabricantes">Buscar Fabricantes</button>
    </form>

    <!-- Mostrar resultados de fabricantes -->
    <?php if (!empty($consultaFabricantes)) { ?>
        <h3>Fabricantes</h3>
        <div class="table-responsive-sm">
            <table class="table table-info align-middle">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Dirección</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultaFabricantes as $fab) { ?>
                        <tr>
                            <!-- Condicional para no mostrar el ID si es igual a 1 -->
                            <td><?php echo htmlspecialchars($fab->id); ?></td>
                            <td><?php echo htmlspecialchars($fab->nombre); ?></td>
                            <td><?php echo htmlspecialchars($fab->pais); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <!-- Barra de búsqueda para Productos -->
    <form method="POST" action="busqueda.php" class="d-flex mb-4">
        <input type="text" class="form-control me-2" name="inputbuscarProductos" placeholder="Buscar productos..." required>
        <button class="btn btn-outline-success" type="submit" name="buscarProductos">Buscar Productos</button>
    </form>

    <!-- Mostrar resultados de productos -->
    <?php if (!empty($consultaProductos)) { ?>
        <h3>Productos</h3>
        <div class="table-responsive-sm">
            <table class="table table-info align-middle">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultaProductos as $prod) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prod->id); ?></td>
                            <td><?php echo htmlspecialchars($prod->nombre); ?></td>
                            <td><?php echo htmlspecialchars($prod->precio); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <!-- Barra de búsqueda para Ventas -->
    <form method="POST" action="busqueda.php" class="d-flex mb-4">
        <input type="text" class="form-control me-2" name="inputbuscarVentas" placeholder="Buscar ventas..." required>
        <button class="btn btn-outline-success" type="submit" name="buscarVentas">Buscar Ventas</button>
    </form>

    <!-- Mostrar resultados de ventas -->
    <?php if (!empty($consultaVentas)) { ?>
        <h3>Ventas</h3>
        <div class="table-responsive-sm">
            <table class="table table-info align-middle">
                <thead>
                    <tr>
                        <th scope="col">ID Venta</th>
                        <th scope="col">ID Producto</th>
                        <th scope="col">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultaVentas as $venta) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($venta->id); ?></td>
                            <td><?php echo htmlspecialchars($venta->producto_id); ?></td>
                            <td><?php echo htmlspecialchars($venta->cantidad); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <!-- Mensaje si no se encontraron resultados -->
    <?php if (empty($consultaFabricantes) && empty($consultaProductos) && empty($consultaVentas)) { ?>
        <div class="alert alert-warning" role="alert">
            No se encontraron resultados para la búsqueda.
        </div>
    <?php } ?>
</div>

<?php include 'template/footer.php' ?>
