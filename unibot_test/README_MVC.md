# MVC Architecture - Unibot

Este proyecto ha sido refactorizado para seguir el patrón de arquitectura MVC (Model-View-Controller).

## Estructura del Proyecto

```
Unibot/
├── app/
│   ├── controllers/       # Controladores (lógica de negocio)
│   │   ├── AuthController.php
│   │   ├── ChatController.php
│   │   └── HomeController.php
│   ├── models/           # Modelos (acceso a datos)
│   │   └── User.php
│   ├── views/            # Vistas (presentación)
│   │   ├── auth/
│   │   │   └── login.php
│   │   ├── chat/
│   │   │   └── index.php
│   │   ├── home/
│   │   │   └── index.php
│   │   └── layouts/
│   │       ├── header.php
│   │       └── footer.php
│   └── core/             # Clases base
│       ├── Controller.php
│       └── Model.php
├── config/               # Configuración
│   ├── database.php
│   └── router.php
├── css/                  # Estilos CSS
├── js/                   # JavaScript
├── icons/                # Iconos
├── images/               # Imágenes
├── .htaccess            # Configuración Apache (URLs limpias)
└── index.php            # Punto de entrada / Router

```

## Componentes MVC

### Models (Modelos)
- **User.php**: Maneja todas las operaciones relacionadas con usuarios (autenticación, registro, etc.)
- Heredan de la clase base `Model` que proporciona métodos para consultas a la base de datos

### Views (Vistas)
- Separadas por funcionalidad (auth, chat, home)
- Utilizan layouts para reutilizar código común (header, footer)
- Solo contienen HTML y presentación, sin lógica de negocio

### Controllers (Controladores)
- **HomeController**: Maneja la página principal
- **AuthController**: Maneja login, registro y logout
- **ChatController**: Maneja el chat del bot
- Heredan de la clase base `Controller` que proporciona métodos útiles

## Rutas Disponibles

- `GET /` - Página principal
- `GET /login` - Formulario de login
- `POST /auth/login` - Procesar login
- `POST /auth/register` - Procesar registro
- `GET /auth/logout` - Cerrar sesión
- `GET /chat` - Chat del bot (requiere autenticación)

## Cómo Funciona

1. Todas las peticiones pasan por `index.php` (router principal)
2. El `Router` analiza la URL y el método HTTP
3. Se instancia el controlador correspondiente
4. El controlador ejecuta el método (acción) adecuado
5. El controlador interactúa con los modelos si es necesario
6. El controlador renderiza la vista correspondiente

## Configuración

### Base de Datos
La configuración de la base de datos está en `config/database.php`. Ajusta las constantes según tu entorno:

```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'unibot_db');
```

### URLs Limpias
El archivo `.htaccess` está configurado para permitir URLs limpias sin `index.php` en la URL.

## Migración desde la Estructura Anterior

Los archivos antiguos aún existen pero ya no se utilizan:
- `login.php` → Ahora usa `app/views/auth/login.php`
- `unibot.php` → Ahora usa `app/views/chat/index.php`
- `process/login_process.php` → Lógica movida a `AuthController::processLogin()`
- `process/register_process.php` → Lógica movida a `AuthController::register()`
- `process/logout.php` → Lógica movida a `AuthController::logout()`
- `includes/db_connect.php` → Reemplazado por `config/database.php`

## Ventajas del MVC

1. **Separación de Responsabilidades**: Cada componente tiene una función específica
2. **Reutilización de Código**: Los modelos y vistas pueden reutilizarse
3. **Mantenibilidad**: Es más fácil mantener y modificar el código
4. **Escalabilidad**: Fácil agregar nuevas funcionalidades
5. **Testing**: Más fácil de testear cada componente por separado

## Próximos Pasos

- Puedes agregar más controladores según necesites
- Crear más modelos para otras entidades (Carrera, Chat, etc.)
- Agregar middleware para validaciones adicionales
- Implementar un sistema de sesiones más robusto
- Agregar manejo de errores centralizado

