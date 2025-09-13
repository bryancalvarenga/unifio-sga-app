<?php

namespace App\Controllers;

use Core\Database;

class AuthController {
    public function home() { require VIEW_PATH . '/home.php';}
    public function loginForm() { require VIEW_PATH . '/auth/login.php';}

    public function login() {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id, senha_hash, nome FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u && password_verify($senha, $u['senha_hash'])) {
            session_start();
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['user_nome'] = $u['nome'];
            header('Location: /'); exit;
        }
        http_response_code(401);
        echo 'Credenciais inválidas';
    }

    public function registerForm() { require VIEW_PATH . '/auth/register.php'; }

    public function register() {
    // validações mínimas + insert
    }
    public function logout() {session_start(); session_destroy(); header('Location: /login'); }
}