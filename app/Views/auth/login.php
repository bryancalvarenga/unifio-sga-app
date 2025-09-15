<?php 
$title = "Login - Sistema de Atléticas"; 
ob_start(); 
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    
    <!-- Logo -->
    <div class="text-center mb-3">
      <img src="/assets/img/unifio-logo-blue.png" alt="Logo UNIFIO" width="100">
    </div>

    <h4 class="text-center mb-4 fw-bold">Sistema de Agendamento</h4>

    <!-- Formulário -->
    <form method="POST" action="/login" class="row g-3">
      <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="seu.email@unifio.edu.br" required>
      </div>

      <div class="col-12">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required>
      </div>

      <!-- Link esqueci senha -->
      <div class="col-12 text-end">
        <a href="/esqueci-senha" class="text-decoration-none">Esqueci minha senha</a>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </div>

      <div class="col-12 text-center">
        <small class="text-muted">Não tem uma conta?
          <a href="/register" class="fw-semibold">Cadastrar</a>
        </small>
      </div>
    </form>

  </div>
</div>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>