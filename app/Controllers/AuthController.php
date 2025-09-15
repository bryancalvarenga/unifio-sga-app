<?php

namespace App\Controllers;

use Core\Database;
use App\Models\User;

/**
 * AuthController
 * Responsável pelo fluxo de autenticação:
 *  - Login
 *  - Registro
 *  - Logout
 *  - Página inicial (home)
 */
class AuthController {

    /**
     * Página inicial do sistema
     */
    public function home() {
        require VIEW_PATH . '/home.php';
    }

    /**
     * Exibe o formulário de login
     */
    public function loginForm() {
        require VIEW_PATH . '/auth/login.php';
    }

    /**
     * Processa o login de um usuário
     */
    public function login() {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        // Conexão com o banco
        $pdo = Database::getConnection();
        $userModel = new User($pdo);

        // Busca o usuário pelo email
        $u = $userModel->findByEmail($email);

        // Verifica se existe e se a senha confere
        if ($u && password_verify($senha, $u['senha_hash'])) {
            session_start();
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['user_nome'] = $u['nome'];
            $_SESSION['tipo_participacao'] = $u['tipo_participacao']; // 👈 importante!

            // Redireciona para home
            header('Location: /'); 
            exit;
        }

        // Falha no login
        http_response_code(401);
        echo "E-mail ou senha inválidos.";
    }

    /**
     * Exibe o formulário de registro
     */
    public function registerForm() {
        require VIEW_PATH . '/auth/register.php';
    }

    /**
     * Processa o registro de um novo usuário
     */
    public function register() {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $tipo = $_POST['tipo_participacao'] ?? 'ALUNO'; // default = aluno
        $foto = $_POST['foto_perfil'] ?? null;

        $pdo = Database::getConnection();
        $userModel = new User($pdo);

        // Cria o usuário
        $criado = $userModel->create($nome, $email, $telefone, $senha, $tipo, $foto);

        if ($criado) {
            // Vai para login após cadastro
            header('Location: /login');
            exit;
        }

        // Se falhar
        http_response_code(500);
        echo "Erro ao registrar usuário.";
    }

    /**
     * Faz logout e encerra a sessão
     */
    public function logout() {
        session_start(); 
        session_destroy(); 
        header('Location: /login'); 
        exit;
    }
}