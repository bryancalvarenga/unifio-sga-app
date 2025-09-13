<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento - Sistema de Atléticas</title>
</head>
<body>
    <h1>Criar Novo Evento</h1>

    <form method="POST" action="/eventos/criar">
        <label>Categoria:</label>
        <select name="categoria" required>
            <option value="ESPORTIVO">Esportivo</option>
            <option value="NAO_ESPORTIVO">Não Esportivo</option>
        </select>
        <br><br>

        <label>Subtipo Esportivo:</label>
        <select name="subtipo_esportivo">
            <option value="">-- Se aplicável --</option>
            <option value="FUTSAL">Futsal</option>
            <option value="VOLEI">Vôlei</option>
            <option value="BASQUETE">Basquete</option>
        </select>
        <br><br>

        <label>Subtipo Não Esportivo:</label>
        <select name="subtipo_nao_esportivo">
            <option value="">-- Se aplicável --</option>
            <option value="PALESTRA">Palestra</option>
            <option value="WORKSHOP">Workshop</option>
            <option value="FORMATURA">Formatura</option>
        </select>
        <br><br>

        <label>Finalidade:</label>
        <select name="finalidade">
            <option value="TREINO">Treino</option>
            <option value="CAMPEONATO">Campeonato</option>
            <option value="OUTRO">Outro</option>
        </select>
        <br><br>

        <label>Data:</label>
        <input type="date" name="data_evento" required>
        <br><br>

        <label>Período:</label>
        <select name="periodo" required>
            <option value="P1">P1 (19:15 - 20:55)</option>
            <option value="P2">P2 (21:10 - 22:50)</option>
        </select>
        <br><br>

        <label>Aberto ao público:</label>
        <input type="checkbox" name="aberto_ao_publico" value="1">
        <br><br>

        <label>Estimativa de participantes:</label>
        <input type="number" name="estimativa_participantes">
        <br><br>

        <label>Materiais necessários:</label>
        <textarea name="materiais_necessarios"></textarea>
        <br><br>

        <label>Usa materiais da instituição:</label>
        <input type="checkbox" name="usa_materiais_instituicao" value="1">
        <br><br>

        <label>Responsável:</label>
        <input type="text" name="responsavel" required>
        <br><br>

        <label>Árbitro:</label>
        <input type="text" name="arbitro">
        <br><br>

        <label>Observações:</label>
        <textarea name="observacoes"></textarea>
        <br><br>

        <button type="submit">Salvar</button>
    </form>

    <p><a href="/eventos">Voltar</a></p>
</body>
</html>