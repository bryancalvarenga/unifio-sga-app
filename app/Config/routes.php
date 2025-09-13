<?php 
// Definição de rotas
use App\Controllers\AuthController;
use App\Controllers\EventController;
use App\Controllers\UserController;
use App\Controllers\LoginController;


/** @var Core\Router $router */

// Instancia o roteador
$router->get('/', [AuthController::class,'home']); // Página inicial

$router->get('/login', [AuthController::class,'loginForm']); // Formulário de login
$router->post('/login', [AuthController::class,'login']); // Processa login
$router->get('/register', [AuthController::class,'registerForm']); // Formulário de registro
$router->post('/register', [AuthController::class,'register']); // Processa registro
$router->get('/logout', [AuthController::class,'logout']); // Logout

// Login
$router->get('/login', [LoginController::class, 'loginForm']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

$router->get('/eventos' , [EventController::class,'index']);
$router->get('/eventos/esportivo', [EventController::class,'esportivoForm']);
$router->post('/eventos/esportivo', [EventController::class,'salvarEsportivo']); 
$router->get('/eventos/nao-esportivo', [EventController::class,'naoEsportivoForm']);
$router->post('/eventos/nao-esportivos', [EventController::class,'salvarNaoEsportivo']);

$router->post('/eventos/cancelar', [UserController::class,'cancelar']);
$router->get('/perfil', [UserController::class,'perfil']);
$router->post('/perfil', [UserController::class,'salvarPerfil']);

// Listagem de eventos
$router->get('/eventos', [EventController::class, 'index']);

// Formulário de criação
$router->get('/eventos/novo', [EventController::class, 'createForm']);

// Processa criação
$router->post('/eventos/criar', [EventController::class, 'create']);

// Formulário de edição
$router->get('/eventos/editar', [EventController::class, 'editForm']);

// Processa atualização
$router->post('/eventos/atualizar', [EventController::class, 'update']);


// Excluir evento
$router->post('/eventos/excluir', [EventController::class, 'delete']);
