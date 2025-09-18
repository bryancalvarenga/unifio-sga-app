<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tipo = $_SESSION['tipo_participacao'] ?? null; // garante sempre definido
$nome = $_SESSION['user']['nome'] ?? 'Meu Perfil';
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
      <img src="/assets/img/unifio-logo-blue.png" alt="Logo UNIFIO" width="100">
    </a>

    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu principal -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav gap-3">
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-1" href="/">
            <i data-lucide="home" class="header-icons me-1"></i> Início
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-1" href="/eventos">
            <i data-lucide="calendar" class="header-icons me-1"></i>Meus Eventos
          </a>
        </li>

        <!-- Menus específicos -->
        <?php if ($tipo === 'ATLETICA'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-1" href="/eventos/esportivo/novo">
              <i data-lucide="trophy" class="header-icons me-1"></i>Agendar Esportivo
            </a>
          </li>
        <?php elseif ($tipo === 'PROFESSOR'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-1" href="/eventos/nao-esportivo/novo">
              <i data-lucide="presentation" class="header-icons me-1"></i>Agendar Evento
            </a>
          </li>
        <?php elseif ($tipo === 'COORDENACAO'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-1" href="/eventos">
              <i data-lucide="check-square" class="header-icons me-1"></i>Analisar Solicitações
            </a>
          </li>
        <?php endif; ?>

        <!-- Perfil e Sair (só no mobile) -->
        <li class="nav-item d-lg-none">
          <a href="/perfil" class="nav-link d-flex align-items-center">
            <i class="header-icons" data-lucide="user"></i>
            <span class="ms-1"><?= htmlspecialchars($nome) ?></span>
          </a>
        </li>
        <li class="nav-item d-lg-none">
          <a href="/logout" class="nav-link text-danger d-flex align-items-center">
            <i class="header-icons" data-lucide="log-out"></i>
            <span class="ms-1">Sair</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Perfil e Sair (só desktop) -->
    <div class="d-none d-lg-flex align-items-center gap-3">
      <a href="/perfil" class="nav-link d-flex align-items-center text-muted">
        <i class="header-icons" data-lucide="user"></i>
        <span class="ms-1"><?= htmlspecialchars($nome) ?></span>
      </a>
      <a href="/logout" class="btn btn-sm btn-outline-danger d-flex align-items-center">
        <i class="header-icons" data-lucide="log-out"></i>
        <span class="ms-1">Sair</span>
      </a>
    </div>
  </div>
</nav>

