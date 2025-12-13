<?php
session_start();
require_once 'config/database.php'; 

// Alto solo admins
if (!isset($_SESSION['loggedin']) || $_SESSION['es_admin'] != 1) {
    header("Location: login.php");
    exit;
}

$nombre_usuario = $_SESSION['nombre_usuario'];

// Obtiene las preguntas 
try {
    $conn = getConnection();
    $sql = "SELECT * FROM preguntas_frecuentes ORDER BY id DESC";
    $result = $conn->query($sql);
    $preguntas = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }
    }
    $conn->close();
} catch (Exception $e) {
    $preguntas = []; 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Preguntas</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<div class="admin-container">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2>UNIBOT Admin</h2>
            <p><?php echo htmlspecialchars($nombre_usuario); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="admin.php" class="nav-item">Dashboard</a>
            <a href="admin_preguntas.php" class="nav-item active">Preguntas Frecuentes</a>
            <a href="unibot.php" class="nav-item">Ver Chat</a>
            <a href="process/logout.php" class="nav-item">Cerrar Sesión</a>
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
                    <?php if (!empty($preguntas)): ?>
                        <?php foreach ($preguntas as $pregunta): ?>
                        <tr>
                            <td><?php echo $pregunta['id']; ?></td>
                            <td><?php echo htmlspecialchars(substr($pregunta['pregunta'], 0, 60)); ?>...</td>
                            <td><?php echo htmlspecialchars(substr($pregunta['respuesta'], 0, 80)); ?>...</td>
                            <td>
                                <button class="btn btn-edit" onclick="editarPregunta(<?php echo $pregunta['id']; ?>)">Editar</button>
                                <button class="btn btn-delete" onclick="eliminarPregunta(<?php echo $pregunta['id']; ?>)">Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No hay preguntas registradas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalPregunta" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Nueva Pregunta</h2>
            <span class="close" onclick="cerrarModal()" style="cursor:pointer; font-size:24px;">&times;</span>
        </div>
        <form id="formPregunta">
            <input type="hidden" id="preguntaId" name="id">
            <input type="hidden" id="accion" name="accion" value="crear">
            <input type="hidden" name="categoria_id" value="1"> 
            
            <div class="form-group">
                <label>Pregunta</label>
                <input type="text" id="pregunta" name="pregunta" required style="width:100%; padding:10px; margin-bottom:10px;">
            </div>
            
            <div class="form-group">
                <label>Respuesta</label>
                <textarea id="respuesta" name="respuesta" rows="5" required style="width:100%; padding:10px; margin-bottom:10px;"></textarea>
            </div>
            
            <div class="modal-footer" style="text-align: right;">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script src="js/admin_preguntas.js"></script>

</body>
</html>