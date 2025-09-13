<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Sistema de Atléticas</title>
</head>
<body>
    <h1>Lista de Eventos</h1>

    <a href="/eventos/novo">Novo Evento</a>
    <br><br>

    <?php if (!empty($eventos)): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categoria</th>
                    <th>Data</th>
                    <th>Período</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($eventos as $evento): ?>
                <tr>
                    <td><?= htmlspecialchars($evento['id']) ?></td>
                    <td><?= htmlspecialchars($evento['categoria']) ?></td>
                    <td><?= htmlspecialchars($evento['data_evento']) ?></td>
                    <td><?= htmlspecialchars($evento['periodo']) ?></td>
                    <td><?= htmlspecialchars($evento['status']) ?></td>
                    <td>
                        <a href="/eventos/editar?id=<?= $evento['id'] ?>">Editar</a>


                        <form action="/eventos/excluir" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                            <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este evento?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum evento cadastrado ainda.</p>
    <?php endif; ?>
</body>
</html>