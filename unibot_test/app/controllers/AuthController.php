<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login() {
        // Redirección 
        if ($this->isLoggedIn()) {
             if (isset($_SESSION['es_admin']) && $_SESSION['es_admin'] == 1) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/chat');
            }
        }

        $error_message = $_SESSION['error'] ?? '';
        $success_message = $_SESSION['success'] ?? '';
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('auth/login', [
            'error_message' => $error_message,
            'success_message' => $success_message
        ]);
    }

    public function processLogin() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $this->redirect('/');
        }

        $codigo_estudiante = $_POST['codigo_estudiante'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($codigo_estudiante) || empty($contrasena)) {
            $_SESSION['error'] = "Por favor completa todos los campos.";
            $this->redirect('/login');
        }

        $user = $this->userModel->findByCode($codigo_estudiante);

        if ($user && $this->userModel->verifyPassword($contrasena, $user['contrasena_hash'])) {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['nombre_usuario'] = $user['nombre'] . ' ' . $user['apellido'];
            $_SESSION['codigo_estudiante'] = $user['codigo_estudiante'];
            $_SESSION['rol_id'] = $user['rol_id'];
            $_SESSION['es_admin'] = ($user['rol_id'] == 2) ? 1 : 0; 
            
            // Redirección por rol
            if ($_SESSION['es_admin'] == 1) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/chat');
            }
            
        } else {
            $_SESSION['error'] = "Código de estudiante o contraseña incorrectos.";
            $this->redirect('/login');
        }
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $this->redirect('/');
        }

        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $codigo_estudiante = $_POST['codigo_estudiante'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $id_carrera = $_POST['id_carrera'] ?? 1;

        // Validation
        if (empty($nombre) || empty($apellido) || empty($codigo_estudiante) || empty($contrasena)) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            $this->redirect('/login');
        }

        if ($this->userModel->codeExists($codigo_estudiante)) {
            $_SESSION['error'] = "El código de estudiante ya está registrado.";
            $this->redirect('/login');
        }

        $result = $this->userModel->create($nombre, $apellido, $codigo_estudiante, $contrasena, $id_carrera);

        if ($result) {
            $_SESSION['success'] = "Cuenta creada con éxito. Por favor, inicia sesión.";
        } else {
            $_SESSION['error'] = "Error al crear la cuenta. Por favor, intenta nuevamente.";
        }

        $this->redirect('/login');
    }

    public function logout() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        $this->redirect('/login');
    }
}