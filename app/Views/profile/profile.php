<?php 
$title = "Meu Perfil - Sistema de Atléticas"; 
ob_start(); 
?>

<h1 class="mb-4">Meu Perfil</h1>

<!-- Mensagens de feedback -->
<?php if (!empty($_SESSION['erro_perfil'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['erro_perfil']; unset($_SESSION['erro_perfil']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['sucesso_perfil'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['sucesso_perfil']; unset($_SESSION['sucesso_perfil']); ?>
    </div>
<?php endif; ?>

<?php if (isset($usuario)): ?>
<form method="POST" action="/perfil/atualizar" enctype="multipart/form-data" class="row g-3">

    <!-- Foto de Perfil -->
    <div class="col-12 text-center mb-4">
        <img src="<?= $usuario['foto_perfil'] ?? '/assets/img/default-user.png' ?>" 
             alt="Foto de Perfil" 
             class="rounded-circle mb-3" width="120" height="120">
        <div>
            <label for="foto_perfil" class="form-label">Alterar foto</label>
            <input type="file" name="foto_perfil" id="foto_perfil" class="form-control">
        </div>
    </div>

    <!-- Nome -->
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome completo</label>
        <input type="text" id="nome" name="nome" 
               value="<?= htmlspecialchars($usuario['nome']) ?>" 
               class="form-control" required>
    </div>

    <!-- Email -->
    <div class="col-md-6">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" name="email" 
               value="<?= htmlspecialchars($usuario['email']) ?>" 
               class="form-control" required>
    </div>

    <!-- Telefone -->
    <div class="col-md-6">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" id="telefone" name="telefone" 
               value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>" 
               class="form-control">
    </div>

    <!-- Senha (alteração opcional) -->
    <div class="col-md-6">
        <label for="senha" class="form-label">Senha (deixe em branco para não alterar)</label>
        <input type="password" id="senha" name="senha" class="form-control">
    </div>

    <!-- Botões -->
    <div class="col-12">
        <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
        <a href="/eventos" class="btn btn-secondary">↩️ Meus Eventos</a>
        <a href="/logout" class="btn btn-danger">🚪 Sair</a>
    </div>
</form>
<?php else: ?>
    <div class="alert alert-danger">Usuário não encontrado.</div>
    <a href="/" class="btn btn-secondary">↩️ Voltar</a>
<?php endif; ?>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
