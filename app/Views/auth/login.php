<?php 
$title = "Login - Sistema de Atléticas"; 
ob_start(); 
?>

<h1 class="mb-4">Login</h1>

<form method="POST" action="/login" class="row g-3">
    <div class="col-12">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>

    <div class="col-12">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" id="senha" name="senha" class="form-control" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">🔐 Entrar</button>
        <a href="/register" class="btn btn-link">Não tem conta? Cadastre-se</a>
    </div>
</form>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>