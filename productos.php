<?php include 'template/header.php'; ?>

<?php
include_once "model/conexion.php"; // Llamada al archivo de conexión

// Obtener la lista de fabricantes
$sentenciaFab = $bd->query("SELECT * FROM fabricante");
$fabricantes = $sentenciaFab->fetchAll(PDO::FETCH_OBJ);

// Obtener la lista de productos
$sentenciaProd = $bd->query("SELECT * FROM producto");
$productos = $sentenciaProd->fetchAll(PDO::FETCH_OBJ);
?>

<!-- Barra de navegacion -->
<nav class="navbar navbar-expand navbar-light bg-light">
    <div class="navbar-nav">
        <a class="nav-item nav-link active" href="index.php" aria-current="page">Página principal
            <span class="visually-hidden">(current)</span></a>
        <a class="nav-item nav-link" href="fabricante.php">Fabricantes</a>
        <!--<a class="nav-item nav-link" href="ventas.php">Ventas</a>-->
        <a class="nav-item nav-link" href="busqueda.php">Buscar</a>
    </div>
</nav>
<!-- Fin de la barra de navegacion -->

<div class="container mt-5">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-10">

            <!-- Alertas para el registro de fabricante y producto -->
            <?php
                if (isset($_GET['mensaje'])) {
                    switch ($_GET['mensaje']) {
                        case 'registradoProd':
                            echo '<div class="alert alert-success" role="alert">¡Registrado! Se agregaron los datos del producto.</div>';
                            break;
                        case 'FaltaProd':
                            echo '<div class="alert alert-danger" role="alert">Faltan datos, por favor completa todos los campos del producto.</div>';
                            break;
                        case 'errorProd':
                            echo '<div class="alert alert-danger" role="alert">Error al registrar el producto, por favor inténtalo de nuevo.</div>';
                            break;
                        case 'registradoProd':
                            echo '<div class="alert alert-success" role="alert">¡Registrado! Se agregaron los datos del producto.</div>';
                            break;
                        case 'FaltaProd':
                            echo '<div class="alert alert-danger" role="alert">Faltan datos, por favor completa todos los campos del producto.</div>';
                            break;
                        case 'errorProd':
                            echo '<div class="alert alert-danger" role="alert">Error al registrar el producto, por favor inténtalo de nuevo.</div>';
                            break;
                        case 'eliminado':
                            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Eliminado!</strong> Los datos del producto fueron eliminados ...
                                  </div>';
                            break;
                        case 'errorProd':
                            echo '<div class="alert alert-danger" role="alert">Ocurrió un error al intentar eliminar el producto.</div>';
                            break;
                        case 'noEliminar':
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Error!</strong> No se puede eliminar el producto ya que fue registrado como vendido.
                                  </div>';
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
            
            <!-- Script para funcione la x para cerrar mensajes emergentes -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Fin de alertas -->

            <!-- Apartado con titulo de la parte de visualizacion -->
            <div class="container mt-2">
                <h2 class="text-center">Visualizacion de productos que se tienen en la tienda</h2>
                <div class="row justify-content-center align-items-start g-4">
                    <!-- Muestra de la tabla productos -->
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-light text-center">
                            <h5>LISTA DE PRODUCTOS</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Fabricante ID</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col" colspan="2">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos as $dato): ?>
                                        <tr>
                                            <td><?php echo $dato->id; ?></td>
                                            <td><?php echo $dato->nombre; ?></td>
                                            <td><?php echo $dato->descripcion; ?></td>
                                            <td><?php echo $dato->precio; ?></td>
                                            <td><?php echo $dato->fabricante_id; ?></td>
                                            <td><?php echo $dato->cantidad; ?></td>
                                            <td>
                                                <a href="modificar_producto.php?codigo=<?php echo $dato->id; ?>" class="btn btn-warning">Editar</a>
                                            </td>
                                            <td>
                                                <a href="eliminar_producto.php?codigo=<?php echo $dato->id; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');" class="btn btn-danger">Eliminar</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Fin de la tabla productos -->
                </div>
            </div>

            <!-- Apartado con titulo de la parte de registros-->
            <div class="container mt-5">
                <h2 class="text-center">Registro de datos del Producto</h2>
                <div class="row justify-content-center align-items-start g-4">
                    <div class="container mt-5">
                        <div class="row justify-content-center align-items-start g-4">
                            <!-- Formulario para ingresar datos del producto -->
                            <div class="col-md-5 mb-4">
                                <div class="card">
                                    <div class="card-header text-center bg-dark text-light">
                                        <h5>Ingresar datos del producto</h5>
                                    </div>
                                    <div class="card-body">
                                        <form class="p-4" method="POST" action="registrar_producto.php">
                                            <div class="mb-3">
                                                <label for="inputTituloProd" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="inputTituloProd" id="inputTituloProd" placeholder="Titulo del videojuego" required />
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputDescProd" class="form-label">Descripción</label>
                                                <input type="text" class="form-control" name="inputDescProd" id="inputDescProd" placeholder="Descripción del producto" required />
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputPrecioProd" class="form-label">Precio (en dólares)</label>
                                                <input type="number" step="0.01" class="form-control" name="inputPrecioProd" id="inputPrecioProd" placeholder="Precio del producto" required />
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputFabricanteId" class="form-label">Fabricante</label>
                                                <select class="form-select" name="inputFabricanteId" id="inputFabricanteId" required>
                                                    <option value="">Selecciona un fabricante</option>
                                                    <?php foreach ($fabricantes as $fabricante): ?>
                                                        <option value="<?php echo $fabricante->id; ?>">
                                                            <?php echo $fabricante->nombre; ?> (<?php echo $fabricante->pais; ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small class="text-muted">Selecciona un fabricante existente.</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputCant" class="form-label">Cantidad</label>
                                                <input type="number" class="form-control" name="inputCant" id="Cant" placeholder="Cantidad del producto" required />
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Registrar</button>
                                                <button type="reset" class="btn btn-secondary mt-2">Restablecer</button>as
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
    </div>
</div>

<?php include 'template/footer.php'; ?>