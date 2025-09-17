<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tipo = $_SESSION['tipo_participacao'] ?? null;
$usuarioId = $_SESSION['user_id'] ?? 0;

$title = "Meus Eventos - Sistema de Atléticas"; 
ob_start(); 

// Separar próximos e passados
$proximos = [];
$passados = [];
$hoje = date('Y-m-d');

foreach ($eventos as $evento) {
    if ($evento['data_evento'] >= $hoje) {
        $proximos[] = $evento;
    } else {
        $passados[] = $evento;
    }
}

// Helpers
$horarios = [
  'P1' => '19:15 - 20:55',
  'P2' => '21:10 - 22:50'
];

$statusMap = [
  'APROVADO'  => 'Agendado',
  'PENDENTE'  => 'Pendente',
  'REJEITADO' => 'Rejeitado',
  'CANCELADO' => 'Cancelado'
];

function formatarData($data) {
    $formatter = new IntlDateFormatter(
        'pt_BR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'America/Sao_Paulo',
        IntlDateFormatter::GREGORIAN
    );
    return ucfirst($formatter->format(new DateTime($data)));
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="mb-1 d-flex align-items-center">
      <i data-lucide="calendar-days" class="me-2"></i> Meus Eventos
    </h1>
    <p class="text-muted mb-0">Gerencie seus agendamentos passados e futuros</p>
  </div>
  <div>
    <?php if ($tipo === 'ATLETICA'): ?>
      <a href="/eventos/esportivo/novo" class="btn btn-primary">
        <i data-lucide="trophy" class="me-1"></i> Evento Esportivo
      </a>
    <?php elseif ($tipo === 'PROFESSOR'): ?>
      <a href="/eventos/nao-esportivo/novo" class="btn btn-warning text-white">
        <i data-lucide="presentation" class="me-1"></i> Evento Não Esportivo
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs modern-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="tab" href="#proximos">
      Próximos Eventos (<?= count($proximos) ?>)
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#passados">
      Eventos Passados (<?= count($passados) ?>)
    </a>
  </li>
</ul>

<div class="tab-content">
  <!-- Próximos -->
  <div class="tab-pane fade show active" id="proximos">
    <?php if ($proximos): ?>
      <?php foreach ($proximos as $evento): ?>
        <?php
          $ehDono = isset($evento['usuario_id']) && ((int)$evento['usuario_id'] === (int)$usuarioId);
          $categoria = strtoupper($evento['categoria'] ?? '');
          $podeEditarExcluir = $ehDono && (
              ($tipo === 'ATLETICA'  && $categoria === 'ESPORTIVO') ||
              ($tipo === 'PROFESSOR' && $categoria === 'NAO_ESPORTIVO')
          );

          $statusClass = match($evento['status']) {
            'APROVADO' => 'status-badge success',
            'REJEITADO' => 'status-badge danger',
            'CANCELADO' => 'status-badge warning',
            default => 'status-badge secondary'
          };

          $horaFormatada = $horarios[$evento['periodo']] ?? $evento['periodo'];
        ?>
        
        <div class="event-card card shadow-sm mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <h5 class="fw-bold mb-1">
                  <i data-lucide="<?= $categoria==='ESPORTIVO'?'trophy':'presentation' ?>" class="me-1"></i>
                  <?= htmlspecialchars($evento['finalidade'] ?? $evento['categoria']) ?>
                </h5>
                <small class="text-muted">Responsável: <?= htmlspecialchars($evento['responsavel'] ?? '-') ?></small>
              </div>
              <span class="<?= $statusClass ?>">
                <?= htmlspecialchars($statusMap[$evento['status']] ?? $evento['status']) ?>
              </span>
            </div>

            <div class="event-details text-muted small mb-3">
              <p><i data-lucide="calendar"></i> <?= formatarData($evento['data_evento']) ?></p>
              <p><i data-lucide="clock-4"></i> <?= $horaFormatada ?></p>
              <p><i data-lucide="map-pin"></i> Quadra Poliesportiva UNIFIO</p>
              <?php if (!empty($evento['estimativa_participantes'])): ?>
                <p><i data-lucide="users"></i> <?= (int)$evento['estimativa_participantes'] ?> participantes</p>
              <?php endif; ?>
            </div>

            <!-- Ações -->
            <div class="d-flex flex-wrap gap-2">
              <button 
                type="button" 
                class="btn-action ver-evento"
                data-id="<?= $evento['id'] ?>" 
                data-bs-toggle="modal" 
                data-bs-target="#modalEvento">
                <i data-lucide="eye"></i> Ver
              </button>

              <?php if ($podeEditarExcluir && $tipo !== 'COORDENACAO'): ?>
                <a href="/eventos/editar?id=<?= $evento['id'] ?>" class="btn-action">
                  <i data-lucide="pencil"></i> Editar
                </a>
              <?php endif; ?>

              <?php if ($ehDono || $tipo === 'COORDENACAO'): ?>
                <form action="/eventos/excluir" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                  <button type="submit" class="btn-action danger"
                          onclick="return confirm('Tem certeza que deseja excluir este evento?')">
                    <i data-lucide="trash-2"></i> Excluir
                  </button>
                </form>
              <?php endif; ?>

              <?php if ($tipo === 'COORDENACAO'): ?>
                <a href="/eventos/editar?id=<?= $evento['id'] ?>" class="btn-action warning">
                  <i data-lucide="check-square"></i> Analisar
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info">Nenhum evento futuro encontrado.</div>
    <?php endif; ?>
  </div>

  <!-- Passados -->
  <div class="tab-pane fade" id="passados">
    <?php if ($passados): ?>
      <?php foreach ($passados as $evento): ?>
        <div class="event-card card mb-3">
          <div class="card-body">
            <h5 class="fw-bold"><?= htmlspecialchars($evento['finalidade'] ?? $evento['categoria']) ?></h5>
            <div class="event-details text-muted small">
              <p><i data-lucide="calendar"></i> <?= formatarData($evento['data_evento']) ?></p>
              <p><i data-lucide="clock-4"></i> <?= $horarios[$evento['periodo']] ?? $evento['periodo'] ?></p>
            </div>
            <span class="status-badge secondary mt-2"><?= htmlspecialchars($evento['status']) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info">Nenhum evento passado.</div>
    <?php endif; ?>
  </div>

</div>


<!-- Modal Detalhes do Evento -->
<div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <p class="text-center text-muted">Carregando...</p>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
