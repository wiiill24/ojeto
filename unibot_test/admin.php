<?php
session_start();

// Solo administradores
if (!isset($_SESSION['loggedin']) || $_SESSION['es_admin'] != 1) {
    header("Location: login.php");
    exit;
}

$nombre_usuario = $_SESSION['nombre_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-container">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2>UNIBOT Admin</h2>
            <p><?php echo htmlspecialchars($nombre_usuario); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="admin.php" class="nav-item active">Dashboard</a>
            <a href="admin_preguntas.php" class="nav-item">Preguntas Frecuentes</a>
            <a href="unibot.php" class="nav-item">Ver Chat</a>
            <a href="process/logout.php" class="nav-item">Cerrar Sesión</a>
        </nav>
    </div>
    
    <div class="admin-main">
        <div class="main-header">
            <h1>Panel de Administración</h1>
        </div>
        
        <div class="cards-grid">
            <div class="card">
                <h3>Preguntas Frecuentes</h3>
                <p>Gestiona las respuestas del chatbot (Crear, Editar, Eliminar).</p>
                <a href="admin_preguntas.php" class="btn btn-primary">Administrar</a>
            </div>
            
            <div class="card">
                <h3>Simulador de Chat</h3>
                <p>Prueba cómo responde el bot a los estudiantes.</p>
                <a href="unibot.php" class="btn btn-secondary">Ir al Chat</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>