<?php 
$title = "Criar Evento Não Esportivo - Sistema de Atléticas"; 
ob_start(); 
?>

<h1 class="mb-4">Criar Novo Evento Não Esportivo</h1>

<form method="POST" action="/eventos/nao-esportivo/criar" class="row g-3">

    <input type="hidden" name="categoria" value="NAO_ESPORTIVO">

    <div class="col-md-6">
        <label class="form-label">Subtipo Não Esportivo</label>
        <select name="subtipo_nao_esportivo" class="form-select" required>
            <option value="">-- Selecione --</option>
            <option value="PALESTRA">Palestra</option>
            <option value="WORKSHOP">Workshop</option>
            <option value="FORMATURA">Formatura</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Finalidade</label>
        <select name="finalidade" class="form-select">
            <option value="OUTRO">Outro</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Data</label>
        <input type="date" name="data_evento" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Período</label>
        <select name="periodo" class="form-select" required>
            <option value="P1">P1 (19:15 - 20:55)</option>
            <option value="P2">P2 (21:10 - 22:50)</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Aberto ao público</label><br>
        <input type="hidden" name="aberto_ao_publico" value="0">
        <input type="checkbox" name="aberto_ao_publico" value="1"> Sim
    </div>

    <div class="col-md-6">
        <label class="form-label">Estimativa de participantes</label>
        <input type="number" name="estimativa_participantes" class="form-control">
    </div>

    <div class="col-12">
        <label class="form-label">Materiais necessários</label>
        <textarea name="materiais_necessarios" class="form-control"></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Usa materiais da instituição</label><br>
        <input type="hidden" name="usa_materiais_instituicao" value="0">
        <input type="checkbox" name="usa_materiais_instituicao" value="1"> Sim
    </div>

    <div class="col-md-6">
        <label class="form-label">Responsável</label>
        <input type="text" name="responsavel" class="form-control" required>
    </div>

    <div class="col-12">
        <label class="form-label">Observações</label>
        <textarea name="observacoes" class="form-control"></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">💾 Salvar</button>
        <a href="/eventos" class="btn btn-secondary">↩️ Voltar</a>
    </div>
</form>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
