<?php
include_once "model/conexion.php";
session_start();

$errores = [];
$nombre = $contraseña = "";

// Tiempo de bloqueo (1 minuto)
$tiempoBloqueo = 60;

if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

// Verificar bloqueo
if (isset($_SESSION['bloqueo']) && time() < $_SESSION['bloqueo']) {
    $tiempoRestante = $_SESSION['bloqueo'] - time();
    $errores['bloqueo'] = "Has alcanzado el límite de intentos. Inténtalo de nuevo en {$tiempoRestante} segundos.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');

    if ($nombre === '') {
        $errores['nombre'] = 'El nombre de usuario es obligatorio.';
    }

    if ($contraseña === '') {
        $errores['contraseña'] = 'La contraseña es obligatoria.';
    }

    if (empty($errores)) {
        $stmt = $bd->prepare("SELECT * FROM usuarios WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user) {
            if ($contraseña === $user->contraseña) {
                $_SESSION['usuario'] = $user->nombre;
                $_SESSION['tipo'] = $user->tipo;
                $_SESSION['intentos'] = 0; // Reiniciar intentos

                if ($user->tipo == 'admin') {
                    header("Location: index.php");
                } else {
                    header("Location: compras.php");
                }
                exit();
            } else {
                $_SESSION['intentos']++;

                if ($_SESSION['intentos'] >= 3) {
                    $_SESSION['bloqueo'] = time() + $tiempoBloqueo;
                    $errores['bloqueo'] = 'Has alcanzado el límite de intentos. Espera 1 minuto antes de intentarlo de nuevo.';
                } else {
                    $restantes = 3 - $_SESSION['intentos'];
                    $errores['general'] = "Contraseña incorrecta. Intentos restantes: {$restantes}";
                }
            }
        } else {
            $errores['nombre'] = "Usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <?php if (!empty($errores['bloqueo'])): ?>
        <div class="alert alert-danger text-center"><?= $errores['bloqueo'] ?></div>
    <?php endif; ?>

    <?php if (!empty($errores['general'])): ?>
        <div class="alert alert-danger text-center"><?= $errores['general'] ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header text-center bg-dark text-light">
                    <h5>Iniciar sesión</h5>
                </div>
                <div class="card-body">
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Usuario</label>
                            <input type="text" class="form-control <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>" name="nombre" id="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
                            <div class="invalid-feedback"><?= $errores['nombre'] ?? '' ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="contraseña" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?= isset($errores['contraseña']) ? 'is-invalid' : '' ?>" name="contraseña" id="contraseña" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">Ver</button>
                            </div>
                            <div class="invalid-feedback d-block"><?= $errores['contraseña'] ?? '' ?></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="registrar_usuario.php" class="btn btn-outline-secondary">¿No tienes cuenta? Regístrate</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar/ocultar contraseña
    document.getElementById("togglePassword").addEventListener("click", function () {
        const pass = document.getElementById("contraseña");
        if (pass.type === "password") {
            pass.type = "text";
            this.textContent = "Ocultar";
        } else {
            pass.type = "password";
            this.textContent = "Ver";
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
