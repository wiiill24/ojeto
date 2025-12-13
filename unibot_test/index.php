<?php
session_start();

// cargador automatico 
spl_autoload_register(function ($class) {
   $paths = [
    __DIR__ . '/app/core/',
    __DIR__ . '/app/controllers/',
    __DIR__ . '/app/models/',
    __DIR__ . '/config/' 
];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

//Cargar configuración
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/router.php';

// Define routes
$router = new Router();

// Home routes
$router->addRoute('GET', '/', 'HomeController', 'index');
$router->addRoute('GET', '/index.php', 'HomeController', 'index');

// Auth routes
$router->addRoute('GET', '/login', 'AuthController', 'login');
$router->addRoute('POST', '/auth/login', 'AuthController', 'processLogin');
$router->addRoute('POST', '/auth/register', 'AuthController', 'register');
$router->addRoute('GET', '/auth/logout', 'AuthController', 'logout');
$router->addRoute('GET', '/logout', 'AuthController', 'logout');

// Chat routes
$router->addRoute('GET', '/chat', 'ChatController', 'index');
$router->addRoute('GET', '/unibot.php', 'ChatController', 'index');

// Admin routes
$router->addRoute('GET', '/admin', 'AdminController', 'index');
$router->addRoute('GET', '/admin/preguntas', 'AdminController', 'preguntas');

// manda la solicitud
$router->dispatch();
?>