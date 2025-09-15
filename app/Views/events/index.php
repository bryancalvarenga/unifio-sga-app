<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tipo = $tipo ?? ($_SESSION['tipo_participacao'] ?? null);
$usuarioId = $usuarioId ?? ($_SESSION['user_id'] ?? 0);

$tipo = $_SESSION['tipo_participacao'] ?? null;
$title = "Meus Eventos - Sistema de Atléticas"; 
ob_start(); 

$usuarioId = $_SESSION['user_id'] ?? 0;

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
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><i class="bi bi-calendar-event"></i> Meus Eventos</h1>
  <div>
    <?php if ($tipo === 'ATLETICA'): ?>
      <a href="/eventos/esportivo/novo" class="btn btn-primary">+ Evento Esportivo</a>
    <?php elseif ($tipo === 'PROFESSOR'): ?>
      <a href="/eventos/nao-esportivo/novo" class="btn btn-warning text-white">+ Evento Não Esportivo</a>
    <?php endif; ?>
  </div>
</div>

<p class="text-muted">Gerencie seus agendamentos passados e futuros</p>

<ul class="nav nav-tabs mb-3">
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
        ?>
        <div class="card mb-3 shadow-sm">
          <div class="card-body">
            <h5 class="mb-2"><?= htmlspecialchars($evento['finalidade'] ?? $evento['categoria']) ?></h5>
            <p class="mb-1"><i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($evento['data_evento'])) ?></p>
            <p class="mb-1"><i class="bi bi-clock"></i> <?= htmlspecialchars($evento['periodo']) ?></p>
            <p class="mb-1"><i class="bi bi-person"></i> <?= htmlspecialchars($evento['responsavel'] ?? '-') ?></p>
            <span class="badge 
              <?= $evento['status'] === 'APROVADO' ? 'bg-success' : 
                 ($evento['status'] === 'REJEITADO' ? 'bg-danger' : 
                 ($evento['status'] === 'CANCELADO' ? 'bg-warning text-dark' : 'bg-secondary')) ?>">
              <?= htmlspecialchars($evento['status']) ?>
            </span>
          </div>
          <div class="card-footer d-flex gap-2">
            <button 
              type="button" 
              class="btn btn-sm btn-outline-primary ver-evento" 
              data-id="<?= $evento['id'] ?>" 
              data-bs-toggle="modal" 
              data-bs-target="#modalEvento">
              👁 Ver
            </button>

            <?php if ($podeEditarExcluir && $tipo !== 'COORDENACAO'): ?>
              <a href="/eventos/editar?id=<?= $evento['id'] ?>" class="btn btn-sm btn-info">✏ Editar</a>
              <form action="/eventos/excluir" method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir este evento?')">🗑 Excluir</button>
              </form>
            <?php endif; ?>

            <?php if ($tipo === 'COORDENACAO'): ?>
              <a href="/eventos/editar?id=<?= $evento['id'] ?>" class="btn btn-sm btn-warning">
                📋 Analisar Solicitação
              </a>
            <?php endif; ?>
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
        <div class="card mb-3 shadow-sm">
          <div class="card-body">
            <h5><?= htmlspecialchars($evento['finalidade'] ?? $evento['categoria']) ?></h5>
            <p class="mb-1"><i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($evento['data_evento'])) ?></p>
            <p class="mb-1"><i class="bi bi-clock"></i> <?= htmlspecialchars($evento['periodo']) ?></p>
            <span class="badge bg-secondary"><?= htmlspecialchars($evento['status']) ?></span>
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

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
