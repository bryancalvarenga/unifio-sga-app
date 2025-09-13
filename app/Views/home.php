<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Unifio - Sistema de Atléticas</title>
</head>
<body>
  <h1>Sistema de Gerenciamento de Atléticas</h1>
  <?php
  session_start(); 
  if (isset($_SESSION['user_id'])): ?>
    <p>Bem-vindo, <strong><?= htmlspecialchars($_SESSION['user_nome']) ?></strong></p>
    <nav>
      <a href="/eventos">Meus Eventos</a>
      <a href="/logout">Sair</a>
    </nav>
  <?php else: ?>
    <p>Você não está logado.</p>
    <nav>
      <a href="/login">Login</a> |
      <a href="/register">Cadastrar</a>
    </nav>
  <?php endif; ?>

</body>
</html>
