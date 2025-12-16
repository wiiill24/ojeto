function mostrarModalCrear() {
    document.getElementById('modalTitle').textContent = 'Nueva Pregunta';
    document.getElementById('accion').value = 'crear';
    document.getElementById('preguntaId').value = '';
    document.getElementById('pregunta').value = '';
    document.getElementById('respuesta').value = '';
    document.getElementById('modalPregunta').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modalPregunta').style.display = 'none';
}

function editarPregunta(id) {
    fetch('process/admin_preguntas.php?accion=obtener&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalTitle').textContent = 'Editar Pregunta';
                document.getElementById('accion').value = 'editar';
                document.getElementById('preguntaId').value = data.pregunta.id;
                document.getElementById('pregunta').value = data.pregunta.pregunta;
                document.getElementById('respuesta').value = data.pregunta.respuesta;
                document.getElementById('modalPregunta').style.display = 'flex';
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error al cargar pregunta');
            console.error(error);
        });
}

function eliminarPregunta(id) {
    if (!confirm('¿Estás seguro de eliminar esta pregunta?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('accion', 'eliminar');
    formData.append('id', id);
    
    fetch('process/admin_preguntas.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error al eliminar pregunta');
        console.error(error);
    });
}

document.getElementById('formPregunta').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('process/admin_preguntas.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error al guardar pregunta');
        console.error(error);
    });
});

window.onclick = function(event) {
    const modal = document.getElementById('modalPregunta');
    if (event.target == modal) {
        cerrarModal();
    }
}
