<?php
// Formatador de data em pt-BR
$fmt = new \IntlDateFormatter(
    'pt_BR',
    \IntlDateFormatter::FULL,
    \IntlDateFormatter::NONE,
    'America/Sao_Paulo',
    \IntlDateFormatter::GREGORIAN
);
$dataFormatada = ucfirst($fmt->format(new DateTime($evento['data_evento'])));

// Converter período P1/P2 em horário
$horario = match($evento['periodo']) {
    'P1' => '19:15 - 20:55',
    'P2' => '21:10 - 22:50',
    default => $evento['periodo']
};

// Verificar se pode editar
$ehDono = isset($evento['usuario_id']) && ((int)$evento['usuario_id'] === (int)($_SESSION['user_id'] ?? 0));
$categoria = strtoupper($evento['categoria'] ?? '');
$podeEditar = $ehDono || ($tipo === 'COORDENACAO');

// Status estilizado
$statusLabel = match($evento['status']) {
    'APROVADO'   => ['Agendado', 'status-badge success'],
    'PENDENTE'   => ['Pendente', 'status-badge secondary'],
    'REJEITADO'  => ['Rejeitado', 'status-badge danger'],
    'CANCELADO'  => ['Cancelado', 'status-badge warning text-dark'],
    default      => [$evento['status'] ?? '-', 'status-badge secondary']
};
?>

<div class="modal-header border-0">
  <h5 class="modal-title fw-bold d-flex align-items-center">
    <i data-lucide="<?= $evento['categoria'] === 'ESPORTIVO' ? 'trophy' : 'presentation' ?>" 
       class="me-2 text-primary"></i>
    <?= htmlspecialchars($evento['finalidade'] ?? $evento['titulo'] ?? 'Evento') ?>
  </h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>

<div class="modal-body">
  <div class="row g-3">

    <!-- Status -->
    <div class="col-md-6">
      <span class="fw-semibold text-secondary">Status:</span>
      <span class="<?= $statusLabel[1] ?>"><?= $statusLabel[0] ?></span>
    </div>

    <!-- Tipo -->
    <div class="col-md-6">
      <span class="fw-semibold text-secondary">Tipo:</span>
      <?= $evento['categoria'] === 'ESPORTIVO' ? 'Esportivo' : 'Não Esportivo' ?>
    </div>

    <!-- Data -->
    <div class="col-12">
      <span class="fw-semibold text-secondary">Data:</span>
      <?= $dataFormatada ?>
    </div>

    <!-- Horário -->
    <div class="col-12">
      <span class="fw-semibold text-secondary">Horário:</span>
      <?= htmlspecialchars($horario) ?>
    </div>

    <!-- Local -->
    <div class="col-12">
      <span class="fw-semibold text-secondary">Local:</span>
      Quadra Poliesportiva UNIFIO
    </div>

    <!-- Responsável -->
    <div class="col-12">
      <span class="fw-semibold text-secondary">Responsável:</span>
      <?= htmlspecialchars($evento['responsavel'] ?? 'Não informado') ?>
    </div>

    <!-- Subtipo Esportivo -->
    <?php if (!empty($evento['subtipo_esportivo'])): ?>
      <div class="col-12">
        <span class="fw-semibold text-secondary">Esporte:</span>
        <?= htmlspecialchars($evento['subtipo_esportivo']) ?>
      </div>
    <?php endif; ?>

    <!-- Subtipo Não Esportivo -->
    <?php if (!empty($evento['subtipo_nao_esportivo'])): ?>
      <div class="col-12">
        <span class="fw-semibold text-secondary">Categoria:</span>
        <?= htmlspecialchars($evento['subtipo_nao_esportivo']) ?>
      </div>
    <?php endif; ?>

    <!-- Finalidade -->
    <?php if (!empty($evento['finalidade'])): ?>
      <div class="col-12">
        <span class="fw-semibold text-secondary">Finalidade:</span>
        <?= htmlspecialchars($evento['finalidade']) ?>
      </div>
    <?php endif; ?>

    <!-- Participantes -->
    <div class="col-12">
      <span class="fw-semibold text-secondary">Participantes:</span>
      <?= htmlspecialchars($evento['estimativa_participantes'] ?? '0') ?>
    </div>
  </div>
</div>

<?php if ($podeEditar): ?>
  <div class="modal-footer border-0">
    <a href="/eventos/editar?id=<?= $evento['id'] ?>" 
       class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
      <i data-lucide="pencil" id="index-icons"></i> Editar Evento
    </a>
  </div>
<?php endif; ?>
