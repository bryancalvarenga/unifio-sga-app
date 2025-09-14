<?php 
$title = "Eventos - Sistema de Atléticas"; 
ob_start(); 
?>

    <h1>Lista de Eventos</h1>

    <a href="/eventos/novo" class="btn btn-success mb-3">Novo Evento</a>
    <br><br>

    <?php if (!empty($eventos)): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
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
                        <a href="/eventos/editar?id=<?= $evento['id'] ?>" class="btn btn-warning btn-sm">Editar</a>


                        <form action="/eventos/excluir" method="POST" class="d-inline">>
                            <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Tem certeza que deseja excluir este evento?')">Excluir</button>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info"">Nenhum evento cadastrado ainda.</div>
    <?php endif; ?>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>