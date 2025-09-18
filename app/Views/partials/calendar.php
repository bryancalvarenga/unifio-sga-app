<?php
use Core\Database;

/**
 * ================================
 * CALENDÁRIO DE EVENTOS
 * ================================
 * Ajustes feitos:
 * - Usa timezone correta (São Paulo)
 * - Mês inicial vem de:
 *     1) $mesParam definido pela view (ex: edit.php passa mês do evento)
 *     2) $_GET['mes'] se existir
 *     3) mês atual (date('Y-m'))
 * - BasePath pode ser definido pela view para manter navegação na rota atual
 * - Links de navegação mantêm querystring correta (? ou &)
 * - Âncora #cal evita a página "subir pro topo"
 */

date_default_timezone_set('America/Sao_Paulo');

/* BasePath = rota para navegação do calendário */
$basePath = $basePath ?? (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

/* Definição do mês exibido */
$mesParam = $mesParam ?? ($_GET['mes'] ?? date('Y-m'));
try {
    $inicio = new DateTime($mesParam . '-01', new DateTimeZone('America/Sao_Paulo'));
} catch (\Throwable $e) {
    $inicio = new DateTime('first day of this month', new DateTimeZone('America/Sao_Paulo'));
}

/* Datas limites */
$fim        = (clone $inicio)->modify('last day of this month');
$diasNoMes  = (int)$inicio->format('t');
$primeiroW  = (int)(new DateTime($inicio->format('Y-m-01')))->format('w'); // 0=Domingo

/* Buscar eventos do mês */
$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT data_evento, periodo, status 
                       FROM events 
                       WHERE data_evento BETWEEN :ini AND :fim");
$stmt->execute([
    'ini' => $inicio->format('Y-m-d'),
    'fim' => $fim->format('Y-m-d')
]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Mapa de ocupação */
$ocupado = [];
foreach ($rows as $r) {
    $st = $r['status'] ?? 'AGENDADO';
    if ($st !== 'CANCELADO' && $st !== 'REJEITADO') {
        $ocupado[$r['data_evento']][$r['periodo']] = true;
    }
}

/* Helpers */
function slotClassCal(bool $busy) { return $busy ? 'btn-outline-secondary disabled' : 'btn-success'; }
function dayBadgeCal($ymd, $ocupado) {
    $p1 = !empty($ocupado[$ymd]['P1']);
    $p2 = !empty($ocupado[$ymd]['P2']);
    if (!$p1 && !$p2) return 'bg-success';
    if ($p1 xor $p2) return 'bg-warning text-dark';
    return 'bg-danger';
}

/* Navegação (prev/next) */
$prevMes = (clone $inicio)->modify('-1 month')->format('Y-m');
$nextMes = (clone $inicio)->modify('+1 month')->format('Y-m');
$sep     = str_contains($basePath, '?') ? '&' : '?';
?>

<h5 class="mb-3">Selecione Data e Horário</h5>

<!-- Legenda -->
<div class="d-flex align-items-center gap-3 small mb-2">
  <span><span class="badge bg-success me-1">&nbsp;</span> Livre</span>
  <span><span class="badge bg-warning text-dark me-1">&nbsp;</span> Meio período ocupado</span>
  <span><span class="badge bg-danger me-1">&nbsp;</span> Indisponível</span>
</div>

<!-- Calendário -->
<div id="cal" class="border rounded-3 p-3">
  <!-- Cabeçalho mês -->
  <div class="d-flex justify-content-between align-items-center mb-2">
    <a class="btn btn-sm btn-outline-secondary"
       href="<?= $basePath . $sep . 'mes=' . $prevMes ?>#cal">
       <i class="bi bi-chevron-left"></i>
    </a>

    <div class="fw-semibold">
      <?php
        $fmt = new \IntlDateFormatter(
            'pt_BR', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE,
            'America/Sao_Paulo', \IntlDateFormatter::GREGORIAN, "LLLL 'de' y"
        );
        echo ucfirst($fmt->format($inicio));
      ?>
    </div>

    <a class="btn btn-sm btn-outline-secondary"
       href="<?= $basePath . $sep . 'mes=' . $nextMes ?>#cal">
       <i class="bi bi-chevron-right"></i>
    </a>
  </div>

  <!-- Cabeçalho semana -->
  <div class="calendar-grid text-center fw-semibold text-muted mb-2">
    <?php foreach (['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'] as $d): ?>
      <div><?= $d ?></div>
    <?php endforeach; ?>
  </div>

  <!-- Grade -->
  <div class="calendar-grid">
    <?php for ($i=0; $i<$primeiroW; $i++): ?>
      <div class="calendar-cell"></div>
    <?php endfor; ?>

    <?php for ($dia=1; $dia<=$diasNoMes; $dia++):
      $ymd    = $inicio->format('Y-m') . '-' . str_pad($dia, 2, '0', STR_PAD_LEFT);
      $badge  = dayBadgeCal($ymd, $ocupado);
      $p1busy = !empty($ocupado[$ymd]['P1']);
      $p2busy = !empty($ocupado[$ymd]['P2']);
    ?>
      <div class="calendar-cell">
        <div class="dayline">
          <span class="calendar-day"><?= $dia ?></span>
          <span class="badge <?= $badge ?>">&nbsp;</span>
        </div>
        <button type="button" class="btn btn-sm slot <?= slotClassCal($p1busy) ?>"
                data-date="<?= $ymd ?>" data-periodo="P1" <?= $p1busy?'disabled':'' ?>>
          19:15
        </button>
        <button type="button" class="btn btn-sm slot <?= slotClassCal($p2busy) ?>"
                data-date="<?= $ymd ?>" data-periodo="P2" <?= $p2busy?'disabled':'' ?>>
          21:10
        </button>
      </div>
    <?php endfor; ?>
  </div>
</div>
