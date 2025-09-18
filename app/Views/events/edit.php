<?php
use Core\Database;

// aqui estava $_SESSION['user']['role'] === 'COORDENADOR'
$isCoordinator = ($_SESSION['tipo_participacao'] ?? '') === 'COORDENACAO';

$title = $isCoordinator 
    ? "Analisar Solicitação - Sistema de Atléticas" 
    : "Editar Evento - Sistema de Atléticas";

ob_start();

/* Carrega evento */
$eventId = $_GET['id'] ?? null;
$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
  echo "<div class='alert alert-danger'>Evento não encontrado</div>";
  return;
}
?>
<div class="container-xxl py-3">

  <!-- Cabeçalho -->
  <div class="align-items-center gap-2 mb-3">
    <div>
      <h2 class="d-flex fw-bold align-items-center">
        <i id="home-icons" class="me-2" data-lucide="<?= $isCoordinator ? 'search' : 'pencil' ?>"></i>
        <?= $isCoordinator ? "Analisar Solicitação" : "Editar Evento" ?>
      </h2>
      <p class="text-muted mb-0">
        <?= $isCoordinator 
            ? "Revise as informações do evento e defina o status da solicitação." 
            : "Modifique as informações do seu evento. Alterações em eventos já aprovados podem requerer nova aprovação." ?>
      </p>
    </div>
  </div>

  <!-- ALERTA para usuários normais -->
  <?php if (!$isCoordinator): ?>
    <div class="alert alert-warning small">
      <i class="bi bi-exclamation-triangle"></i>
      Atenção: alterações em eventos aprovados podem precisar de nova aprovação da coordenação.
    </div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- CALENDÁRIO -->
    <div class="col-12 col-lg-6">
      <?php
      // abrir no mês do evento ou no mês da navegação (?mes=)
      $mesParam = $_GET['mes'] ?? substr($event['data_evento'], 0, 7);

      // manter navegação dentro da tela de edição
      $basePath = "/eventos/editar?id=" . urlencode($event['id']);

      include VIEW_PATH . "/partials/calendar.php";
      ?>
    </div>

    <!-- FORM DETALHES -->
    <div class="col-12 col-lg-6">
      <form method="POST" action="/eventos/atualizar" class="row g-3">
        <input type="hidden" name="id" value="<?= $event['id'] ?>">
        <input type="hidden" name="categoria" value="<?= $event['categoria'] ?>">
        <input type="hidden" name="data_evento" id="data_evento" value="<?= $event['data_evento'] ?>">
        <input type="hidden" name="periodo" id="periodo" value="<?= $event['periodo'] ?>">

        <!-- Tipo / Esporte -->
        <div class="col-md-6">
          <label class="form-label">Tipo *</label>
          <select name="finalidade" class="form-select" required>
            <option value="TREINO" <?= $event['finalidade']==='TREINO'?'selected':'' ?>>Treino</option>
            <option value="CAMPEONATO" <?= $event['finalidade']==='CAMPEONATO'?'selected':'' ?>>Campeonato</option>
            <option value="OUTRO" <?= $event['finalidade']==='OUTRO'?'selected':'' ?>>Outro</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Esporte *</label>
          <select name="subtipo_esportivo" class="form-select" required>
            <option value="FUTSAL" <?= $event['subtipo_esportivo']==='FUTSAL'?'selected':'' ?>>Futsal</option>
            <option value="VOLEI" <?= $event['subtipo_esportivo']==='VOLEI'?'selected':'' ?>>Vôlei</option>
            <option value="BASQUETE" <?= $event['subtipo_esportivo']==='BASQUETE'?'selected':'' ?>>Basquete</option>
          </select>
        </div>

        <!-- Materiais -->
        <div class="col-12">
          <label class="form-label">Materiais esportivos</label>
          <div class="row row-cols-2 g-2">
            <?php foreach (['Bolas de Futsal','Coletes','Rede de Vôlei','Cronômetro','Bolas de Vôlei','Cones','Apito'] as $m): ?>
              <div class="col">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="materiais[]" value="<?= $m ?>" 
                         id="mat-<?= md5($m) ?>" <?= str_contains($event['materiais_necessarios'] ?? '', $m)?'checked':'' ?>>
                  <label class="form-check-label" for="mat-<?= md5($m) ?>"><?= $m ?></label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Campos básicos -->
        <div class="col-md-6">
          <label class="form-label">Aberto ao público</label><br>
          <input type="hidden" name="aberto_ao_publico" value="0">
          <div class="form-check form-switch d-inline-block">
            <input class="form-check-input" type="checkbox" name="aberto_ao_publico" value="1" <?= $event['aberto_ao_publico']?'checked':'' ?>>
            <label class="form-check-label">Sim</label>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Estimativa de participantes</label>
          <input type="number" name="estimativa_participantes" class="form-control" value="<?= $event['estimativa_participantes'] ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Responsável *</label>
          <input type="text" name="responsavel" class="form-control" value="<?= $event['responsavel'] ?>" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Árbitro (opcional)</label>
          <input type="text" name="arbitro" class="form-control" value="<?= $event['arbitro'] ?>">
        </div>

        <div class="col-12">
          <label class="form-label">Observações</label>
          <textarea name="observacoes" class="form-control" rows="3"><?= $event['observacoes'] ?></textarea>
        </div>

        <!-- STATUS -->
        <?php if ($isCoordinator): ?>
          <div class="col-12">
            <label class="form-label">Status *</label>
            <select name="status" class="form-select" required>
              <option value="PENDENTE" <?= $event['status']==='PENDENTE'?'selected':'' ?>>Pendente</option>
              <option value="APROVADO" <?= $event['status']==='APROVADO'?'selected':'' ?>>Aprovado</option>
              <option value="REJEITADO" <?= $event['status']==='REJEITADO'?'selected':'' ?>>Rejeitado</option>
            </select>
          </div>
        <?php else: ?>
          <input type="hidden" name="status" value="<?= $event['status'] ?>">
        <?php endif; ?>

        <!-- BOTÕES -->
        <div class="col-12 d-flex justify-content-between align-items-center">
          <a href="/eventos" class="btn btn-light">Cancelar</a>
          <button type="submit" class="btn <?= $isCoordinator ? 'btn-danger' : 'btn-primary' ?>">
            <?= $isCoordinator ? 'Salvar Análise' : 'Salvar Alterações' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include VIEW_PATH . "/layout.php";
