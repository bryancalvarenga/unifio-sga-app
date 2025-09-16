<?php
use Core\Database;

$title = "Criar Evento Esportivo - Sistema de Atléticas";
ob_start();

/* -------------------- CONFIG / DADOS DO MÊS -------------------- */
$basePath = "/eventos/esportivo/novo";
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
    if (!$p1 && !$p2) return 'bg-success';                // Livre
    if ($p1 xor $p2)   return 'bg-warning text-dark';     // Meio ocupado
    return 'bg-danger';                                   // Indisponível
}
?>
<div class="container-xxl py-3">

  <!-- Cabeçalho -->
  <div class="d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-trophy fs-3 text-primary"></i>
    <h2 class="m-0">Evento Esportivo</h2>
  </div>
  <p class="text-muted">Agende treinos e campeonatos na quadra poliesportiva</p>

  <!-- Regras -->
  <div class="alert alert-light border mb-4">
    <div class="fw-semibold mb-2"><i class="bi bi-info-circle"></i> Regras importantes:</div>
    <ul class="mb-0">
      <li>Cada Atlética pode agendar apenas 1 treino por semana por esporte</li>
      <li>Eventos devem ser agendados com pelo menos 48h de antecedência</li>
      <li>Materiais emprestados devem ser devolvidos no mesmo dia</li>
      <li>Cancelamentos devem ser feitos com 24h de antecedência</li>
    </ul>
  </div>

  <div class="row g-4">
    <!-- CALENDÁRIO -->
    <div class="col-12 col-lg-6">
        <?php 
            $basePath = "/eventos/esportivo/novo";
            include VIEW_PATH . "/partials/calendar.php"; 
        ?>
    </div>

    <!-- FORM DETALHES -->
    <div class="col-12 col-lg-6">
      <h5 class="mb-3">Detalhes do Evento</h5>

      <form method="POST" action="/eventos/esportivo/criar" class="row g-3">
        <input type="hidden" name="categoria" value="ESPORTIVO">
        <input type="hidden" name="data_evento" id="data_evento">
        <input type="hidden" name="periodo" id="periodo">

        <div class="col-md-6">
          <label class="form-label">Finalidade *</label>
          <select name="finalidade" class="form-select" required>
            <option value="TREINO">Treino</option>
            <option value="CAMPEONATO">Campeonato</option>
            <option value="OUTRO">Outro</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Esporte *</label>
          <select name="subtipo_esportivo" class="form-select" required>
            <option value="">Selecione o esporte</option>
            <option value="FUTSAL">Futsal</option>
            <option value="VOLEI">Vôlei</option>
            <option value="BASQUETE">Basquete</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Materiais esportivos</label>
          <div class="row row-cols-2 g-2">
            <?php foreach (['Bolas de Futsal','Coletes','Rede de Vôlei','Cronômetro','Bolas de Vôlei','Cones','Apito'] as $m): ?>
              <div class="col">
                <div class="form-check">
                  <input class="form-check-input materiais-check" type="checkbox" value="<?= $m ?>" id="mat-<?= md5($m) ?>">
                  <label class="form-check-label" for="mat-<?= md5($m) ?>"><?= $m ?></label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <input type="hidden" name="materiais_necessarios" id="materiais_necessarios">
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

        <div class="col-md-6">
          <label class="form-label">Árbitro (opcional)</label>
          <input type="text" name="arbitro" class="form-control">
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
            <button id="btnSalvar" type="submit" class="btn btn-primary" disabled>Agendar Evento</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include VIEW_PATH . "/layout.php";
