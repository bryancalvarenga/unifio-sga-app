<?php
// Carrega o autoload do Composer
require __DIR__ . '/../../vendor/autoload.php';

// Carrega variáveis do .env (na raiz do projeto)
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();


// Variáveis globais
define('BASE_PATH', dirname(__DIR__, 2));

if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . '/app');
}

if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', APP_PATH . '/Views');
}

define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost:8000');
