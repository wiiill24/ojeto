$(document).ready(function() {

    $('#Sign-in').on('submit', function(e) { 
        e.preventDefault();
        
        const codigo = $('#codigo_estudiante').val(); 
        const password = $('#contrasena').val();      
        
        if(!codigo || !password) {
            alert("Por favor ingresa tu código y contraseña");
            return;
        }

        $.ajax({
            url: 'process/login_process.php',
            method: 'POST',
            data: { codigo: codigo, password: password }, 
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect; 
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function() {
                alert('Error al conectar con el servidor');
            }
        });
    });

    $('#sign-up').on('submit', function(e) {
        e.preventDefault(); 

        const formData = {
            nombre: $('#nombre').val(),
            apellido: $('#apellido').val(),
            codigo_estudiante: $('#codigo_estudiante_reg').val(), 
            contrasena: $('#contrasena_reg').val(), 
            id_carrera: 1
        };

        $.ajax({
            url: 'process/register_process.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Registro exitoso. Ahora serás redirigido para iniciar sesión.');
                    
                    if(response.redirect) {
                        window.location.href = response.redirect.replace('../', ''); 
                    } else {
                        location.reload(); 
                    }
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Hubo un error al intentar registrarse.');
            }
        });
    });

});