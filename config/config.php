<?php
session_start();

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '12345678');
define('DB_NAME', 'biblioteca');

// Configuración de la aplicación
define('APP_ROOT', dirname(dirname(__FILE__)));
define('URL_ROOT', 'http://localhost/biblioteca/public');
define('SITE_NAME', 'Biblioteca');

// Constantes
define('AAA', 'a');

?>