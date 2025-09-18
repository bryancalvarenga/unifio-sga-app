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
    public function login(){
        session_start();

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        // Se faltar dados básicos
        if (empty($email) || empty($senha)) {
            $_SESSION['login_error'] = "Por favor, preencha todos os campos.";
            header("Location: /login");
            exit;
        }

        try {
            $pdo = Database::getConnection();
            $userModel = new User($pdo);

            // Busca usuário pelo email
            $u = $userModel->findByEmail($email);

            // Confere usuário + senha
            if ($u && password_verify($senha, $u['senha_hash'])) {
                // Autenticação OK
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['user_nome'] = $u['nome'];
                $_SESSION['tipo_participacao'] = $u['tipo_participacao']; 

                // Redireciona para a home
                header("Location: /");
                exit;
            } else {
                $_SESSION['login_error'] = "Email ou senha incorretos. Tente novamente.";
                header("Location: /login");
                exit;
            }
        } catch (\Throwable $e) {
            // Erro inesperado -> log e mensagem amigável
            error_log("Erro de login: " . $e->getMessage());
            $_SESSION['login_error'] = "Ocorreu um problema ao tentar fazer login. Tente novamente mais tarde.";
            header("Location: /login");
            exit;
        }
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