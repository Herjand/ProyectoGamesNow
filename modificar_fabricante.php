<?php include 'template/header.php'; ?>

<?php
    include_once 'model/conexion.php';
    $codigo = $_GET["codigo"]; // guardar en una variable codigo

    // Cambia la consulta para obtener datos de la tabla FABRICANTE
    $sentencia = $bd->prepare("SELECT * FROM fabricante WHERE id = ?;");
    $sentencia->execute([$codigo]);
    $fabricante = $sentencia->fetch(PDO::FETCH_OBJ);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h5>Modificar Datos del Fabricante</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="modificar_fabricante_proceso.php">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="inputNombre" id="inputNombre" value="<?php echo $fabricante->nombre; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputPais" class="form-label">País</label>
                            <input type="text" class="form-control" name="inputPais" id="inputPais" value="<?php echo $fabricante->pais; ?>" required>
                        </div>
                        <input type="hidden" name="codigo" value="<?php echo $fabricante->id; ?>">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Modificar</button>
                            <a href="fabricante.php" class="btn btn-secondary btn-lg">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>
