<?php 
$title = "Meu Perfil - Sistema de Atléticas"; 
ob_start(); 
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

      <!-- Título -->
      <div class="mb-4">
        <h2 class="fw-bold d-flex align-items-center">
          <i data-lucide="user" id="home-icons" class="me-2 icon-lg"></i> Meu Perfil
        </h2>
        <p class="text-muted">Gerencie suas informações pessoais</p>
      </div>

      <!-- Card Informações Pessoais -->
      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

          <h5 class="fw-bold mb-1">Informações Pessoais</h5>
          <p class="text-muted small mb-4">Mantenha seus dados atualizados para melhor comunicação</p>

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
          <form method="POST" action="/perfil/atualizar" enctype="multipart/form-data" class="row g-4">

            <!-- Foto + Nome -->
            <div class="col-12 d-flex align-items-center mb-4">
              <div class="me-3">
                <img src="<?= $usuario['foto_perfil'] ?? '/assets/img/default-user.png' ?>" 
                     alt="Foto de Perfil" 
                     class="rounded-circle shadow-sm" width="80" height="80">
              </div>
              <div>
                <h6 class="mb-1"><?= htmlspecialchars($usuario['nome']) ?></h6>
                <p class="text-muted small mb-2"><?= ucfirst(strtolower($usuario['tipo_participacao'] ?? 'Usuário')) ?></p>
                <label for="foto_perfil" class="btn btn-sm btn-outline-primary d-flex align-items-center">
                  <i data-lucide="camera" class="icon-sm me-1"></i> Alterar foto
                </label>
                <input type="file" name="foto_perfil" id="foto_perfil" class="d-none">
              </div>
            </div>

            <!-- Nome -->
            <div class="col-md-6">
              <label class="form-label">Nome Completo</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i data-lucide="user" class="icon-sm"></i></span>
                <input type="text" name="nome" class="form-control"
                       value="<?= htmlspecialchars($usuario['nome']) ?>" required>
              </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <label class="form-label">E-mail</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i data-lucide="mail" class="icon-sm"></i></span>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($usuario['email']) ?>" required>
              </div>
            </div>

            <!-- Telefone -->
            <div class="col-md-6">
              <label class="form-label">Telefone</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i data-lucide="phone" class="icon-sm"></i></span>
                <input type="text" name="telefone" class="form-control"
                       value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>">
              </div>
            </div>

            <!-- Senha -->
            <div class="col-md-6">
              <label class="form-label">Senha</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i data-lucide="lock" class="icon-sm"></i></span>
                <input type="password" name="senha" class="form-control" placeholder="Deixe em branco p/ não alterar">
              </div>
            </div>

            <!-- Botões -->
            <div class="col-12 d-flex justify-content-between mt-6">
              <div class="profile-btn d-flex gap-3">
                <button type="submit" class="btn btn-primary d-flex align-items-center">
                    <i data-lucide="save" class="icon-sm me-1"></i> Salvar Alterações
                  </button>
                <a href="/eventos" class="btn btn-outline-secondary d-flex align-items-center">
                  <i data-lucide="calendar" class="icon-sm me-1"></i> Meus Eventos
                </a>
              </div>
              <div>
                <a href="/logout" class="profile-btn-logout btn btn-outline-danger d-flex align-items-center">
                  <i data-lucide="log-out" class="icon-sm me-1"></i> Sair
                </a>
              </div>
            </div>
          </form>
          <?php else: ?>
            <div class="alert alert-danger">Usuário não encontrado.</div>
            <a href="/" class="btn btn-secondary">Voltar</a>
          <?php endif; ?>

        </div>
      </div>

      <!-- Card Informações da Conta -->
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3">Informações da Conta</h5>
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><strong>Tipo de usuário:</strong> <?= ucfirst(strtolower($usuario['tipo_participacao'] ?? 'Usuário')) ?></li>
            <li class="mb-2"><strong>Membro desde:</strong> Janeiro 2024</li>
            <li class="mb-2"><strong>Eventos agendados:</strong> 12</li>
            <li><strong>Status da conta:</strong> <span class="text-success">Ativa</span></li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>
