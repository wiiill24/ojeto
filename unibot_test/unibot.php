<?php
session_start();

// SEGURIDAD login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: login.php");
    exit;
}

// DATOS DEL USUARIO
$nombre_usuario = $_SESSION['nombre_usuario'];
$id_usuario = $_SESSION['id_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot - Servicios Estudiantiles</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <a href="process/logout.php" style="float: right; color: white; text-decoration: none; font-size: 14px; border: 1px solid white; padding: 5px 10px; border-radius: 15px;">Salir</a>
            <h1>Servicios Estudiantiles</h1>
            <p>Hola, <?php echo htmlspecialchars($nombre_usuario); ?></p>
        </div>

        <div class="quick-actions">
            <button class="quick-action-btn" onclick="sendQuickMessage('¿Cuál es mi horario?')">Mi Horario</button>
            <button class="quick-action-btn" onclick="sendQuickMessage('¿Cómo me inscribo?')">Inscripciones</button>
            <button class="quick-action-btn" onclick="sendQuickMessage('¿Dónde pago?')">Pagos</button>
            <button class="quick-action-btn" onclick="sendQuickMessage('Información de contacto')">Contacto</button>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    Hola <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong>, soy Unibot.
                    Puedo ayudarte con horarios, inscripciones, pagos y más.
                    ¿En qué puedo ayudarte hoy?
                    <div class="message-time" id="welcomeTime"></div>
                </div>
            </div>
        </div>

        <div class="chat-input-container">
            <form class="chat-input-form" id="chatForm">
                <input type="text" class="chat-input" id="messageInput" placeholder="Escribe tu pregunta aquí..." autocomplete="off" required>
                <button type="submit" class="send-button" id="sendButton">Enviar</button>
            </form>
        </div>
    </div>

    <script>
        window.USER_ID = <?php echo $id_usuario; ?>;
    </script>
    
    <script src="js/chat.js"></script>
</body>
</html>