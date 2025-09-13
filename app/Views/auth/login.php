<!-- View: login -->
<!-- Formulário de login de usuário -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Atléticas</title>
</head>
<body>
    <!-- O formulário envia dados via POST para /login -->
    <h1>Login</h1>

  <form method="POST" action="/login">
      <label for="email">E-mail:</label>
      <input type="email" name="email" required><br><br>

      <label for="senha">Senha:</label>
      <input type="password" name="senha" required><br><br>

      <button type="submit">Entrar</button>
  </form>

  <p><a href="/register">Não tem conta? Cadastre-se</a></p>
</body>
</html>