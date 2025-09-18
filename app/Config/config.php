<?php
// Variáveis globais

define('BASE_PATH', dirname(__DIR__, 1));
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}
if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', APP_PATH . '/Views');
}
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost:8000');