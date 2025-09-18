<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = "Login - Sistema de Atléticas"; 
ob_start(); 
?>


<div class="d-flex justify-content-center align-items-center auth-login-container">
  <div class="card shadow p-4 auth-login-content" style="max-width: 400px; width: 100%;">
    
    <!-- Logo -->
    <div class="text-center mb-3">
      <img src="/assets/img/unifio-logo-blue.png" alt="Logo UNIFIO" width="120">
    </div>

    <h4 class="text-center mb-4 title-auth">Agendamento da Quadra Poliesportiva</h4>

    <?php if (!empty($_SESSION['login_error'])): ?>
      <div id="login-error" class="alert alert-danger text-center">
        <?= htmlspecialchars($_SESSION['login_error']) ?>
      </div>
      <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>


    <!-- Formulário -->
    <form method="POST" action="/login" class="row g-3">

      <!-- Email -->
      <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="mail"></i></span>
          <input type="email" id="email" name="email" class="form-control" 
                 placeholder="seu.email@unifio.edu.br" required>
        </div>
      </div>

      <!-- Senha -->
      <div class="col-12">
        <label for="senha" class="form-label">Senha</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i data-lucide="lock" id="index-icons"></i></span>
          <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required>

          <button type="button"
                  class="input-group-text bg-light"
                  data-toggle="senha" data-target="senha"
                  aria-label="Mostrar senha" aria-pressed="false">
            <i data-lucide="eye-off" id="index-icons" class="icon-eyeoff"></i>
            <i data-lucide="eye"     id="index-icons" class="icon-eye d-none"></i>
          </button>
        </div>
      </div>


      <!-- Link esqueci senha -->
      <div class="col-12 text-end">
        <a href="/esqueci-senha" class="text-decoration-none">Esqueci minha senha</a>
      </div>

      <!-- Botão Entrar -->
      <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </div>

      <!-- Link cadastro -->
      <div class="col-12 text-center">
        <small class="text-muted">Não tem uma conta?
          <a href="/register" class="text-decoration-none">Cadastrar</a>
        </small>
      </div>
    </form>

  </div>
</div>
<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>