<?php 
$title = "Registro - Sistema de Atléticas"; 
ob_start(); 
?>

<h1 class="mb-4">Registro de Usuário</h1>

<form method="POST" action="/register" class="row g-3">
    <div class="col-12">
        <label for="nome" class="form-label">Nome completo</label>
        <input type="text" id="nome" name="nome" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" id="telefone" name="telefone" class="form-control">
    </div>

    <div class="col-md-6">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" id="senha" name="senha" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="tipo_participacao" class="form-label">Tipo de participação</label>
        <select id="tipo_participacao" name="tipo_participacao" class="form-select" required>
            <option value="ATLETICA">Atlética</option>
            <option value="ALUNO">Aluno</option>
            <option value="PROFESSOR">Professor</option>
            <option value="COMUNIDADE">Comunidade</option>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">📝 Registrar</button>
        <a href="/login" class="btn btn-link">Já tem conta? Faça login</a>
    </div>
</form>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
