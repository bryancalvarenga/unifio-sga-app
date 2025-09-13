<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - Sistema de Atléticas</title>
</head>
<body>
    <h1>Editar Evento</h1>

    <?php if ($evento): ?>
    <form method="POST" action="/eventos/atualizar">
        <input type="hidden" name="id" value="<?= htmlspecialchars($evento['id']) ?>">

        <label>Categoria:</label>
        <select name="categoria" required>
            <option value="ESPORTIVO" <?= $evento['categoria'] === 'ESPORTIVO' ? 'selected' : '' ?>>Esportivo</option>
            <option value="NAO_ESPORTIVO" <?= $evento['categoria'] === 'NAO_ESPORTIVO' ? 'selected' : '' ?>>Não Esportivo</option>
        </select>
        <br><br>

        <label>Subtipo Esportivo:</label>
        <select name="subtipo_esportivo">
            <option value="">-- Se aplicável --</option>
            <option value="FUTSAL"   <?= $evento['subtipo_esportivo'] === 'FUTSAL' ? 'selected' : '' ?>>Futsal</option>
            <option value="VOLEI"    <?= $evento['subtipo_esportivo'] === 'VOLEI' ? 'selected' : '' ?>>Vôlei</option>
            <option value="BASQUETE" <?= $evento['subtipo_esportivo'] === 'BASQUETE' ? 'selected' : '' ?>>Basquete</option>
        </select>
        <br><br>

        <label>Subtipo Não Esportivo:</label>
        <select name="subtipo_nao_esportivo">
            <option value="">-- Se aplicável --</option>
            <option value="PALESTRA"  <?= $evento['subtipo_nao_esportivo'] === 'PALESTRA' ? 'selected' : '' ?>>Palestra</option>
            <option value="WORKSHOP"  <?= $evento['subtipo_nao_esportivo'] === 'WORKSHOP' ? 'selected' : '' ?>>Workshop</option>
            <option value="FORMATURA" <?= $evento['subtipo_nao_esportivo'] === 'FORMATURA' ? 'selected' : '' ?>>Formatura</option>
        </select>
        <br><br>

        <label>Finalidade:</label>
        <select name="finalidade">
            <option value="TREINO"      <?= $evento['finalidade'] === 'TREINO' ? 'selected' : '' ?>>Treino</option>
            <option value="CAMPEONATO"  <?= $evento['finalidade'] === 'CAMPEONATO' ? 'selected' : '' ?>>Campeonato</option>
            <option value="OUTRO"       <?= $evento['finalidade'] === 'OUTRO' ? 'selected' : '' ?>>Outro</option>
        </select>
        <br><br>

        <label>Data:</label>
        <input type="date" name="data_evento" value="<?= htmlspecialchars($evento['data_evento']) ?>" required>
        <br><br>

        <label>Período:</label>
        <select name="periodo" required>
            <option value="P1" <?= $evento['periodo'] === 'P1' ? 'selected' : '' ?>>P1 (19:15 - 20:55)</option>
            <option value="P2" <?= $evento['periodo'] === 'P2' ? 'selected' : '' ?>>P2 (21:10 - 22:50)</option>
        </select>
        <br><br>

        <label>Aberto ao público:</label>
        <!-- Checkbox envia 0 se desmarcada -->
        <input type="hidden" name="aberto_ao_publico" value="0">
        <input type="checkbox" name="aberto_ao_publico" value="1" <?= !empty($evento['aberto_ao_publico']) ? 'checked' : '' ?>>
        <br><br>

        <label>Estimativa de participantes:</label>
        <input type="number" name="estimativa_participantes" value="<?= htmlspecialchars($evento['estimativa_participantes'] ?? '') ?>">
        <br><br>

        <label>Materiais necessários:</label>
        <textarea name="materiais_necessarios"><?= htmlspecialchars($evento['materiais_necessarios'] ?? '') ?></textarea>
        <br><br>

        <label>Usa materiais da instituição:</label>
        <input type="hidden" name="usa_materiais_instituicao" value="0">
        <input type="checkbox" name="usa_materiais_instituicao" value="1" <?= !empty($evento['usa_materiais_instituicao']) ? 'checked' : '' ?>>
        <br><br>

        <label>Responsável:</label>
        <input type="text" name="responsavel" value="<?= htmlspecialchars($evento['responsavel']) ?>" required>
        <br><br>

        <label>Árbitro:</label>
        <input type="text" name="arbitro" value="<?= htmlspecialchars($evento['arbitro'] ?? '') ?>">
        <br><br>

        <label>Status:</label>
        <select name="status">
            <option value="AGENDADO"  <?= $evento['status'] === 'AGENDADO' ? 'selected' : '' ?>>Agendado</option>
            <option value="APROVADO"  <?= $evento['status'] === 'APROVADO' ? 'selected' : '' ?>>Aprovado</option>
            <option value="REJEITADO" <?= $evento['status'] === 'REJEITADO' ? 'selected' : '' ?>>Rejeitado</option>
            <option value="CANCELADO" <?= $evento['status'] === 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
            <option value="FINALIZADO"<?= $evento['status'] === 'FINALIZADO' ? 'selected' : '' ?>>Finalizado</option>
        </select>
        <br><br>

        <label>Observações:</label>
        <textarea name="observacoes"><?= htmlspecialchars($evento['observacoes'] ?? '') ?></textarea>
        <br><br>

        <button type="submit">Salvar alterações</button>
    </form>
    <?php else: ?>
        <p>Evento não encontrado.</p>
    <?php endif; ?>

    <p><a href="/eventos">Voltar</a></p>
</body>
</html>