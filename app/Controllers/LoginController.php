<?php
namespace App\Controllers;

use Core\Database;
use App\Models\User;

class LoginController {

    // Mostra formulário de login
    public function loginForm() {
        require VIEW_PATH . '/auth/login.php';
    }

    // Processa login
    public function login() {
        session_start();
        $pdo = Database::getConnection();
        $userModel = new User($pdo);

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $user = $userModel->findByEmail($email);

        if ($user && password_verify($senha, $user['senha_hash'])) {
            // Salva dados essenciais na sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['tipo_participacao'] = $user['tipo_participacao'];

            header("Location: /");
            exit;
        }


        // Falha no login
        http_response_code(401);
        echo "E-mail ou senha inválidos.";
    }

    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /login");
        exit;
    }
}
