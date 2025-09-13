<?php

namespace App\Models;

use PDO;
use PDOException;

/*
- Classe User
- Representa o usuário do sistema
- Fornece os métodos
- Interage com a tabela 'users' do DB
*/

class User {
    private $db; // Conexão PDO com o DB

    // Construtor recebe conexão PDO (injeção de dependência)
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /*
    - Cria um novo usuário no DB
    - A senha é sempre armazenada como HASH
    */
    public function create($nome, $email, $telefone, $senha, $tipo_participacao, $foto_perfil = null) {

        try {
            $sql = "INSERT INTO users (nome, email, telefone, senha_hash, tipo_participacao, foto_perfil)
            VALUES (:nome, :email, :telefone, :senha_hash, :tipo_participacao, :foto_perfil)";

            $stmt = $this->db->prepare($sql);

            // Hash seguro de senha
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':senha_hash', $senhaHash);
            $stmt->bindParam(':tipo_participacao', $tipo_participacao);
            $stmt->bindParam(':foto_perfil', $foto_perfil);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // Melhorar tratamento de erros
        }

    }
    // Busca um usuário pelo email (útil para login).
    public function findByEmail($email) {

        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    // Retorna todos os usuários
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}