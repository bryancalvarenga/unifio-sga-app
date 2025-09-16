<?php 
$title = "Registro - Sistema de Atléticas"; 
ob_start(); 
?>

<div class="d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-sm p-4 auth-register-content">
    
    <!-- Título -->
    <div class="text-center mb-3">
      <img src="/assets/img/unifio-logo-blue.png" alt="Logo UNIFIO" width="100">
    </div>

    <h4 class="text-center mb-4 title-auth">Cadastre-se no Sistema</h4>

    <!-- Formulário -->
    <form method="POST" action="/register" class="row g-3">
      
      <div class="col-12">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite seu nome completo" required>
      </div>

      <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="seu.email@unifio.edu.br" required>
      </div>

      <div class="col-12">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" id="telefone" name="telefone" class="form-control" placeholder="(11) 99999-9999">
      </div>

      <div class="col-12">
        <label for="tipo_participacao" class="form-label">Tipo de Participação</label>
        <select id="tipo_participacao" name="tipo_participacao" class="form-select" required>
          <option value="">Selecione seu tipo</option>
          <option value="ATLETICA">Atlética</option>
          <option value="ALUNO">Aluno</option>
          <option value="PROFESSOR">Professor</option>
          <option value="COMUNIDADE">Comunidade</option>
        </select>
      </div>

      <div class="col-12">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required>
      </div>

      <div class="col-12">
        <label for="senha_confirm" class="form-label">Confirmar Senha</label>
        <input type="password" id="senha_confirm" name="senha_confirm" class="form-control" placeholder="Confirme sua senha" required>
      </div>

      <div class="col-12 d-grid">
        <button type="submit" class="btn btn-primary">Cadastrar</button>
      </div>

      <div class="col-12 text-center">
        <a href="/login" class="text-decoration-none">Voltar ao Login</a>
      </div>
    </form>
  </div>
</div>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>