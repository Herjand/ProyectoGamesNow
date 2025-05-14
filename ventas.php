<?php include 'template/header.php'; ?>

<?php
include_once "model/conexion.php"; // Llamada al archivo de conexión

// Obtener la lista de productos para mostrar en el formulario
$sentenciaProd = $bd->query("SELECT * FROM producto");
$productos = $sentenciaProd->fetchAll(PDO::FETCH_OBJ);

// Obtener la lista de ventas desde la tabla 'venta'
$sentenciaVenta = $bd->query("SELECT v.id, p.nombre AS producto, v.cantidad, v.precio, v.fecha_venta 
                              FROM venta v
                              JOIN producto p ON v.producto_id = p.id");
$ventas = $sentenciaVenta->fetchAll(PDO::FETCH_OBJ);
?>

<!-- Barra de navegación -->
<nav class="navbar navbar-expand navbar-light bg-light">
    <div class="navbar-nav">
        <a class="nav-item nav-link active" href="index.php" aria-current="page">Página principal
            <span class="visually-hidden">(current)</span></a>
        <a class="nav-item nav-link" href="fabricante.php">Fabricantes</a>
        <a class="nav-item nav-link" href="productos.php">Productos</a>
        <a class="nav-item nav-link" href="busqueda.php">Buscar</a>
    </div>
</nav>
<!-- Fin de la barra de navegación -->

<div class="container mt-5">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-10">

            <!-- Alertas para el registro de ventas -->
            <?php
                 if (isset($_GET['mensaje'])) {
                    switch ($_GET['mensaje']) {
                        case 'StockInsuficiente':
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>Error!</strong> La cantidad solicitada excede el stock disponible. Por favor, ingresa una cantidad menor o igual al stock disponible.</div>';
                            break;
                        case 'FaltaVenta':
                            echo '<div class="alert alert-danger" role="alert">Faltan datos, por favor completa todos los campos de la venta.</div>';
                            break;
                        case 'ProductoNoExiste':
                            echo '<div class="alert alert-danger" role="alert">El producto seleccionado no existe en la base de datos.</div>';
                            break;
                        case 'RegistradoVenta':
                            echo '<div class="alert alert-success" role="alert">¡Venta registrada exitosamente!</div>';
                            break;
                        case 'ErrorVenta':
                            echo '<div class="alert alert-danger" role="alert">Hubo un error al registrar la venta. Por favor, intenta nuevamente.</div>';
                            break;
                    }
                }
            ?>

            <!-- Script para eliminar el parámetro "mensaje" de la URL -->
            <script>
                if (window.location.search.includes('mensaje=')) {
                    const url = new URL(window.location);
                    url.searchParams.delete('mensaje');
                    window.history.replaceState(null, '', url); // Actualiza la URL sin recargar la página
                }
            </script>
            
            <!-- Script para que funcione la x para cerrar mensajes emergentes -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Fin de alertas -->

            <!-- Apartado con título de la parte de visualización -->
            <div class="container mt-2">
                <h2 class="text-center">Visualización de Ventas</h2>
                <div class="row justify-content-center align-items-start g-4">
                    <!-- Muestra de la tabla de ventas -->
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-light text-center">
                            <h5>LISTA DE VENTAS</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col">ID Venta</th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Fecha de Venta</th>
                                            <th scope="col" colspan="2">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ventas as $venta): ?>
                                        <tr>
                                            <td><?php echo $venta->id; ?></td>
                                            <td><?php echo $venta->producto; ?></td>
                                            <td><?php echo $venta->cantidad; ?></td>
                                            <td><?php echo $venta->precio; ?></td>
                                            <td><?php echo $venta->fecha_venta; ?></td>
                                            <td>
                                                <a href="eliminar_venta.php?id=<?php echo $venta->id; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar esta venta?');" class="btn btn-danger">Eliminar</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Fin de la tabla ventas -->
                </div>
            </div>

            <!-- Apartado con título de la parte de registro -->
            <div class="container mt-5">
                <h2 class="text-center">Registro de Venta</h2>
                <div class="row justify-content-center align-items-start g-4">
                    <div class="col-md-5 mb-4">
                        <div class="card">
                            <div class="card-header text-center bg-dark text-light">
                                <h5>Ingresar datos de la venta</h5>
                            </div>
                            <div class="card-body">
                                <form class="p-4" method="POST" action="registrar_venta.php">
                                    <div class="mb-3">
                                        <label for="inputProductoId" class="form-label">Producto</label>
                                        <select class="form-select" name="inputProductoId" id="inputProductoId" required onchange="actualizarPrecio()">
                                            <option value="">Selecciona un producto</option>
                                            <?php foreach ($productos as $producto): ?>
                                                <option value="<?php echo $producto->id; ?>" data-precio="<?php echo $producto->precio; ?>">
                                                    <?php echo $producto->nombre; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputCantidadVenta" class="form-label">Cantidad</label>
                                        <input type="number" class="form-control" name="inputCantidadVenta" id="inputCantidadVenta" placeholder="Cantidad de productos vendidos" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputPrecioVenta" class="form-label">Precio</label>
                                        <input type="number" step="0.01" class="form-control" name="inputPrecioVenta" id="inputPrecioVenta" placeholder="Precio del producto" required readonly />
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputFechaVenta" class="form-label">Fecha de Venta</label>
                                        <input type="date" class="form-control" name="inputFechaVenta" id="inputFechaVenta" required />
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Registrar Venta</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

<!-- Script para actualizar el precio del producto según la selección -->
<script>
    function actualizarPrecio() {
        var select = document.getElementById('inputProductoId');
        var precio = select.options[select.selectedIndex].getAttribute('data-precio');
        document.getElementById('inputPrecioVenta').value = precio;
    }
</script>

<?php include 'template/footer.php'; ?>
