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
<body>
  <?php
    // Verifica se é página de autenticação
    $isAuthPage = isset($title) && (
        str_contains($title, "Login") ||
        str_contains($title, "Registro")
    );
  ?>

  <?php if (!$isAuthPage): ?>
    <!-- Navbar -->
    <?php include VIEW_PATH . "/partials/navbar.php"; ?>
  <?php endif; ?>

  <div class="<?= $isAuthPage ? 'container d-flex justify-content-center align-items-center vh-100' : 'container mt-4' ?>">
    <?= $content ?>
  </div>

  <?php if (!$isAuthPage): ?>
    <!-- Footer -->
    <?php include VIEW_PATH . "/partials/footer.php"; ?>
  <?php endif; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Seus scripts -->
  <script src="/assets/js/app.js"></script>
</body>
</html>