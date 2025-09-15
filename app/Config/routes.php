<?php 
use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\EventController;
use App\Controllers\ParticipantController;

/** @var Core\Router $router */

/* -------------------------
   PÁGINA INICIAL
------------------------- */
$router->get('/', [AuthController::class,'home']);

/* -------------------------
   AUTENTICAÇÃO
------------------------- */
// Registro
$router->get('/register', [AuthController::class,'registerForm']);
$router->post('/register', [AuthController::class,'register']);

// Login / Logout
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

/* -------------------------
   PERFIL DO USUÁRIO
------------------------- */
$router->get('/perfil', [ProfileController::class, 'view']);
$router->post('/perfil/atualizar', [ProfileController::class, 'update']);

/* -------------------------
   EVENTOS
------------------------- */
// Listagem e detalhes
$router->get('/eventos', [EventController::class, 'index']);
$router->get('/eventos/ver', [EventController::class, 'show']);

// Criação separada (documento pede)
$router->get('/eventos/esportivo/novo', [EventController::class,'createEsportivoForm']);
$router->post('/eventos/esportivo/criar', [EventController::class,'createEsportivo']);

$router->get('/eventos/nao-esportivo/novo', [EventController::class,'createNaoEsportivoForm']);
$router->post('/eventos/nao-esportivo/criar', [EventController::class,'createNaoEsportivo']);

// Edição e exclusão
$router->get('/eventos/editar', [EventController::class, 'editForm']);
$router->post('/eventos/atualizar', [EventController::class, 'update']);
$router->post('/eventos/excluir', [EventController::class, 'delete']);

// Alterar status (Coordenação)
$router->post('/eventos/alterar-status', [EventController::class, 'changeStatus']);

/* -------------------------
   PARTICIPAÇÃO EM EVENTOS
------------------------- */
$router->get('/eventos/participar', [ParticipantController::class, 'join']);
$router->get('/eventos/cancelar', [ParticipantController::class, 'leave']);
