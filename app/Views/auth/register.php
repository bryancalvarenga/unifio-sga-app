<?php 
$title = "Registro - Sistema de Atléticas"; 
ob_start(); 
?>

<div class="d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-sm p-4 auth-register-content" style="max-width: 500px; width: 100%;">
    
    <!-- Logo -->
    <div class="text-center mb-3">
      <img src="/assets/img/unifio-logo-blue.png" alt="Logo UNIFIO" width="100">
    </div>

    <h4 class="text-center mb-4 title-auth">Cadastre-se no Sistema</h4>

    <!-- Formulário -->
    <form method="POST" action="/register" class="row g-3">

      <!-- Nome -->
      <div class="col-12">
        <label for="nome" class="form-label">Nome Completo</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="user"></i></span>
          <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite seu nome completo" required>
        </div>
      </div>

      <!-- Email -->
      <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="mail"></i></span>
          <input type="email" id="email" name="email" class="form-control" placeholder="seu.email@unifio.edu.br" required>
        </div>
      </div>

      <!-- Telefone -->
      <div class="col-12">
        <label for="telefone" class="form-label">Telefone</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="phone"></i></span>
          <input type="text" id="telefone" name="telefone" class="form-control" placeholder="(11) 99999-9999">
        </div>
      </div>

      <!-- Tipo de Participação -->
      <div class="col-12">
        <label for="tipo_participacao" class="form-label">Tipo de Participação</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="users"></i></span>
          <select id="tipo_participacao" name="tipo_participacao" class="form-select" required>
            <option value="">Selecione seu tipo</option>
            <option value="ATLETICA">Atlética</option>
            <option value="ALUNO">Aluno</option>
            <option value="PROFESSOR">Professor</option>
            <option value="COMUNIDADE">Comunidade</option>
          </select>
        </div>
      </div>

      <!-- Curso -->
      <div class="col-12">
        <label for="curso" class="form-label">Curso</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="book-open"></i></span>
          <select id="curso" name="curso" class="form-select" required>
            <option value="">Selecione seu curso</option>
            <option value="Agronomia">Agronomia</option>
            <option value="Biomedicina">Biomedicina</option>
            <option value="Ciências Biológicas">Ciências Biológicas</option>
            <option value="Ciências Contábeis">Ciências Contábeis</option>
            <option value="Design de Interiores">Design de Interiores</option>
            <option value="Direito">Direito</option>
            <option value="Educação Física">Educação Física</option>
            <option value="Enfermagem">Enfermagem</option>
            <option value="Engenharia Civil">Engenharia Civil</option>
            <option value="Engenharia de Produção">Engenharia de Produção</option>
            <option value="Engenharia de Software">Engenharia de Software</option>
            <option value="Engenharia Elétrica">Engenharia Elétrica</option>
            <option value="Engenharia Mecânica">Engenharia Mecânica</option>
            <option value="Farmácia">Farmácia</option>
            <option value="Fisioterapia">Fisioterapia</option>
            <option value="Medicina Veterinária">Medicina Veterinária</option>
            <option value="Nutrição">Nutrição</option>
            <option value="Odontologia">Odontologia</option>
            <option value="Psicologia">Psicologia</option>
          </select>
        </div>
      </div>

      <!-- Senha -->
      <div class="col-12">
        <label for="senha" class="form-label">Senha</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="lock" id="index-icons"></i></span>
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

      <!-- Confirmar Senha -->
      <div class="col-12">
        <label for="senha_confirm" class="form-label">Confirmar Senha</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i id="index-icons" data-lucide="lock"></i></span>
          <input type="password" id="senha_confirm" name="senha_confirm" class="form-control" placeholder="Confirme sua senha" required>
        </div>
      </div>

      <!-- Botão -->
      <div class="col-12 d-grid">
        <button type="submit" class="btn btn-primary">Cadastrar</button>
      </div>

      <!-- Voltar -->
      <div class="col-12 text-center">
        <a href="/login" class="text-decoration-none">Voltar ao Login</a>
      </div>
    </form>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="/assets/js/register.js"></script>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
