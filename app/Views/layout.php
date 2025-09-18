<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Sistema de Atléticas' ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="site-body">
  <script src="https://unpkg.com/lucide@latest"></script>
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?> mt-2 text-center">
      <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <?php
    // Verifica se é página de autenticação
    $isAuthPage = isset($title) && (
        str_contains($title, "Login") ||
        str_contains($title, "Registro")
    );
  ?>

  <?php if (!$isAuthPage): ?>
    <!-- Header -->
    <header class="site-header">
      <?php include VIEW_PATH . "/partials/header.php"; ?>
    </header>
  <?php endif; ?>
  
  <main class="site-main">
    <div class="<?= $isAuthPage ? 'container d-flex justify-content-center align-items-center vh-100' : 'container mt-4' ?>">
      <?= $content ?>
    </div>
  </main>

  <?php if (!$isAuthPage): ?>
    <!-- Footer -->
     <footer class="site-footer text-light pt-5 pb-3 mt-5">
      <?php include VIEW_PATH . "/partials/footer.php"; ?>
    </footer>
  <?php endif; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scripts -->
  <script src="/assets/js/app.js"></script>
  <script src="/assets/js/auth.js"></script>
</body>
</html>