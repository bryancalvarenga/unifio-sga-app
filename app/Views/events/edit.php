<?php 
$title = "Editar Evento - Sistema de Atléticas"; 
ob_start(); 
?>

<h1 class="mb-4">Editar Evento</h1>

<?php if ($evento): ?>
<form method="POST" action="/eventos/atualizar" class="row g-3">
    <input type="hidden" name="id" value="<?= htmlspecialchars($evento['id']) ?>">

    <div class="col-md-6">
        <label class="form-label">Categoria</label>
        <select name="categoria" class="form-select" required>
            <option value="ESPORTIVO" <?= $evento['categoria'] === 'ESPORTIVO' ? 'selected' : '' ?>>Esportivo</option>
            <option value="NAO_ESPORTIVO" <?= $evento['categoria'] === 'NAO_ESPORTIVO' ? 'selected' : '' ?>>Não Esportivo</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Subtipo Esportivo</label>
        <select name="subtipo_esportivo" class="form-select">
            <option value="">-- Se aplicável --</option>
            <option value="FUTSAL"   <?= $evento['subtipo_esportivo'] === 'FUTSAL' ? 'selected' : '' ?>>Futsal</option>
            <option value="VOLEI"    <?= $evento['subtipo_esportivo'] === 'VOLEI' ? 'selected' : '' ?>>Vôlei</option>
            <option value="BASQUETE" <?= $evento['subtipo_esportivo'] === 'BASQUETE' ? 'selected' : '' ?>>Basquete</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Subtipo Não Esportivo</label>
        <select name="subtipo_nao_esportivo" class="form-select">
            <option value="">-- Se aplicável --</option>
            <option value="PALESTRA"  <?= $evento['subtipo_nao_esportivo'] === 'PALESTRA' ? 'selected' : '' ?>>Palestra</option>
            <option value="WORKSHOP"  <?= $evento['subtipo_nao_esportivo'] === 'WORKSHOP' ? 'selected' : '' ?>>Workshop</option>
            <option value="FORMATURA" <?= $evento['subtipo_nao_esportivo'] === 'FORMATURA' ? 'selected' : '' ?>>Formatura</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Finalidade</label>
        <select name="finalidade" class="form-select">
            <option value="TREINO"      <?= $evento['finalidade'] === 'TREINO' ? 'selected' : '' ?>>Treino</option>
            <option value="CAMPEONATO"  <?= $evento['finalidade'] === 'CAMPEONATO' ? 'selected' : '' ?>>Campeonato</option>
            <option value="OUTRO"       <?= $evento['finalidade'] === 'OUTRO' ? 'selected' : '' ?>>Outro</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Data</label>
        <input type="date" name="data_evento" value="<?= htmlspecialchars($evento['data_evento']) ?>" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Período</label>
        <select name="periodo" class="form-select" required>
            <option value="P1" <?= $evento['periodo'] === 'P1' ? 'selected' : '' ?>>P1 (19:15 - 20:55)</option>
            <option value="P2" <?= $evento['periodo'] === 'P2' ? 'selected' : '' ?>>P2 (21:10 - 22:50)</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Aberto ao público</label><br>
        <input type="hidden" name="aberto_ao_publico" value="0">
        <input type="checkbox" name="aberto_ao_publico" value="1" 
               <?= !empty($evento['aberto_ao_publico']) ? 'checked' : '' ?>> Sim
    </div>

    <div class="col-md-6">
        <label class="form-label">Estimativa de participantes</label>
        <input type="number" name="estimativa_participantes" 
               value="<?= htmlspecialchars($evento['estimativa_participantes'] ?? '') ?>" class="form-control">
    </div>

    <div class="col-12">
        <label class="form-label">Materiais necessários</label>
        <textarea name="materiais_necessarios" class="form-control"><?= htmlspecialchars($evento['materiais_necessarios'] ?? '') ?></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Usa materiais da instituição</label><br>
        <input type="hidden" name="usa_materiais_instituicao" value="0">
        <input type="checkbox" name="usa_materiais_instituicao" value="1" 
               <?= !empty($evento['usa_materiais_instituicao']) ? 'checked' : '' ?>> Sim
    </div>

    <div class="col-md-6">
        <label class="form-label">Responsável</label>
        <input type="text" name="responsavel" value="<?= htmlspecialchars($evento['responsavel']) ?>" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Árbitro</label>
        <input type="text" name="arbitro" value="<?= htmlspecialchars($evento['arbitro'] ?? '') ?>" class="form-control">
    </div>

    <?php if (isset($_SESSION['tipo_participacao']) && $_SESSION['tipo_participacao'] === 'COORDENACAO'): ?>
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="PENDENTE"  <?= $evento['status'] === 'PENDENTE' ? 'selected' : '' ?>>Pendente</option>
                <option value="AGENDADO"  <?= $evento['status'] === 'AGENDADO' ? 'selected' : '' ?>>Agendado</option>
                <option value="APROVADO"  <?= $evento['status'] === 'APROVADO' ? 'selected' : '' ?>>Aprovado</option>
                <option value="REJEITADO" <?= $evento['status'] === 'REJEITADO' ? 'selected' : '' ?>>Rejeitado</option>
                <option value="CANCELADO" <?= $evento['status'] === 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
                <option value="FINALIZADO"<?= $evento['status'] === 'FINALIZADO' ? 'selected' : '' ?>>Finalizado</option>
            </select>
        </div>
    <?php endif; ?>


    <div class="col-12">
        <label class="form-label">Observações</label>
        <textarea name="observacoes" class="form-control"><?= htmlspecialchars($evento['observacoes'] ?? '') ?></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">💾 Salvar alterações</button>
        <a href="/eventos" class="btn btn-secondary">↩️ Voltar</a>
    </div>
</form>
<?php else: ?>
    <div class="alert alert-danger">Evento não encontrado.</div>
    <a href="/eventos" class="btn btn-secondary">↩️ Voltar</a>
<?php endif; ?>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>