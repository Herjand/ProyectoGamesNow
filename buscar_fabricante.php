<?php
include_once "model/conexion.php";  // Asegúrate de tener la conexión a la BD

$consulta = [];  // Inicializa la variable como un array vacío

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $busqueda = trim($_POST['inputbuscar']);

    if (!empty($busqueda)) {
        // Preparar consulta para buscar en nombre, apellidos y teléfono
        $sentencia = $bd->prepare("SELECT * FROM fabricante WHERE id LIKE ? OR nombre LIKE ? OR id LIKE OR id LIKE ?");
        $sentencia->execute(["%$busqueda%", "%$busqueda%", "%$busqueda%"]);
        $consulta = $sentencia->fetchAll(PDO::FETCH_OBJ); // Almacena el resultado en un array de objetos
    }
} else {
    // Consulta para obtener todos los clientes si no hay búsqueda
    $sentencia = $bd->prepare("SELECT * FROM fabricante");
    $sentencia->execute();
    $consulta = $sentencia->fetchAll(PDO::FETCH_OBJ); // Almacena todos los resultados
}
?>

<?php include 'template/header.php' ?>

<nav class="navbar navbar-expand navbar-light bg-light">
  <div class="nav navbar-nav">
    <a class="nav-item nav-link active" href="index.php" aria-current="page">Página principal <span class="visually-hidden">(current)</span></a>
    <a class="nav-item nav-link" href="#">Producto</a>
    <a class="nav-item nav-link" href="pedidos.php">Ventas</a>
  </div>
</nav>

<!-- Estructura HTML para mostrar los resultados de la búsqueda -->
<div class="container mt-5">
    <h2 class="text-center">Búsqueda de fabricantes</h2>

    <form method="POST" action="buscar_fabricante.php" class="d-flex mb-4">
        <input type="text" class="form-control me-2" name="inputbuscar" placeholder="Buscar fabricante" required>
        <button class="btn btn-outline-success" type="submit">Buscar</button>
    </form>

    <?php if (!empty($consulta)) { ?>
        <div class="table-responsive-sm">
            <table class="table table-info align-middle">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">FABRICANTE</th>
                        <th scope="col">PAÍS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consulta as $dato) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dato->id); ?></td>
                            <td><?php echo htmlspecialchars($dato->nombre); ?></td>
                            <td><?php echo htmlspecialchars($dato->pais); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
        <div class="alert alert-warning" role="alert">
            No se encontraron resultados para la búsqueda.
        </div>
    <?php } ?>
</div>

<?php include 'template/footer.php' ?>
