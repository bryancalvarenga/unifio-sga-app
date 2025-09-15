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

    /* -------------------------
        EVENTOS ESPORTIVOS
    ------------------------- */
    public function createEsportivoForm() {
        session_start();
        if (($_SESSION['tipo_participacao'] ?? null) !== 'ATLETICA') {
            http_response_code(403);
            echo "Apenas Atléticas podem criar eventos esportivos.";
            return;
        }

        require VIEW_PATH . '/events/create-esportivo.php';
    }

    public function createEsportivo() {
        session_start();
        if (($_SESSION['tipo_participacao'] ?? null) !== 'ATLETICA') {
            http_response_code(403);
            echo "Apenas Atléticas podem criar eventos esportivos.";
            return;
        }

        $pdo = Database::getConnection();
        $eventModel = new Event($pdo);

        // valida conflito de horário
        $stmt = $pdo->prepare("SELECT id FROM events WHERE data_evento = :d AND periodo = :p");
        $stmt->execute(['d' => $_POST['data_evento'], 'p' => $_POST['periodo']]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo "Já existe evento marcado para {$_POST['data_evento']} no período {$_POST['periodo']}.";
            return;
        }

        $ok = $eventModel->create(
            $_SESSION['user_id'],
            'ESPORTIVO',
            $_POST['subtipo_esportivo'] ?? null,
            null,
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
        echo "Erro ao cadastrar evento esportivo.";
    }

    /* -------------------------
    EVENTOS NÃO ESPORTIVOS
    ------------------------- */
    public function createNaoEsportivoForm() {
        session_start();
        if (($_SESSION['tipo_participacao'] ?? null) !== 'PROFESSOR') {
            http_response_code(403);
            echo "Apenas Professores podem criar eventos não esportivos.";
            return;
        }

        require VIEW_PATH . '/events/create-nao-esportivo.php';
    }

    public function createNaoEsportivo() {
        session_start();
        if (($_SESSION['tipo_participacao'] ?? null) !== 'PROFESSOR') {
            http_response_code(403);
            echo "Apenas Professores podem criar eventos não esportivos.";
            return;
        }

        $pdo = Database::getConnection();
        $eventModel = new Event($pdo);

        // valida conflito de horário
        $stmt = $pdo->prepare("SELECT id FROM events WHERE data_evento = :d AND periodo = :p");
        $stmt->execute(['d' => $_POST['data_evento'], 'p' => $_POST['periodo']]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo "Já existe evento marcado para {$_POST['data_evento']} no período {$_POST['periodo']}.";
            return;
        }

        $ok = $eventModel->create(
            $_SESSION['user_id'],
            'NAO_ESPORTIVO',
            null,
            $_POST['subtipo_nao_esportivo'] ?? null,
            $_POST['finalidade'] ?? null,
            $_POST['data_evento'],
            $_POST['periodo'],
            $_POST['aberto_ao_publico'] ?? 0,
            !empty($_POST['estimativa_participantes']) ? (int) $_POST['estimativa_participantes'] : null,
            $_POST['materiais_necessarios'] ?? null,
            $_POST['usa_materiais_instituicao'] ?? 0,
            $_POST['responsavel'],
            null,
            null,
            $_POST['observacoes'] ?? null
        );

        if ($ok) {
            header("Location: /eventos");
            exit;
        }

        http_response_code(500);
        echo "Erro ao cadastrar evento não esportivo.";
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

        // Permissões de edição
        $tipo = $_SESSION['tipo_participacao'] ?? null;

        $ehDono = ($evento['usuario_id'] == $_SESSION['user_id']);
        $podeEditar = (
            ($tipo === 'ATLETICA'  && $evento['categoria'] === 'ESPORTIVO'     && $ehDono) ||
            ($tipo === 'PROFESSOR' && $evento['categoria'] === 'NAO_ESPORTIVO' && $ehDono) ||
            ($tipo === 'COORDENACAO') // coordenação pode sempre editar (aprovar/rejeitar)
        );

        if (!$podeEditar) {
            http_response_code(403);
            echo "Você não tem permissão para editar este evento.";
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

        $tipo = $_SESSION['tipo_participacao'] ?? null;

        // Só Atlética ou Professor podem atualizar, e apenas os próprios
        $ehDono = ($evento['usuario_id'] == $_SESSION['user_id']);

        $podeAtualizar = (
            ($tipo === 'ATLETICA'  && $evento['categoria'] === 'ESPORTIVO'     && $ehDono) ||
            ($tipo === 'PROFESSOR' && $evento['categoria'] === 'NAO_ESPORTIVO' && $ehDono) ||
            ($tipo === 'COORDENACAO') // Coordenação pode atualizar status
        );

        if (!$podeAtualizar) {
            http_response_code(403);
            echo "Você não tem permissão para atualizar este evento.";
            return;
        }

        // Categoria não pode fugir do permitido
        $categoria = $_POST['categoria'] ?? $evento['categoria'];
        if ($tipo === 'ATLETICA' && $categoria !== 'ESPORTIVO') {
            http_response_code(403);
            echo "Atlética só pode manter eventos ESPORTIVOS.";
            return;
        }
        if ($tipo === 'PROFESSOR' && $categoria !== 'NAO_ESPORTIVO') {
            http_response_code(403);
            echo "Professor só pode manter eventos NÃO ESPORTIVOS.";
            return;
        }

        // Checa se já existe evento no mesmo dia/período (exceto este mesmo id)
        $stmt = $pdo->prepare("SELECT id FROM events WHERE data_evento = :d AND periodo = :p AND id != :id");
        $stmt->execute(['d' => $_POST['data_evento'], 'p' => $_POST['periodo'], 'id' => $id]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo "Já existe evento marcado para {$_POST['data_evento']} no período {$_POST['periodo']}.";
            return;
        }

        $dados = [
            'categoria' => $categoria,
            'subtipo_esportivo' => !empty($_POST['subtipo_esportivo']) ? $_POST['subtipo_esportivo'] : null,
            'subtipo_nao_esportivo' => !empty($_POST['subtipo_nao_esportivo']) ? $_POST['subtipo_nao_esportivo'] : null,
            'finalidade' => $_POST['finalidade'] ?? $evento['finalidade'],
            'data_evento' => $_POST['data_evento'] ?? $evento['data_evento'],
            'periodo' => $_POST['periodo'] ?? $evento['periodo'],
            'aberto_ao_publico' => $_POST['aberto_ao_publico'] ?? $evento['aberto_ao_publico'],
            'estimativa_participantes' => !empty($_POST['estimativa_participantes']) ? (int)$_POST['estimativa_participantes'] : $evento['estimativa_participantes'],
            'materiais_necessarios' => $_POST['materiais_necessarios'] ?? $evento['materiais_necessarios'],
            'usa_materiais_instituicao' => $_POST['usa_materiais_instituicao'] ?? $evento['usa_materiais_instituicao'],
            'responsavel' => $_POST['responsavel'] ?? $evento['responsavel'],
            'arbitro' => $_POST['arbitro'] ?? $evento['arbitro'],
            'lista_participantes_path' => $evento['lista_participantes_path'],
            'observacoes' => $_POST['observacoes'] ?? $evento['observacoes']
        ];
        // Define o status de acordo com o tipo de usuário
        if ($tipo === 'COORDENACAO') {
            $dados['status'] = $_POST['status'] ?? $evento['status'];
        } else {
            $dados['status'] = $evento['status']; // mantém sem alteração
        }

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

        $tipo = $_SESSION['tipo_participacao'] ?? null;

        // Só Atlética ou Professor podem excluir, e apenas os próprios
        if (!in_array($tipo, ['ATLETICA', 'PROFESSOR']) ||
            $evento['usuario_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "Você não tem permissão para excluir este evento.";
            return;
        }

        if ($eventModel->delete($id)) {
            header("Location: /eventos");
            exit;
        }

        http_response_code(500);
        echo "Erro ao excluir evento.";
    }

    public function show() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "ID do evento não informado.";
            return;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $evento = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$evento) {
            http_response_code(404);
            echo "Evento não encontrado.";
            return;
        }

        $usuarioId = (int) $_SESSION['user_id'];
        $tipo      = $_SESSION['tipo_participacao'];

        // Já está inscrito?
        $stmt = $pdo->prepare("SELECT 1 FROM event_participants WHERE evento_id = :evento_id AND usuario_id = :usuario_id");
        $stmt->execute(['evento_id' => (int)$id, 'usuario_id' => $usuarioId]);
        $inscrito = (bool) $stmt->fetch();

        // Pode editar/excluir? Somente dono + categoria certa
        $podeEditar = (
            ($tipo === 'ATLETICA'  && $evento['categoria'] === 'ESPORTIVO'     && (int)$evento['usuario_id'] === $usuarioId) ||
            ($tipo === 'PROFESSOR' && $evento['categoria'] === 'NAO_ESPORTIVO' && (int)$evento['usuario_id'] === $usuarioId) ||
            ($tipo === 'COORDENACAO')
        );

        // Pode participar? ALUNO/COMUNIDADE; você pode manter a regra de "aberto_ao_publico"
        $usuarioPodeParticipar = in_array($tipo, ['ALUNO', 'COMUNIDADE']) && (int)$evento['aberto_ao_publico'] === 1;

        require VIEW_PATH . "/events/show.php";
    }

    public function changeStatus() {
        session_start();
        if (($_SESSION['tipo_participacao'] ?? null) !== 'COORDENACAO') {
            http_response_code(403);
            echo "Apenas a Coordenação pode alterar status.";
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $novoStatus = $_POST['status'] ?? null;
        $observacao = trim($_POST['observacao'] ?? '');

        $permitidos = ['AGENDADO','APROVADO','REJEITADO','CANCELADO','FINALIZADO'];
        if (!$id || !in_array($novoStatus, $permitidos, true)) {
            http_response_code(400);
            echo "Dados inválidos.";
            return;
        }

        $pdo = Database::getConnection();
        // verifica evento
        $stmt = $pdo->prepare("SELECT id, status FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $evento = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$evento) {
            http_response_code(404);
            echo "Evento não encontrado.";
            return;
        }

        // atualiza status
        $pdo->beginTransaction();
        try {
            $up = $pdo->prepare("UPDATE events SET status = :s, updated_at = NOW() WHERE id = :id");
            $up->execute(['s' => $novoStatus, 'id' => $id]);

            // registra histórico (se tiver a tabela event_approvals)
            if ($pdo->query("SHOW TABLES LIKE 'event_approvals'")->fetch()) {
                if ($pdo->query("SHOW TABLES LIKE 'event_approvals'")->fetch()) {
                $log = $pdo->prepare("
                    INSERT INTO event_approvals (evento_id, aprovado_por, status, justificativa, created_at)
                    VALUES (:e, :u, :s, :j, NOW())
                ");
                $log->execute([
                    'e' => $id,
                    'u' => $_SESSION['user_id'],
                    's' => in_array($novoStatus, ['APROVADO','REJEITADO']) ? $novoStatus : 'REJEITADO',
                    'j' => $observacao
                ]);
            }

            }

            $pdo->commit();
            header("Location: /eventos/ver?id=".$id);
            exit;
        } catch (\Throwable $t) {
            $pdo->rollBack();
            http_response_code(500);
            echo "Falha ao alterar status.";
        }
    }
}