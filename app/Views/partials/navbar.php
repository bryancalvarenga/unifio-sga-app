<!-- Navbar moderna com 3 blocos -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
  <div class="container">

    <!-- Bloco da esquerda (Logo) -->
    <a class="navbar-brand d-flex align-items-center" href="/">
      <img src="/assets/img/unifio-logo-blue.png" alt="Logo UNIFIO" width="100">
    </a>

    <!-- Botão mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Bloco central (menu) -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="/">
            <i class="bi bi-house me-1"></i> Início
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="/eventos">
            <i class="bi bi-calendar-event me-1"></i> Meus Eventos
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="/perfil">
            <i class="bi bi-person me-1"></i> Perfil
          </a>
        </li>

        <!-- Botão Sair no mobile -->
        <li class="nav-item d-lg-none">
          <a class="nav-link text-danger d-flex align-items-center" href="/logout">
            <i class="bi bi-box-arrow-right me-1"></i> Sair
          </a>
        </li>
      </ul>
    </div>

    <!-- Botão Sair no desktop -->
    <div class="d-none d-lg-flex">
      <a class="nav-link text-danger d-flex align-items-center" href="/logout">
        <i class="bi bi-box-arrow-right me-1"></i> Sair
      </a>
    </div>

  </div>
</nav>
