<?php 
$title = "Detalhes do Evento - Sistema de Atléticas"; 
ob_start(); 
?>

<h1 class="mb-4">Detalhes do Evento</h1>

<?php if (!empty($evento)): ?>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= htmlspecialchars($evento['categoria']) ?> 
                <small class="text-light">(#<?= $evento['id'] ?>)</small>
            </h4>
        </div>
        <div class="card-body">
            <p><strong>Data:</strong> <?= htmlspecialchars($evento['data_evento']) ?></p>
            <p><strong>Período:</strong> <?= htmlspecialchars($evento['periodo']) ?></p>
            <p><strong>Finalidade:</strong> <?= htmlspecialchars($evento['finalidade'] ?? '-') ?></p>
            <p><strong>Subtipo Esportivo:</strong> <?= htmlspecialchars($evento['subtipo_esportivo'] ?? '-') ?></p>
            <p><strong>Subtipo Não Esportivo:</strong> <?= htmlspecialchars($evento['subtipo_nao_esportivo'] ?? '-') ?></p>
            <p><strong>Estimativa de participantes:</strong> <?= htmlspecialchars($evento['estimativa_participantes'] ?? '-') ?></p>
            <p><strong>Materiais necessários:</strong> <?= htmlspecialchars($evento['materiais_necessarios'] ?? '-') ?></p>
            <p><strong>Responsável:</strong> <?= htmlspecialchars($evento['responsavel'] ?? '-') ?></p>
            <p><strong>Árbitro:</strong> <?= htmlspecialchars($evento['arbitro'] ?? '-') ?></p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    <?= $evento['status'] === 'APROVADO' ? 'bg-success' : 
                       ($evento['status'] === 'REJEITADO' ? 'bg-danger' : 
                       ($evento['status'] === 'CANCELADO' ? 'bg-warning text-dark' : 'bg-secondary')) ?>">
                    <?= htmlspecialchars($evento['status']) ?>
                </span>
            </p>
            <p><strong>Observações:</strong> <?= nl2br(htmlspecialchars($evento['observacoes'] ?? '-')) ?></p>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="/eventos" class="btn btn-secondary">↩️ Voltar</a>

            <?php if ($podeEditar): ?>
                <a href="/eventos/editar?id=<?= $evento['id'] ?>" class="btn btn-info">✏️ Editar</a>
                <form action="/eventos/excluir" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Tem certeza que deseja excluir este evento?')">
                        🗑 Excluir
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($usuarioPodeParticipar): ?>
                <?php if ($inscrito): ?>
                    <a href="/eventos/cancelar?id=<?= $evento['id'] ?>" class="btn btn-warning">❌ Cancelar Participação</a>
                <?php else: ?>
                    <a href="/eventos/participar?id=<?= $evento['id'] ?>" class="btn btn-success">✅ Participar</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if (($_SESSION['tipo_participacao'] ?? null) === 'COORDENACAO'): ?>
        <form method="POST" action="/eventos/alterar-status" class="mt-3">
        <input type="hidden" name="id" value="<?= htmlspecialchars($evento['id']) ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="AGENDADO"  <?= $evento['status']==='AGENDADO'?'selected':''; ?>>Agendado</option>
                <option value="APROVADO"  <?= $evento['status']==='APROVADO'?'selected':''; ?>>Aprovado</option>
                <option value="REJEITADO" <?= $evento['status']==='REJEITADO'?'selected':''; ?>>Rejeitado</option>
                <option value="CANCELADO" <?= $evento['status']==='CANCELADO'?'selected':''; ?>>Cancelado</option>
                <option value="FINALIZADO"<?= $evento['status']==='FINALIZADO'?'selected':''; ?>>Finalizado</option>
            </select>
            </div>
            <div class="col-md-6">
            <label class="form-label">Observação</label>
            <input type="text" name="observacao" class="form-control" placeholder="Comentário opcional">
            </div>
            <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Atualizar</button>
            </div>
        </div>
        </form>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="alert alert-danger">Evento não encontrado.</div>
    <a href="/eventos" class="btn btn-secondary">↩️ Voltar</a>
<?php endif; ?>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>