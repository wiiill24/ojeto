const API_URL = 'process/chatbot-api.php'; 

const USUARIO_ID = (typeof window.USER_ID !== 'undefined') ? window.USER_ID : 1;

const chatMessages = document.getElementById('chatMessages');
const messageInput = document.getElementById('messageInput');
const chatForm = document.getElementById('chatForm');
const sendButton = document.getElementById('sendButton');

if(document.getElementById('welcomeTime')) {
    document.getElementById('welcomeTime').textContent = getCurrentTime();
}

if(chatForm) {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const mensaje = messageInput.value.trim();
        
        if (mensaje) {
            await enviarMensaje(mensaje);
            messageInput.value = '';
        }
    });
}

window.addEventListener('load', () => {
    if(messageInput) messageInput.focus();
});

// mensaje al db
async function enviarMensaje(mensaje) {
    // mensaje del usuario
    agregarMensaje('user', mensaje);
        messageInput.disabled = true;
    sendButton.disabled = true;
    
    // indicador de escribir
    const typingIndicator = mostrarTypingIndicator();
    
    try {
        // pediticion db
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                mensaje: mensaje,
                usuario_id: USUARIO_ID
            })
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const data = await response.json();
        
        // adios indicador de escribir
        typingIndicator.remove();
        
        if (data.error) {
            agregarMensaje('bot', `
                <div class="error-message">
                     ${data.mensaje}
                    ${data.detalle ? '<br><small>' + data.detalle + '</small>' : ''}
                </div>
            `);
        } else {
            procesarRespuesta(data);
        }
        
    } catch (error) {
        console.error('Error:', error);
        typingIndicator.remove();
        agregarMensaje('bot', `
            <div class="error-message">
                 Lo siento, ocurrió un error al procesar tu mensaje.<br>
                <small>Verifica que el archivo process/chatbot-api.php exista y la base de datos esté conectada.</small>
            </div>
        `);
    } finally {
        messageInput.disabled = false;
        sendButton.disabled = false;
        messageInput.focus();
    }
}

function procesarRespuesta(data) {
    let contenidoHTML = '';

    if (data.encontrado) {
        if (data.tipo_respuesta === 'preguntas_frecuentes') {
            contenidoHTML = formatearPreguntasFrecuentes(data.respuestas);
        } else if (data.tipo_respuesta === 'horarios') {
            contenidoHTML = formatearHorarios(data.respuestas);
        } else if (data.tipo_respuesta === 'docentes') {
            contenidoHTML = formatearDocentes(data.respuestas);
        } else if (data.tipo_respuesta === 'calendario') {
            contenidoHTML = formatearCalendario(data.respuestas);
        } else if (data.tipo_respuesta === 'carreras') {
            contenidoHTML = formatearCarreras(data.respuestas);
        } else if (data.tipo_respuesta === 'asignaturas') {
            contenidoHTML = formatearAsignaturas(data.respuestas);
        }
    } else {
    // si no hay respuesta muestra
        let mensajeBot = data.mensaje_bot || 'No encontré información sobre tu consulta.';
        mensajeBot = escapeHtml(mensajeBot).replace(/\n/g, '<br>');
        
        contenidoHTML = `
            <div class="no-results">
                ${mensajeBot}
            </div>
        `;
    }

    agregarMensaje('bot', contenidoHTML);
}

// como responde
function formatearPreguntasFrecuentes(respuestas) {
    let html = '<div style="margin-bottom: 10px;">Encontré la siguiente información:</div>';
    
    respuestas.forEach(item => {
        html += `
            <div class="result-card">
                <h4> ${escapeHtml(item.pregunta)}</h4>
                <p>${escapeHtml(item.respuesta)}</p>
            </div>
        `;
    });
    return html;
}

function formatearHorarios(horarios) {
    let html = '<div style="margin-bottom: 10px;"> Aquí está el horario encontrado:</div>';
    
    horarios.forEach(horario => {
        html += `
            <div class="horario-card">
                <div class="horario-header">
                    <span class="dia">${escapeHtml(horario.dia_semana)}</span>
                    <span class="hora">${escapeHtml(horario.hora_inicio)} - ${escapeHtml(horario.hora_fin)}</span>
                </div>
                <div class="detalle">
                    <strong> ${escapeHtml(horario.asignatura) || 'N/A'}</strong><br>
                     ${escapeHtml(horario.docente) || 'Docente no asignado'}<br>
                     Aula: ${escapeHtml(horario.aula) || 'N/A'}
                </div>
            </div>
        `;
    });
    return html;
}

function formatearDocentes(docentes) {
    let html = '<div style="margin-bottom: 10px;">Información de docentes:</div>';
    docentes.forEach(doc => {
        html += `
            <div class="result-card">
                <h4>${escapeHtml(doc.nombre_completo)}</h4>
                <p>
                    ${doc.especialidad ? 'Especialidad: ' + escapeHtml(doc.especialidad) + '<br>' : ''}
                    ${doc.email ? 'Email: ' + escapeHtml(doc.email) : ''}
                </p>
            </div>
        `;
    });
    return html;
}

function formatearCalendario(eventos) {
    let html = '<div style="margin-bottom: 10px;">Eventos del calendario:</div>';
    eventos.forEach(ev => {
        html += `
            <div class="result-card">
                <h4>${escapeHtml(ev.nombre)}</h4>
                <p>Fecha: ${escapeHtml(ev.fecha_inicio)} ${ev.fecha_fin ? ' al ' + escapeHtml(ev.fecha_fin) : ''}</p>
                <p><small>${escapeHtml(ev.descripcion || '')}</small></p>
            </div>
        `;
    });
    return html;
}

function formatearCarreras(carreras) {
    let html = '<div style="margin-bottom: 10px;">Carreras encontradas:</div>';
    carreras.forEach(c => {
        html += `
            <div class="result-card">
                <h4>${escapeHtml(c.nombre)} (${escapeHtml(c.codigo)})</h4>
                <p>Duración: ${escapeHtml(c.duracion)}</p>
            </div>
        `;
    });
    return html;
}

function formatearAsignaturas(asignaturas) {
    let html = '<div style="margin-bottom: 10px;">Asignaturas encontradas:</div>';
    asignaturas.forEach(a => {
        html += `
            <div class="result-card">
                <h4>${escapeHtml(a.nombre)} (${escapeHtml(a.codigo)})</h4>
                <p>Semestre: ${escapeHtml(a.semestre)} | Créditos: ${escapeHtml(a.creditos)}</p>
            </div>
        `;
    });
    return html;
}

function agregarMensaje(tipo, contenido) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${tipo}`;
    const time = getCurrentTime();
    messageDiv.innerHTML = `
        <div class="message-content">
            ${contenido}
            <div class="message-time">${time}</div>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function mostrarTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.className = 'message bot';
    typingDiv.innerHTML = `
        <div class="typing-indicator show">
            <span></span>
            <span></span>
            <span></span>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    scrollToBottom();
    return typingDiv;
}

function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString('es-ES', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });
}

//vaya automatico
function scrollToBottom() {
    if(chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

window.sendQuickMessage = function(mensaje) {
    if(messageInput) {
        messageInput.value = mensaje;
        enviarMensaje(mensaje);
    }
};

function escapeHtml(text) {
    if (!text) return '';
    text = String(text);
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

if(messageInput) {
    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
}

console.log('Chatbot iniciado correctamente en modo MVC');