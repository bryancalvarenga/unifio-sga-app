<div class="modal-header border-0">
  <h5 class="modal-title fw-bold d-flex align-items-center">
    <i class="bi bi-trophy text-primary me-2"></i>
    <?= htmlspecialchars($evento['titulo'] ?? 'Evento') ?>
  </h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>

<div class="modal-body">
  <div class="row g-3">
    <div class="col-6">
      <span class="fw-semibold text-secondary">Status:</span>
      <span class="badge bg-<?= $evento['status'] === 'APROVADO' ? 'success' : ($evento['status'] === 'PENDENTE' ? 'warning text-dark' : 'secondary') ?>">
        <?= htmlspecialchars($evento['status'] ?? '-') ?>
      </span>
    </div>
    <div class="col-6">
      <span class="fw-semibold text-secondary">Tipo:</span>
      <?= $evento['categoria'] === 'ESPORTIVO' ? 'Esportivo' : 'Não Esportivo' ?>
    </div>

    <div class="col-12">
      <span class="fw-semibold text-secondary">Data:</span>
      <?= date('d/m/Y', strtotime($evento['data_evento'])) ?>
    </div>

    <div class="col-12">
      <span class="fw-semibold text-secondary">Horário:</span>
      <?= htmlspecialchars($evento['periodo']) ?>
    </div>

    <div class="col-12">
      <span class="fw-semibold text-secondary">Responsável:</span>
      <?= htmlspecialchars($evento['responsavel'] ?? 'Não informado') ?>
    </div>

    <?php if (!empty($evento['subtipo_esportivo'])): ?>
      <div class="col-12">
        <span class="fw-semibold text-secondary">Esporte:</span>
        <?= htmlspecialchars($evento['subtipo_esportivo']) ?>
      </div>
    <?php endif; ?>

    <div class="col-12">
      <span class="fw-semibold text-secondary">Participantes:</span>
      <?= htmlspecialchars($evento['estimativa_participantes'] ?? '0') ?>
    </div>
  </div>
</div>