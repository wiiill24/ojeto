<?php
session_start();
require_once('../config/database.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}
// CORREGIDO: Usar los nombres de campos de entrada del formulario login.php
$codigo = trim($_POST['codigo_estudiante'] ?? ''); 
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$password = trim($_POST['contrasena'] ?? ''); 

// Validaciones
if (empty($codigo) || empty($nombre) || empty($apellido) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Por favor complete todos los campos']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres']);
    exit;
}

try {
    $conn = getConnection();
    
    // Verificar si el código ya existe
    $sql_check = "SELECT id FROM usuario WHERE codigo_estudiante = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $codigo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'El código de estudiante ya está registrado']);
        exit;
    }
    
    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar usuario (rol_id = 1 para estudiante normal)
    $rol_id = 1; 
    $carrera_id = intval($_POST['id_carrera'] ?? 1); // Tomar id_carrera del formulario, o 1 por defecto
    
    // CORREGIDO: Usar la tabla 'usuario' y sus columnas
    $sql = "INSERT INTO usuario (nombre, apellido, codigo_estudiante, contrasena_hash, rol_id, carrera_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $nombre, $apellido, $codigo, $hashed_password, $rol_id, $carrera_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Registro exitoso. Ahora puedes iniciar sesión',
            'redirect' => '../login.php' 
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al registrar usuario: ' . $stmt->error]);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>