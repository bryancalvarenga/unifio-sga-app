<?php
namespace App\Controllers;

use Core\Database;

class ProfileController
{
    // Exibe o perfil do usuário logado
    public function view()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");

        $stmt->execute(['id' => $_SESSION['user_id']]);

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$usuario) {
            http_response_code(404);
            echo "Usuário não encontrado.";
            return;
        }

        require VIEW_PATH . '/profile/profile.php';
    }

    // Atualiza os dados do perfil
    public function update()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $pdo = Database::getConnection();

        // Coleta dados do formulário
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);
        $senha = $_POST['senha'] ?? null;

        // Valida se o e-mail já existe em outro usuário
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $stmt->execute([
            'email' => $email,
            'id'    => $_SESSION['user_id']
        ]);
        if ($stmt->fetch()) {
            $_SESSION['erro_perfil'] = "E-mail já está em uso por outro usuário.";
            header("Location: /perfil");
            exit;
        }

        // Upload da foto
        $fotoPerfil = null;
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));

            // Valida tipo de arquivo
            $extPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($ext, $extPermitidas)) {
                $destino = '/assets/uploads/' . uniqid('user_') . '.' . $ext;
                $caminhoFisico = __DIR__ . '/../../public' . $destino;

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoFisico)) {
                    $fotoPerfil = $destino;
                }
            }
        }

        // Monta query dinâmica
        $sql = "UPDATE users 
                SET nome = :nome, email = :email, telefone = :telefone, updated_at = NOW()";
        $params = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'id' => $_SESSION['user_id']
        ];

        if (!empty($senha)) {
            $sql .= ", senha_hash = :senha";
            $params['senha'] = password_hash($senha, PASSWORD_BCRYPT);
        }

        if ($fotoPerfil) {
            $sql .= ", foto_perfil = :foto";
            $params['foto'] = $fotoPerfil;
            $_SESSION['foto_perfil'] = $fotoPerfil; // Mantém sessão atualizada
        }

        $sql .= " WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Atualiza sessão também
        $_SESSION['user_nome'] = $nome;
        $_SESSION['user_email'] = $email;
        if ($fotoPerfil) {
            $_SESSION['foto_perfil'] = $fotoPerfil;
        }

        // Redireciona de volta
        header("Location: /perfil");
        exit;
    }
}