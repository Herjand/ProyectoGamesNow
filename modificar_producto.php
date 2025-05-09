<?php include 'template/header.php'; ?>

<?php
    include_once 'model/conexion.php';
    $codigo = $_GET["codigo"]; // guardar en una variable codigo

    // Cambia la consulta para obtener datos de la tabla PRODUCTO
    $sentencia = $bd->prepare("SELECT * FROM producto WHERE id = ?;");
    $sentencia->execute([$codigo]);
    $producto = $sentencia->fetch(PDO::FETCH_OBJ);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h5>Modificar Datos del Producto</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="modificar_producto_proceso.php">
                        <div class="mb-3">
                            <label for="inputNombr" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" name="inputNombr" id="inputNombr" value="<?php echo $producto->nombre; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputDesc" class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="inputDesc" id="inputDesc" value="<?php echo $producto->descripcion; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputCant" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="inputCant" id="inputCant" value="<?php echo $producto->cantidad; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputPrecio" class="form-label">Precio ($ USD)</label>
                            <input type="number" class="form-control" name="inputPrecio" id="inputPrecio" value="<?php echo $producto->precio; ?>" required>
                            <small class="form-text text-muted">Ingrese el precio en dólares.</small>
                        </div>
                        <input type="hidden" name="codigo" value="<?php echo $producto->id; ?>">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Modificar</button>
                            <a href="productos.php" class="btn btn-secondary btn-lg">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>
