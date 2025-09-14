<?php 
session_start();
$title = "Home - Sistema de Atléticas"; 
ob_start(); 
?>

  <h1>Sistema de Gerenciamento de Atléticas</h1>

  <?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if (isset($_SESSION['user_id'])): 
      $nome = htmlspecialchars($_SESSION['user_nome']);
      $tipo = $_SESSION['tipo_participacao'];
  ?>
    <p>Bem-vindo, <strong><?= $nome ?></strong> (<?= $tipo ?>)</p>

    <hr>

    <?php if ($tipo === 'ATLETICA'): ?>
        <h2>Menu Atlética</h2>
        <ul>
          <li><a href="/eventos/novo">Criar Evento Esportivo</a></li>
          <li><a href="/eventos">Gerenciar meus eventos</a></li>
        </ul>

    <?php elseif ($tipo === 'PROFESSOR'): ?>
        <h2>Menu Professor</h2>
        <ul>
          <li><a href="/eventos/novo">➕ Criar Evento Acadêmico</a></li>
          <li><a href="/eventos">Meus eventos</a></li>
        </ul>

    <?php elseif ($tipo === 'ALUNO'): ?>
        <h2>Menu Aluno</h2>
        <ul>
          <li><a href="/eventos">Visualizar eventos</a></li>
          <li><a href="/eventos">Cancelar minha participação (quando aplicável)</a></li>
        </ul>

    <?php elseif ($tipo === 'COMUNIDADE'): ?>
        <h2>Menu Comunidade</h2>
        <ul>
          <li><a href="/eventos">Visualizar eventos públicos</a></li>
        </ul>

    <?php else: ?>
        <p>Tipo de usuário não reconhecido.</p>
    <?php endif; ?>

  <?php else: ?>
    <p>Você não está logado.</p>
    <nav>
      <a href="/login">Login</a> |
      <a href="/register">Cadastrar</a>
    </nav>
  <?php endif; ?>
  
<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>