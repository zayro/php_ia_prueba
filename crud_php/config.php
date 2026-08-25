<?php
/**
 * Configuración y Bootstrap de la aplicación
 * Un solo archivo para cargar todo
 */

// Configuración de la aplicación
define('APP_NAME', 'CRUD PHP');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'crud_productos');
define('DB_USER', 'root');
define('DB_PASS', 'zayro');  // XAMPP: vacío | WAMP: vacío | Producción: tu contraseña
define('DB_CHARSET', 'utf8mb4');

// Cargar núcleo
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Modelo.php';
require_once __DIR__ . '/core/Api.php';

// Cargar modelos dinámicamente
foreach (glob(__DIR__ . '/app/models/*.php') as $file) {
    require_once $file;
}

// Autoload simple
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/controllers/',
        __DIR__ . '/core/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
