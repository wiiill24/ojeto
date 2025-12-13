<?php
$title = 'Admin - Preguntas';
$css = ['css/admin.css'];
$js = [
    'https://code.jquery.com/jquery-3.7.1.min.js',
    'js/admin_preguntas.js'
];
include __DIR__ . '/../layouts/header.php';
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2>UNIBOT Admin</h2>
            <p><?php echo htmlspecialchars($nombre_usuario); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="/admin" class="nav-item">Dashboard</a>
            <a href="/admin/preguntas" class="nav-item active">Preguntas Frecuentes</a>
            <a href="/chat" class="nav-item">Ver Chat</a>
            <a href="/process/logout.php" class="nav-item">Cerrar Sesión</a>
        </nav>
    </div>
    
    <div class="admin-main">
        <div class="main-header">
            <h1>Gestión de Preguntas Frecuentes</h1>
            <button class="btn btn-primary" onclick="mostrarModalCrear()">Nueva Pregunta</button>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pregunta</th>
                        <th>Respuesta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($preguntas as $pregunta): ?>
                    <tr>
                        <td><?php echo $pregunta['id']; ?></td>
                        <td><?php echo htmlspecialchars(substr($pregunta['pregunta'], 0, 80)); ?>...</td>
                        <td><?php echo htmlspecialchars(substr($pregunta['respuesta'], 0, 100)); ?>...</td>
                        <td>
                            <button class="btn btn-edit" onclick="editarPregunta(<?php echo $pregunta['id']; ?>)">Editar</button>
                            <button class="btn btn-delete" onclick="eliminarPregunta(<?php echo $pregunta['id']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalPregunta" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Nueva Pregunta</h2>
            <span class="close" onclick="cerrarModal()">&times;</span>
        </div>
        <form id="formPregunta">
            <input type="hidden" id="preguntaId" name="id">
            <input type="hidden" id="accion" name="accion" value="crear">
            
            <div class="form-group">
                <label>Pregunta</label>
                <input type="text" id="pregunta" name="pregunta" required>
            </div>
            
            <div class="form-group">
                <label>Respuesta</label>
                <textarea id="respuesta" name="respuesta" rows="6" required></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
