<?php 
$tipo = $_SESSION['tipo_participacao'] ?? null;
$title = "Eventos - Sistema de Atléticas"; 
ob_start(); 
?>

<h1 class="mb-4">Lista de Eventos</h1>

<?php if ($tipo === 'ATLETICA'): ?>
  <a href="/eventos/esportivo/novo" class="btn btn-primary mb-3">➕ Novo Evento Esportivo</a>
<?php elseif ($tipo === 'PROFESSOR'): ?>
  <a href="/eventos/nao-esportivo/novo" class="btn btn-primary mb-3">➕ Novo Evento Não Esportivo</a>
<?php endif; ?>

<?php if (!empty($eventos)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
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
                    <td>
                        <span class="badge 
                            <?= $evento['status'] === 'APROVADO' ? 'bg-success' : 
                               ($evento['status'] === 'REJEITADO' ? 'bg-danger' : 
                               ($evento['status'] === 'CANCELADO' ? 'bg-warning text-dark' : 'bg-secondary')) ?>">
                            <?= htmlspecialchars($evento['status']) ?>
                        </span>
                    </td>
                    <?php
                    $usuarioId = $_SESSION['user_id'] ?? 0;
                    $ehDono    = ((int)$evento['usuario_id'] === (int)$usuarioId);
                    $podeEditarExcluir = $ehDono && (
                            ($tipo === 'ATLETICA'  && $evento['categoria'] === 'ESPORTIVO') ||
                            ($tipo === 'PROFESSOR' && $evento['categoria'] === 'NAO_ESPORTIVO')
                    );
                    ?>
                    <td>
                    <a href="/eventos/ver?id=<?= $evento['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>

                    <?php if ($podeEditarExcluir): ?>
                        <a href="/eventos/editar?id=<?= $evento['id'] ?>" class="btn btn-sm btn-info">Editar</a>
                        <form action="/eventos/excluir" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Tem certeza que deseja excluir este evento?')">
                                Excluir
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php
                        // Participar/Cancelar só para ALUNO/COMUNIDADE e se evento for público
                        $mostrarParticipar = in_array($tipo, ['ALUNO','COMUNIDADE']) && !empty($evento['aberto_ao_publico']);
                        if ($mostrarParticipar) {
                            $pdo = \Core\Database::getConnection();
                            $stmt = $pdo->prepare("SELECT 1 FROM event_participants WHERE evento_id = :evento_id AND usuario_id = :usuario_id");
                            $stmt->execute(['evento_id' => $evento['id'], 'usuario_id' => $usuarioId]);
                            $inscrito = $stmt->fetch();
                        }
                    ?>

                    <?php if ($mostrarParticipar): ?>
                        <?php if ($inscrito): ?>
                            <a href="/eventos/cancelar?id=<?= $evento['id'] ?>" class="btn btn-sm btn-warning">Cancelar</a>
                        <?php else: ?>
                            <a href="/eventos/participar?id=<?= $evento['id'] ?>" class="btn btn-sm btn-success">Participar</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Nenhum evento cadastrado ainda.</div>
<?php endif; ?>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
