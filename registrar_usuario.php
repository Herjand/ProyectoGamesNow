<?php
include_once "model/conexion.php";
session_start();

$errores = [];
$nombre = $correo = $contraseña = $confirmar = $tipo = "";

// Validar envío
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $contraseña = trim($_POST["contraseña"] ?? '');
    $confirmar = trim($_POST["confirmar"] ?? '');
    $tipo = $_POST["tipo"] ?? '';

    // Validaciones
    if ($nombre === '') $errores['nombre'] = "El nombre es obligatorio.";
    if ($correo === '') {
        $errores['correo'] = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "El correo no es válido.";
    }

    if ($contraseña === '') $errores['contraseña'] = "La contraseña es obligatoria.";
    if ($confirmar === '') $errores['confirmar'] = "Debe confirmar la contraseña.";
    if ($contraseña !== '' && $confirmar !== '' && $contraseña !== $confirmar) {
        $errores['confirmar'] = "Las contraseñas no coinciden.";
    }

    // Validación de contraseña segura
    if (!empty($contraseña) && !preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $contraseña)) {
        $errores['contraseña'] = "La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial.";
    }

    if ($tipo === '') $errores['tipo'] = "Debe seleccionar un tipo de usuario.";

    // Verificar que no exista el correo
    if (empty($errores)) {
        $verificarCorreo = $bd->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $verificarCorreo->bindParam(':correo', $correo);
        $verificarCorreo->execute();
        if ($verificarCorreo->fetch()) {
            $errores['correo'] = "Ya existe una cuenta con ese correo.";
        }
    }

    // Insertar si todo está bien
    // Insertar si todo está bien
    if (empty($errores)) {
        try {
            // Preparar la consulta de inserción
            $stmt = $bd->prepare("INSERT INTO usuarios (nombre, correo, contraseña, tipo) VALUES (?, ?, ?, ?)");
        
            // Vincular los parámetros correctamente, asegurándonos de que no haya parámetros con nombre
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $correo);
            $stmt->bindValue(3, $contraseña); // Contraseña no hasheada por ahora
            $stmt->bindValue(4, $tipo);
        
            // Ejecutar la consulta
            $stmt->execute();
        
            // Mostrar mensaje de éxito
            // Mostrar mensaje de éxito
            echo '
            <div class="container mt-5">
                <div class="alert alert-success text-center p-4 shadow-lg rounded" role="alert" style="background-color: #28a745; color: white; font-weight: bold;">
                    <h3>¡Usuario registrado correctamente!</h3>
                </div>
                <div class="text-center mt-4">
                    <a href="registrar_usuario.php" class="btn btn-primary btn-lg px-4 py-2" style="background-color: #007bff; border: none; border-radius: 50px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        Registrar otro usuario
                    </a>
                    <a href="login.php" class="btn btn-success btn-lg px-4 py-2 ms-3" style="background-color: #28a745; border: none; border-radius: 50px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        Iniciar sesión
                    </a>
                </div>
            </div>';

            exit();
        } catch (PDOException $e) {
            // Si ocurre un error, mostrarlo
            echo "Error al insertar: " . $e->getMessage();
            echo "SQL: " . $stmt->queryString; // Mostrar la consulta que se está ejecutando para depuración
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h5>Registrar nuevo usuario</h5>
                </div>
                <div class="card-body">
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($nombre) ?>">
                            <div class="invalid-feedback"><?= $errores['nombre'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" class="form-control <?= isset($errores['correo']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($correo) ?>">
                            <div class="invalid-feedback"><?= $errores['correo'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" name="contraseña" class="form-control <?= isset($errores['contraseña']) ? 'is-invalid' : '' ?>" id="contraseña">
                                <button type="button" id="togglePassword" class="btn btn-outline-secondary">Ver</button>
                            </div>
                            <div class="invalid-feedback"><?= $errores['contraseña'] ?? '' ?></div>
                            <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="confirmar" class="form-control <?= isset($errores['confirmar']) ? 'is-invalid' : '' ?>" id="confirmar">
                            <div class="invalid-feedback"><?= $errores['confirmar'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de usuario</label>
                            <select name="tipo" class="form-select <?= isset($errores['tipo']) ? 'is-invalid' : '' ?>">
                                <option value="">Selecciona una opción</option>
                                <option value="admin" <?= $tipo == 'admin' ? 'selected' : '' ?>>Administrador</option>
                                <option value="cliente" <?= $tipo == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                            </select>
                            <div class="invalid-feedback"><?= $errores['tipo'] ?? '' ?></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>

                        <div class="mt-3 text-center">
                            <a href="login.php" class="btn btn-outline-secondary">¿Ya tienes cuenta? Inicia sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mostrar y ocultar contraseñas -->
<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        const passwordField = document.getElementById("contraseña");
        const confirmField = document.getElementById("confirmar");
        const toggleBtn = this;

        if (passwordField.type === "password" && confirmField.type === "password") {
            passwordField.type = "text";
            confirmField.type = "text";
            toggleBtn.textContent = "Ocultar";
        } else {
            passwordField.type = "password";
            confirmField.type = "password";
            toggleBtn.textContent = "Ver";
        }
    });
</script>

<!-- Script de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
