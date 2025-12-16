<?php
$title = 'Admin - Dashboard';
$css = ['css/admin.css'];
include __DIR__ . '/../layouts/header.php';
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2>UNIBOT Admin</h2>
            <p><?php echo htmlspecialchars($nombre_usuario); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="/admin" class="nav-item active">Dashboard</a>
            <a href="/admin/preguntas" class="nav-item">Preguntas Frecuentes</a>
            <a href="/chat" class="nav-item">Ver Chat</a>
            <a href="/process/logout.php" class="nav-item">Cerrar Sesión</a>
        </nav>
    </div>
    
    <div class="admin-main">
        <h1>Panel de Administración</h1>
        
        <div class="cards-grid">
            <div class="card">
                <h3>Preguntas Frecuentes</h3>
                <p>Gestiona las preguntas del chatbot</p>
                <a href="/admin/preguntas" class="btn">Administrar</a>
            </div>
            
            <div class="card">
                <h3>Chatbot</h3>
                <p>Prueba el chatbot</p>
                <a href="/chat" class="btn">Ir al Chat</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
