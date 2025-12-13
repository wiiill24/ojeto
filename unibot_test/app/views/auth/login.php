<?php
$title = 'Unibot - Login';
$css = ['css/login.css'];
$js = ['js/script.js'];
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" id="container">
    <div class="forms-container">
        <div class="forms" id="forms">

            <!-- LOGIN -->
            <form action="/auth/login" method="POST" id="Sign-in">
                <h2>Iniciar Sesión</h2>
                <p>¿No tienes cuenta? <a href="#" id="link-sign-in">Regístrate</a></p>

                <?php if (isset($success_message) && $success_message): ?>
                    <div class="alert success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if (isset($error_message) && $error_message): ?>
                    <div class="alert error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <div class="input-container">
                    <label for="codigo_estudiante">Código de Estudiante</label>
                    <input id="codigo_estudiante" name="codigo_estudiante" type="text" placeholder="Ej: E123456789" required>
                </div>

                <div class="input-container">
                    <div class="forget">
                        <label for="contrasena">Contraseña</label>
                        <a href="#">¿Olvidaste tu contraseña?</a>
                    </div>
                    <input id="contrasena" name="contrasena" type="password" placeholder="Ingresa tu contraseña" required>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="checkbox-login">
                    <label for="checkbox-login">Recordarme</label>
                </div>

                <button type="submit">INGRESAR</button>

                <div class="line-with-text"><span>o iniciar con</span></div>
                <div class="other-login">
                    <a href="#" class="google">
                        <img src="icons/google.svg" alt="google">
                        <span>Google</span>
                    </a>

            <!-- REGISTRO -->
            <form action="process/register_process.php" method="POST" id="sign-up">
                <h2>Registro</h2>
                <p>¿Ya tienes cuenta? <a href="#" id="link-sign-up">Inicia sesión</a></p>

                <div class="input-container">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" placeholder="Tu nombre" required>
                </div>
                <div class="input-container">
                    <label for="apellido">Apellido</label>
                    <input id="apellido" name="apellido" type="text" placeholder="Tu apellido" required>
                </div>
                <div class="input-container">
                    <label for="codigo_estudiante_reg">Código de Estudiante</label>
                    <input id="codigo_estudiante_reg" name="codigo_estudiante" type="text" placeholder="Ej: E123456789" required>
                </div>
                <div class="input-container">
                    <label for="contrasena_reg">Contraseña</label>
                    <input id="contrasena_reg" name="contrasena" type="password" placeholder="Crea una contraseña" required>
                </div>
                <input type="hidden" name="id_carrera" value="1">
                <button type="submit" class="btn-register">REGISTRAR</button>
            </form>
        </div>
    </div>

    <!-- BANNER / SIDEBAR -->
    <div class="banner">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
        <h1>Bienvenido a UNIBOT <span>portal estudiantil</span></h1>
        <p>Inicia sesión para acceder a tu portal</p>
        <img src="images/banner.svg" alt="banner">
    </div>

    <div class="sidebar" id="sidebar">
        <div class="sign" id="btn-sign-in">
            <img src="icons/crown.svg" alt="">
            <span>Login</span>
        </div>
        <div class="sign" id="btn-sign-up">
            <img src="icons/rule.svg" alt="">
            <span>Registro</span>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>