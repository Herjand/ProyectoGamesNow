<?php
use PHPUnit\Framework\TestCase;

require_once 'model/conexion.php'; // Asegúrate de incluir tu archivo de conexión

class LoginTest extends TestCase {

    private $conn;

    protected function setUp(): void {
        // Simulación de la conexión a la base de datos
        $this->conn = $this->createMock(mysqli::class);
    }

    public function testInicioSesionAdmin() {
        // Simular usuario admin en la BD
        $mockUser = [
            'nombre' => 'Juan Perez',
            'contraseña' => password_hash('admin123', PASSWORD_DEFAULT),
            'tipo' => 'admin'
        ];

        // Simular consulta a la base de datos
        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('get_result')->willReturn(new ArrayObject([$mockUser]));

        $this->conn->method('prepare')->willReturn($stmt);

        // Simular inicio de sesión
        $_POST['nombre'] = 'Juan Perez';
        $_POST['contraseña'] = 'admin123';

        session_start();
        include 'login.php';

        // Verificar que las variables de sesión se establecieron
        $this->assertEquals('Juan Perez', $_SESSION['usuario']);
        $this->assertEquals('admin', $_SESSION['tipo']);
    }

    public function testInicioSesionCliente() {
        $mockUser = [
            'nombre' => 'Pedro Domingo',
            'contraseña' => password_hash('cliente123', PASSWORD_DEFAULT),
            'tipo' => 'cliente'
        ];

        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('get_result')->willReturn(new ArrayObject([$mockUser]));

        $this->conn->method('prepare')->willReturn($stmt);

        $_POST['nombre'] = 'Pedro Domingo';
        $_POST['contraseña'] = 'cliente123';

        session_start();
        include 'login.php';

        $this->assertEquals('Pedro Domingo', $_SESSION['usuario']);
        $this->assertEquals('cliente', $_SESSION['tipo']);
    }

    public function testUsuarioNoExiste() {
        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('get_result')->willReturn(new ArrayObject([]));

        $this->conn->method('prepare')->willReturn($stmt);

        $_POST['nombre'] = 'UsuarioFalso';
        $_POST['contraseña'] = 'claveIncorrecta';

        session_start();
        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString("Error: Usuario no encontrado.", $output);
    }

    public function testContraseñaIncorrecta() {
        $mockUser = [
            'nombre' => 'Juan Perez',
            'contraseña' => password_hash('admin123', PASSWORD_DEFAULT),
            'tipo' => 'admin'
        ];

        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('get_result')->willReturn(new ArrayObject([$mockUser]));

        $this->conn->method('prepare')->willReturn($stmt);

        $_POST['nombre'] = 'Juan Perez';
        $_POST['contraseña'] = 'incorrecta123';

        session_start();
        ob_start();
        include 'login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString("Error: Contraseña incorrecta.", $output);
    }
}
