<?php
namespace App\Controllers;

use Core\Database;

class ParticipantController
{
    public function join()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) { header("Location: /login"); exit; }

        $eventoId = $_GET['id'] ?? null;
        if (!$eventoId) { http_response_code(400); echo "Evento não informado."; return; }

        $pdo  = Database::getConnection();
        $tipo = $_SESSION['tipo_participacao'];

        // Só ALUNO/COMUNIDADE participam de evento público
        if (!in_array($tipo, ['ALUNO','COMUNIDADE'])) { http_response_code(403); echo "Você não pode participar deste evento."; return; }

        $stmt = $pdo->prepare("SELECT aberto_ao_publico FROM events WHERE id = :id");
        $stmt->execute(['id' => $eventoId]);
        $evt = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$evt) { http_response_code(404); echo "Evento não encontrado."; return; }
        if ((int)$evt['aberto_ao_publico'] !== 1) { http_response_code(403); echo "Este evento não é público."; return; }

        $ins = $pdo->prepare("INSERT IGNORE INTO event_participants (evento_id, usuario_id, status)
                            VALUES (:evento_id, :usuario_id, 'INSCRITO')");
        $ins->execute(['evento_id' => $eventoId, 'usuario_id' => $_SESSION['user_id']]);

        header("Location: /eventos/ver?id=".$eventoId);
        exit;
    }

    public function leave()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) { header("Location: /login"); exit; }

        $eventoId = $_GET['id'] ?? null;
        if (!$eventoId) { http_response_code(400); echo "Evento não informado."; return; }

        $pdo = Database::getConnection();
        $del = $pdo->prepare("DELETE FROM event_participants WHERE evento_id = :evento_id AND usuario_id = :usuario_id");
        $del->execute(['evento_id' => $eventoId, 'usuario_id' => $_SESSION['user_id']]);

        header("Location: /eventos/ver?id=".$eventoId);
        exit;
    }

}
