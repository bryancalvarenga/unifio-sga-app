<?php
namespace App\Controllers;

use Core\Database;
use App\Models\Event;

class EventController {

    public function index() {
        $pdo = Database::getConnection();
        $eventModel = new Event($pdo);
        $eventos = $eventModel->getAll();

        require VIEW_PATH . '/events/index.php';
    }

    public function createForm() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        require VIEW_PATH . '/events/create.php';
    }

    public function create() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        // Apenas Atlética e Professor podem criar
        if (!in_array($_SESSION['tipo_participacao'], ['ATLETICA', 'PROFESSOR'])) {
            http_response_code(403);
            echo "Você não tem permissão para criar eventos.";
            return;
        }

        $pdo = Database::getConnection();
        $eventModel = new Event($pdo);

        $ok = $eventModel->create(
            $_SESSION['user_id'],
            $_POST['categoria'],
            !empty($_POST['subtipo_esportivo']) ? $_POST['subtipo_esportivo'] : null,
            !empty($_POST['subtipo_nao_esportivo']) ? $_POST['subtipo_nao_esportivo'] : null,
            $_POST['finalidade'] ?? null,
            $_POST['data_evento'],
            $_POST['periodo'],
            $_POST['aberto_ao_publico'] ?? 0,
            !empty($_POST['estimativa_participantes']) ? (int) $_POST['estimativa_participantes'] : null,
            $_POST['materiais_necessarios'] ?? null,
            $_POST['usa_materiais_instituicao'] ?? 0,
            $_POST['responsavel'],
            $_POST['arbitro'] ?? null,
            null,
            $_POST['observacoes'] ?? null
        );

        if ($ok) {
            header("Location: /eventos");
            exit;
        }

        http_response_code(500);
        echo "Erro ao cadastrar evento.";
    }

    public function editForm() {
        session_start();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "ID do evento não informado.";
            return;
        }

        $pdo = Database::getConnection();
        $eventModel = new Event($pdo);
        $evento = $eventModel->findById($id);

        if (!$evento) {
            http_response_code(404);
            echo "Evento não encontrado.";
            return;
        }

        // Verifica permissões
        if (!in_array($_SESSION['tipo_participacao'], ['ATLETICA', 'PROFESSOR'])) {
            http_response_code(403);
            echo "Você não tem permissão para editar eventos.";
            return;
        }

        if ($evento['usuario_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "Você só pode editar eventos que você mesmo criou.";
            return;
        }

        require VIEW_PATH . '/events/edit.php';
    }

    public function update() {
        session_start();
        $id = $_POST['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "ID do evento não informado.";
            return;
        }

        $pdo = Database::getConnection();
        $eventModel = new Event($pdo);
        $evento = $eventModel->findById($id);

        if (!$evento) {
            http_response_code(404);
            echo "Evento não encontrado.";
            return;
        }

        // Verifica permissões
        if (!in_array($_SESSION['tipo_participacao'], ['ATLETICA', 'PROFESSOR'])) {
            http_response_code(403);
            echo "Você não tem permissão para atualizar eventos.";
            return;
        }

        if ($evento['usuario_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "Você só pode atualizar eventos que você mesmo criou.";
            return;
        }

        $dados = [
            'categoria' => $_POST['categoria'] ?? $evento['categoria'],
            'subtipo_esportivo' => !empty($_POST['subtipo_esportivo']) ? $_POST['subtipo_esportivo'] : null,
            'subtipo_nao_esportivo' => !empty($_POST['subtipo_nao_esportivo']) ? $_POST['subtipo_nao_esportivo'] : null,
            'finalidade' => $_POST['finalidade'] ?? $evento['finalidade'],
            'data_evento' => $_POST['data_evento'] ?? $evento['data_evento'],
            'periodo' => $_POST['periodo'] ?? $evento['periodo'],
            'aberto_ao_publico' => $_POST['aberto_ao_publico'] ?? $evento['aberto_ao_publico'],
            'estimativa_participantes' => !empty($_POST['estimativa_participantes']) ? (int) $_POST['estimativa_participantes'] : $evento['estimativa_participantes'],
            'materiais_necessarios' => $_POST['materiais_necessarios'] ?? $evento['materiais_necessarios'],
            'usa_materiais_instituicao' => $_POST['usa_materiais_instituicao'] ?? $evento['usa_materiais_instituicao'],
            'responsavel' => $_POST['responsavel'] ?? $evento['responsavel'],
            'arbitro' => $_POST['arbitro'] ?? $evento['arbitro'],
            'lista_participantes_path' => $evento['lista_participantes_path'],
            'observacoes' => $_POST['observacoes'] ?? $evento['observacoes'],
            'status' => $_POST['status'] ?? $evento['status'],
        ];

        if ($eventModel->update($id, $dados)) {
            header("Location: /eventos");
            exit;
        }

        http_response_code(500);
        echo "Erro ao atualizar evento.";
    }

    public function delete() {
        session_start();
        $id = $_POST['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "ID do evento não informado.";
            return;
        }

        $pdo = Database::getConnection();
        $eventModel = new Event($pdo);
        $evento = $eventModel->findById($id);

        if (!$evento) {
            http_response_code(404);
            echo "Evento não encontrado.";
            return;
        }

        // Verifica permissões
        if (!in_array($_SESSION['tipo_participacao'], ['ATLETICA', 'PROFESSOR'])) {
            http_response_code(403);
            echo "Você não tem permissão para excluir eventos.";
            return;
        }

        if ($evento['usuario_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "Você só pode excluir eventos que você mesmo criou.";
            return;
        }

        if ($eventModel->delete($id)) {
            header("Location: /eventos");
            exit;
        }

        http_response_code(500);
        echo "Erro ao excluir evento.";
    }
}