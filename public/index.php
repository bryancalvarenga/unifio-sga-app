<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// raiz do projeto
define('ROOT_PATH', dirname(__DIR__));

// .env
$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->safeLoad();

// core/config
require_once ROOT_PATH . '/app/Config/config.php';
require_once ROOT_PATH . '/app/Core/Router.php';

// cria o roteador **antes** de carregar as rotas
$router = new Core\Router();

// rotas (usa a variável $router já criada)
require_once ROOT_PATH . '/app/Config/routes.php';

// despacha
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
