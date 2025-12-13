<?php
$title = 'Unibot - Chat';
$css = ['css/chat.css'];
$js = ['https://code.jquery.com/jquery-3.7.1.min.js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', 'js/main.js', 'js/chat.js'];
include __DIR__ . '/../layouts/header.php';
?>

<header class="chat-header">
    <div class="container">
        <div class="header-content">
            <div class="header-left">
                <div class="bot-avatar">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="20" r="20" fill="#4CAF50"/>
                        <path d="M20 10C14.48 10 10 14.48 10 20C10 25.52 14.48 30 20 30C25.52 30 30 25.52 30 20C30 14.48 25.52 10 20 10ZM16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18ZM24 24H16V22H24V24ZM22 20C20.9 20 20 19.1 20 18C20 16.9 20.9 16 22 16C23.1 16 24 16.9 24 18C24 19.1 23.1 20 22 20Z" fill="white"/>
                    </svg>
                </div>
                <div class="header-info">
                    <h1 class="bot-name">Unibot</h1>
                    <span class="status-indicator">
                        <span class="status-dot"></span>
                        En línea
                    </span>
                </div>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <span class="welcome-text">Hola, <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong></span>
                </div>
                <a href="/Unibot_4.0/logout" class="btn-logout">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Salir
                </a>
            </div>
        </div>
    </div>
</header>

<div class="chat-container">
    <div class="chat-wrapper">
        
        <div class="chat-messages" id="chatWindow">
            
            <div class="message-wrapper bot-wrapper fade-in">
                <div class="message-avatar">
                    <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="20" r="20" fill="#4CAF50"/>
                        <path d="M20 10C14.48 10 10 14.48 10 20C10 25.52 14.48 30 20 30C25.52 30 30 25.52 30 20C30 14.48 25.52 10 20 10ZM16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18ZM24 24H16V22H24V24ZM22 20C20.9 20 20 19.1 20 18C20 16.9 20.9 16 22 16C23.1 16 24 16.9 24 18C24 19.1 23.1 20 22 20Z" fill="white"/>
                    </svg>
                </div>
                <div class="message bot-message">
                    <div class="message-header">
                        <span class="sender-name">Unibot</span>
                        <span class="message-time" id="welcomeTime"></span>
                    </div>
                    <div class="message-content">
                        <p>¡Hola <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong>! 👋</p>
                        <p>Soy Unibot, tu asistente virtual universitario. Estoy aquí para ayudarte con:</p>
                        <ul class="help-list">
                            <li> Información sobre becas</li>
                            <li> Horarios de clases y aulas</li>
                            <li> Ubicación de instalaciones</li>
                            <li> Trámites administrativos</li>
                            <li> Preguntas frecuentes</li>
                        </ul>
                        <p>¿En qué puedo ayudarte hoy?</p>
                    </div>
                </div>
            </div>
            
            <div class="quick-suggestions" id="quickSuggestions">
                <div class="suggestions-title">Preguntas frecuentes:</div>
                <div class="suggestions-grid">
                    <button class="suggestion-chip" data-question="¿Cuál es el horario de atención?">
                         Horarios de atención
                    </button>
                    <button class="suggestion-chip" data-question="¿Cómo solicito una beca?">
                         Solicitar beca
                    </button>
                    <button class="suggestion-chip" data-question="¿Dónde está la biblioteca?">
                         Ubicación biblioteca
                    </button>
                    <button class="suggestion-chip" data-question="¿Cuándo son los exámenes finales?">
                         Exámenes finales
                    </button>
                </div>
            </div>
            
        </div>
        
        <div class="typing-indicator" id="typingIndicator" style="display: none;">
            <div class="message-avatar">
                <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="#4CAF50"/>
                    <path d="M20 10C14.48 10 10 14.48 10 20C10 25.52 14.48 30 20 30C25.52 30 30 25.52 30 20C30 14.48 25.52 10 20 10ZM16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18ZM24 24H16V22H24V24ZM22 20C20.9 20 20 19.1 20 18C20 16.9 20.9 16 22 16C23.1 16 24 16.9 24 18C24 19.1 23.1 20 22 20Z" fill="white"/>
                </svg>
            </div>
            <div class="typing-bubble">
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        
        <div class="chat-input-container">
            <form id="chatForm" class="chat-input-form">
                <div class="input-wrapper">
                    <button type="button" class="btn-emoji" title="Emojis">

                    </button>
                    <input 
                        type="text" 
                        id="userInput" 
                        class="chat-input" 
                        placeholder="Escribe tu pregunta aquí..." 
                        autocomplete="off"
                        required
                    >
                    <button type="submit" class="btn-send" id="sendButton">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        <span class="btn-send-text">Enviar</span>
                    </button>
                </div>
            </form>
            <div class="input-hint">
                Presiona <kbd>Enter</kbd> para enviar
            </div>
        </div>
        
    </div>
</div>

<script>
document.getElementById('welcomeTime').textContent = new Date().toLocaleTimeString('es-ES', {
    hour: '2-digit',
    minute: '2-digit'
});

document.querySelectorAll('.suggestion-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        const question = this.dataset.question;
        document.getElementById('userInput').value = question;
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    });
});

// baja solo al final del chat
function scrollToBottom() {
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

setTimeout(scrollToBottom, 100);
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>