<?php
$title = 'Unibot!';
$css = ['css/style.css'];
include __DIR__ . '/../layouts/header.php';
?>

<header>
    <div class="container">
        <p class="logo"> Unibot! V 1.0.0.3</p>
        <nav>
            <a href="#somos_unibot">Somos Unibot</a>
            <a href="#caracteristicas">Características</a>
            <a href="<?php echo '/Unibot_test/login.php'; ?>">Iniciar sesión / Registrarse</a>
        </nav>
    </div>
</header>

<section id="inicio">
    <h1>Descubrí cómo <br> Unibot puede ayudarte</h1>
    <button onclick="window.location.href='<?php echo '/Unibot_test/login.php'; ?>'">¡Comenzar ahora!</button>
</section>

<section id="somos_unibot">
    <div class="container">
        <div class="img-container"></div>
        <div class="texto">
            <h2>¿Qué es Unibot?</h2>
            <p>Unibot es un chat bot diseñado para revolucionar la atención a los estudiantes en la universidad. 
            Responde consultas frecuentes sobre horarios, aulas, becas y más, brindando asistencia 24/7.</p>
        </div>
    </div>
</section>

<section id="caracteristicas">
    <div class="container">
        <div class="img-container"></div>
        <div class="texto">
            <h2>Características</h2>
            <p>Estas son algunas de las ventajas que ofrece Unibot:</p>
            <ul>
                <li>Completamente online</li>
                <li>Atención las 24/7</li>
                <li>Respuestas rápidas y precisas</li>
                <li>Reduce tiempos de espera en oficinas</li>
            </ul>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>© 2025 Unibot | Proyecto universitario</p>
    </div>
</footer>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

