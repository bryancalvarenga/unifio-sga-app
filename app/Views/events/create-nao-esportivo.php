<?php
use Core\Database;

$title = "Criar Evento Não Esportivo - Sistema de Atléticas";
ob_start();

/* -------------------- CONFIG / DADOS DO MÊS -------------------- */
$basePath = "/eventos/nao-esportivo/novo";
$mesParam = $_GET['mes'] ?? date('Y-m');
try {
    $inicio = new DateTime($mesParam . '-01');
} catch (\Throwable $e) {
    $inicio = new DateTime('first day of this month');
}
$fim = (clone $inicio)->modify('last day of this month');
$diasNoMes = (int)$inicio->format('t');

$pdo = Database::getConnection();
$stmt = $pdo->prepare("
  SELECT data_evento, periodo, status
  FROM events
  WHERE data_evento BETWEEN :ini AND :fim
");
$stmt->execute(['ini' => $inicio->format('Y-m-d'), 'fim' => $fim->format('Y-m-d')]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Ocupação */
$ocupado = [];
foreach ($rows as $r) {
    $st = $r['status'] ?? 'AGENDADO';
    if ($st !== 'CANCELADO' && $st !== 'REJEITADO') {
        $d = $r['data_evento'];
        $p = $r['periodo'];
        $ocupado[$d][$p] = true;
    }
}

/* Helpers */
function slotClass(bool $isBusy): string {
    return $isBusy ? 'btn-outline-secondary disabled' : 'btn-success';
}
function dayBadge(string $ymd, array $ocupado): string {
    $p1 = !empty($ocupado[$ymd]['P1']);
    $p2 = !empty($ocupado[$ymd]['P2']);
    if (!$p1 && !$p2) return 'bg-success';
    if ($p1 xor $p2) return 'bg-warning text-dark';
    return 'bg-danger';
}
?>
<div class="container-xxl py-3">

  <!-- Cabeçalho -->
  <div class="align-items-center gap-2 mb-3">
    <h2 class="fw-bold d-flex align-items-center">
      <i data-lucide="presentation" id="home-icons" class="me-2 icon-lg"></i>Novo Evento
    </h2>
    <p class="text-muted">Solicite a quadra para palestras, workshops, formaturas e afins</p>
  </div>

  <div class="row g-4">
    <!-- CALENDÁRIO -->
    <div class="col-12 col-lg-6">
      <?php 
            $basePath = "/eventos/nao-esportivo/novo";
            include VIEW_PATH . "/partials/calendar.php"; 
        ?>
    </div>

    <!-- FORM DETALHES -->
    <div class="col-12 col-lg-6">
      <h5 class="mb-3">Detalhes do Evento</h5>

      <form method="POST" action="/eventos/nao-esportivo/criar" class="row g-3">
        <input type="hidden" name="categoria" value="NAO_ESPORTIVO">
        <input type="hidden" name="data_evento" id="data_evento">
        <input type="hidden" name="periodo" id="periodo">

        <div class="col-md-6">
          <label class="form-label">Finalidade *</label>
          <select name="subtipo_nao_esportivo" class="form-select" required>
            <option value="">Selecione</option>
            <option value="PALESTRA">Palestra</option>
            <option value="WORKSHOP">Workshop</option>
            <option value="FORMATURA">Formatura</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Aberto ao público</label><br>
          <input type="hidden" name="aberto_ao_publico" value="0">
          <div class="form-check form-switch d-inline-block">
            <input class="form-check-input" type="checkbox" name="aberto_ao_publico" value="1" id="pub">
            <label class="form-check-label" for="pub">Sim</label>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Estimativa de participantes</label>
          <input type="number" name="estimativa_participantes" class="form-control" min="0">
        </div>

        <div class="col-12">
          <label class="form-label">Materiais necessários</label>
          <textarea name="materiais_necessarios" class="form-control" rows="2"></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Usa materiais da instituição</label><br>
          <input type="hidden" name="usa_materiais_instituicao" value="0">
          <div class="form-check form-switch d-inline-block">
            <input class="form-check-input" type="checkbox" name="usa_materiais_instituicao" value="1" id="inst">
            <label class="form-check-label" for="inst">Sim</label>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Responsável *</label>
          <input type="text" name="responsavel" class="form-control" required>
        </div>

        <div class="col-12">
          <label class="form-label">Observações (opcional)</label>
          <textarea name="observacoes" class="form-control" rows="3"></textarea>
        </div>

        <div class="col-12 d-flex justify-content-between align-items-center">
          <div class="small text-muted">
            <span class="me-2">Data selecionada:</span>
            <span id="selecionado" class="fw-semibold">—</span>
          </div>
          <div>
            <a href="/eventos" class="btn btn-light">Cancelar</a>
            <button id="btnSalvar" type="submit" class="btn btn-warning text-white" disabled>Agendar Evento</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include VIEW_PATH . "/layout.php";