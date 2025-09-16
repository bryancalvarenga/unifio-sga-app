<?php 
session_start();
$title = "Home - Sistema de Atléticas"; 
ob_start(); 

// Se não estiver logado, redireciona para login
if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

$nome = $_SESSION['user_nome'] ?? null;
$tipo = $_SESSION['tipo_participacao'] ?? null;

// Buscar próximos eventos (exemplo: 5 mais próximos aprovados ou agendados)
$pdo = \Core\Database::getConnection();
$stmt = $pdo->query("
    SELECT id, categoria, data_evento, periodo, status, subtipo_esportivo, subtipo_nao_esportivo, finalidade
    FROM events
    WHERE data_evento >= CURDATE()
    ORDER BY data_evento ASC, periodo ASC
    LIMIT 5
");
$proximosEventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h1 class="text-center mb-4">Sistema de Gerenciamento</h1>
  <h2 class="text-center mb-4 home-subtitle">Quadra Poliesportiva do Centro Universitário de Ourinhos</h2>
  <p class="text-center home-description">
    Bem-vindo, <strong><?= htmlspecialchars($nome) ?></strong> (<?= htmlspecialchars($tipo) ?>)! Gerencie seus agendamentos de forma simples e eficiente. Agende eventos esportivos, palestras e muito mais.
  </p>

  <div class="row g-4 my-4">
    <?php if ($tipo === 'ATLETICA'): ?>
      <!-- Card Evento Esportivo -->
      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="bi bi-trophy display-4 text-primary"></i>
            <h5 class="card-title mt-3">Evento Esportivo</h5>
            <p class="card-text">Agende treinos e campeonatos na quadra.</p>
            <a href="/eventos/esportivo/novo" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      </div>
    <?php elseif ($tipo === 'PROFESSOR'): ?>
      <!-- Card Evento Não Esportivo -->
      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="bi bi-people display-4 text-warning"></i>
            <h5 class="card-title mt-3">Evento Não Esportivo</h5>
            <p class="card-text">Solicite a quadra para palestras, workshops e formaturas.</p>
            <a href="/eventos/nao-esportivo/novo" class="btn btn-warning text-white">Acessar</a>
          </div>
        </div>
      </div>
    <?php elseif ($tipo === 'COORDENACAO'): ?>
      <!-- Card Aprovação -->
      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="bi bi-check2-circle display-4 text-success"></i>
            <h5 class="card-title mt-3">Gerenciar Eventos</h5>
            <p class="card-text">Aprove ou rejeite solicitações de eventos.</p>
            <a href="/eventos" class="btn btn-success">Acessar</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Card Regulamento (sempre visível) -->
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body text-center">
          <i class="bi bi-file-earmark-text display-4 text-success"></i>
          <h5 class="card-title mt-3">Regulamento</h5>
          <p class="card-text">Consulte as regras e diretrizes de uso da quadra.</p>
          <a href="/docs/regulamento.pdf" target="_blank" class="btn btn-success">Acessar</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Informações Institucionais -->
  <div class="card shadow-sm my-4">
    <div class="card-body">
      <h5 class="card-title"><i class="bi bi-building"></i> Informações Institucionais</h5>
      <div class="row mt-3">
        <div class="col-md-6">
          <p><i class="bi bi-geo-alt"></i> Rodovia BR 153, Km 338+420m<br>Bairro Água do Cateto, Ourinhos-SP</p>
          <p><i class="bi bi-telephone"></i> (14) 3302-6400</p>
        </div>
        <div class="col-md-6">
          <p><i class="bi bi-envelope"></i> email@unifio.edu.br</p>
          <p><i class="bi bi-whatsapp"></i> (14) 99999-9999</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Próximos Eventos -->
  <div class="card shadow-sm my-4">
    <div class="card-body">
      <h5 class="card-title"><i class="bi bi-calendar-event"></i> Próximos Eventos</h5>
      <?php if ($proximosEventos): ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($proximosEventos as $ev): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>
                <?= htmlspecialchars($ev['finalidade'] ?: $ev['subtipo_esportivo'] ?: $ev['subtipo_nao_esportivo']) ?>
                - <?= htmlspecialchars($ev['categoria']) ?> |
                <?= htmlspecialchars($ev['data_evento']) ?> <?= htmlspecialchars($ev['periodo']) ?>
              </span>
              <span class="badge 
                <?= $ev['status']==='APROVADO'?'bg-success':
                    ($ev['status']==='REJEITADO'?'bg-danger':
                    ($ev['status']==='CANCELADO'?'bg-warning text-dark':'bg-secondary')) ?>">
                <?= htmlspecialchars($ev['status']) ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="text-center mt-3">
          <a href="/eventos" class="btn btn-outline-primary">Ver Todos os Eventos</a>
        </div>
      <?php else: ?>
        <p class="text-muted">Nenhum evento futuro encontrado.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>