<?php
session_start();
require_once('../config/database.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$codigo = trim($_POST['codigo'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($codigo) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Por favor complete todos los campos']);
    exit;
}

try {
    $conn = getConnection();
    
    // Buscar usuario por código
    $sql = "SELECT u.id, u.nombre, u.apellido, u.codigo_estudiante, u.contrasena_hash, u.rol_id
            FROM usuario u
            WHERE u.codigo_estudiante = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    
    // Verificar contraseña
    if (!password_verify($password, $user['contrasena_hash'])) {
        echo json_encode(['success' => false, 'error' => 'Contraseña incorrecta']);
        exit;
    }
    
    // Crear sesión
    $_SESSION['loggedin'] = true;
    $_SESSION['id_usuario'] = $user['id'];
    $_SESSION['codigo_estudiante'] = $user['codigo_estudiante'];
    $_SESSION['nombre_usuario'] = $user['nombre'] . ' ' . $user['apellido'];
    $_SESSION['rol_id'] = $user['rol_id'];
    $_SESSION['es_admin'] = ($user['rol_id'] == 2) ? 1 : 0;

    $redirect = ($user['rol_id'] == 2) ? 'admin.php' : 'unibot.php';
    
    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'redirect' => $redirect,
        'es_admin' => $_SESSION['es_admin']
    ]);
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>