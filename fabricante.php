<?php include 'template/header.php'; ?>

<?php
include_once "model/conexion.php"; // Llamada al archivo de conexión

// Obtener la lista de fabricantes
$sentenciaFab = $bd->query("SELECT * FROM fabricante");
$fabricantes = $sentenciaFab->fetchAll(PDO::FETCH_OBJ);
?>

<!-- Barra de navegacion -->
<nav class="navbar navbar-expand navbar-light bg-light">
    <div class="navbar-nav">
        <a class="nav-item nav-link active" href="index.php" aria-current="page">Página principal
            <span class="visually-hidden">(current)</span></a>
        <a class="nav-item nav-link" href="productos.php">Productos</a>
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
                    case 'registradoFab':
                        echo '<div class="alert alert-success" role="alert">¡Registrado! Se agregaron los datos del fabricante.</div>';
                        break;
                    case 'FaltaFab':
                        echo '<div class="alert alert-danger" role="alert">Faltan datos, por favor completa todos los campos del fabricante.</div>';
                        break;
                    case 'errorFab':
                        echo '<div class="alert alert-danger" role="alert">Error al registrar el fabricante, por favor inténtalo de nuevo.</div>';
                        break;
                    case 'eliminado':
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>Eliminado!</strong> Los datos del fabricante o producto fueron eliminados ...
                              </div>';
                        break;
                    case 'fabricanteConProductos':
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>Error!</strong> No se puede eliminar el fabricante porque tiene productos asociados.
                              </div>';
                        break;
                    case 'error':
                        echo '<div class="alert alert-danger" role="alert">Ocurrió un error al intentar eliminar el fabricante.</div>';
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

            <!-- Apartado con titulo de la parte de visualizacion -->
            <div class="container mt-2">
                <h2 class="text-center">Visualización de fabricantes</h2>
                <div class="row justify-content-center align-items-start g-4">
                    <!-- Muestra de la tabla fabricantes -->
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-light text-center">
                            <h5>LISTA DE FABRICANTES</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">País</th>
                                            <th scope="col" colspan="2">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fabricantes as $dato): ?>
                                        <tr>
                                            <td><?php echo $dato->id; ?></td>
                                            <td><?php echo $dato->nombre; ?></td>
                                            <td><?php echo $dato->pais; ?></td>
                                            <td>
                                                <a href="modificar_fabricante.php?codigo=<?php echo $dato->id; ?>" class="btn btn-warning">Editar</a>
                                            </td>
                                            <td>
                                                <a href="eliminar_fabricante.php?codigo=<?php echo $dato->id; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este fabricante?');" class="btn btn-danger">Eliminar</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Fin de la tabla fabricantes -->

                    <!-- Apartado con titulo de la parte de registros-->
                    <div class="container mt-5">
                        <h2 class="text-center">Registro de datos del Fabricante</h2>
                        <div class="row justify-content-center align-items-start g-4">
                            <div class="col-md-5 mb-4">
                                <div class="card">
                                    <div class="card-header text-center bg-dark text-light">
                                        <h5>Ingresar datos del Fabricante</h5>
                                    </div>
                                    <div class="card-body">
                                        <form class="p-4" method="POST" action="registrar_fabricante.php">
                                            <div class="mb-3">
                                                <label for="inputNombreFab" class="form-label">Nombre del Fabricante</label>
                                                <input type="text" class="form-control" name="inputNombreFab" id="inputNombreFab" placeholder="Nombre del fabricante" required />
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputPaisFab" class="form-label">País</label>
                                                <input type="text" class="form-control" name="inputPaisFab" id="inputPaisFab" placeholder="País del fabricante" required />
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Registrar</button>
                                                <button type="reset" class="btn btn-secondary mt-2">Restablecer</button>
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
