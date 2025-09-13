<!-- View: Registro -->
<!-- Formulário de registro de usuário -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Atléticas</title>
</head>
<body>
    <h1>Registro de Usuário</h1>
    <!-- O formulário envia dados via POST para /register -->
    <form method="POST" action="/register">
        <label for="nome">Nome completo:</label>
        <input type="text" id="nome" name="nome" required>
        <br><br>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone">
        <br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br><br>

        <label for="tipo_participacao">Tipo de participação:</label>
        <select id="tipo_participacao" name="tipo_participacao" required>
            <option value="ATLETICA">Atlética</option>
            <option value="ALUNO">Aluno</option>
            <option value="PROFESSOR">Professor</option>
            <option value="COMUNIDADE">Comunidade</option>
        </select>
        <br><br>

        <button type="submit">Registrar</button>
    </form>

    <p>Já tem conta? <a href="/login">Faça login</a></p>
</body>
</html>