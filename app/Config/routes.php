<?php 
// Definição de rotas
use App\Controllers\AuthController;
use App\Controllers\EventController;
use App\Controllers\UserController;

/** @var Core\Router $router */

// Instancia o roteador
$router->get('/', [AuthController::class,'home']); // Página inicial

$router->get('/login', [AuthController::class,'loginForm']); // Formulário de login
$router->post('/login', [AuthController::class,'login']); // Processa login
$router->get('/register', [AuthController::class,'registerForm']); // Formulário de registro
$router->post('/register', [AuthController::class,'register']); // Processa registro
$router->get('/logout', [AuthController::class,'logout']); // Logout


$router->get('/eventos' , [EventController::class,'index']);
$router->get('/eventos/esportivo', [EventController::class,'esportivoForm']);
$router->post('/eventos/esportivo', [EventController::class,'salvarEsportivo']); 
$router->get('/eventos/nao-esportivo', [EventController::class,'naoEsportivoForm']);
$router->post('/eventos/nao-esportivos', [EventController::class,'salvarNaoEsportivo']);

$router->post('/eventos/cancelar', [UserController::class,'cancelar']);
$router->get('/perfil', [UserController::class,'perfil']);
$router->post('/perfil', [UserController::class,'salvarPerfil']);