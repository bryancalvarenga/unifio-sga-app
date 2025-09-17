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

<div class="container py-4 home-wrapper">

  <!-- Título -->
  <h1 class="home-title">Quadra Poliesportiva</h1>
  <h2 class="home-subtitle">Centro Universitário de Ourinhos</h2>
  <p class="home-description">
    Bem-vindo, <strong><?= htmlspecialchars($nome) ?></strong> (<?= htmlspecialchars($tipo) ?>)! 
    Gerencie seus agendamentos de forma simples e eficiente. 
    Agende eventos esportivos, palestras e muito mais.
  </p>

  <!-- Cards principais -->
  <div class="row g-4 mb-5">
    <?php if ($tipo === 'ATLETICA'): ?>
      <div class="col-md-4">
        <div class="home-card">
          <div class="home-icon bg-primary-subtle text-primary">
            <i data-lucide="trophy"></i>
          </div>
          <h5>Evento Esportivo</h5>
          <p>Agende treinos e campeonatos na quadra poliesportiva.</p>
          <a href="/eventos/esportivo/novo" class="btn btn-primary w-100">Acessar</a>
        </div>
      </div>
    <?php elseif ($tipo === 'PROFESSOR'): ?>
      <div class="col-md-4">
        <div class="home-card">
          <div class="home-icon bg-warning-subtle text-warning">
            <i data-lucide="presentation"></i>
          </div>
          <h5>Evento Não Esportivo</h5>
          <p>Solicite a quadra para palestras, workshops e formaturas.</p>
          <a href="/eventos/nao-esportivo/novo" class="btn btn-warning w-100 text-white">Acessar</a>
        </div>
      </div>
    <?php elseif ($tipo === 'COORDENACAO'): ?>
      <div class="col-md-4">
        <div class="home-card">
          <div class="home-icon bg-success-subtle text-success">
            <i data-lucide="check-square"></i>
          </div>
          <h5>Gerenciar Eventos</h5>
          <p>Aprove ou rejeite solicitações de eventos.</p>
          <a href="/eventos" class="btn btn-success w-100">Acessar</a>
        </div>
      </div>
    <?php endif; ?>

    <!-- Regulamento -->
    <div class="col-md-4">
      <div class="home-card">
        <div class="home-icon bg-success-subtle text-success">
          <i data-lucide="file-text"></i>
        </div>
        <h5>Regulamento</h5>
        <p>Consulte as regras e diretrizes de uso da quadra.</p>
        <a href="/docs/regulamento.pdf" target="_blank" class="btn btn-outline-success w-100">Acessar</a>
      </div>
    </div>
  </div>

  <!-- Informações institucionais -->
  <div class="home-info card border-0 shadow-sm mb-5">
    <div class="card-body">
      <h5 class="info-title"><i data-lucide="building" class="me-2"></i> Informações Institucionais</h5>
      <div class="row mt-3">
        <div class="col-md-6 mb-3">
          <p><i data-lucide="map-pin" class="me-2"></i> Rodovia BR 153, Km 338+420m<br>Bairro Água do Cateto, Ourinhos-SP</p>
          <p><i data-lucide="phone" class="me-2"></i> (14) 3302-6400</p>
        </div>
        <div class="col-md-6">
          <p><i data-lucide="mail" class="me-2"></i> email@unifio.edu.br</p>
          <p><i data-lucide="message-circle" class="me-2"></i> (14) 99999-9999</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Próximos eventos -->
  <div class="home-events card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="info-title"><i data-lucide="calendar" class="me-2"></i> Próximos Eventos</h5>
      <?php if ($proximosEventos): ?>
        <ul class="list-unstyled mt-3">
          <?php foreach ($proximosEventos as $ev): ?>
            <li class="event-item d-flex justify-content-between align-items-center">
              <div>
                <span class="event-title">
                  <?= htmlspecialchars($ev['finalidade'] ?: $ev['subtipo_esportivo'] ?: $ev['subtipo_nao_esportivo']) ?>
                </span>
                <div class="event-meta text-muted small">
                  <?= date('d/m/Y', strtotime($ev['data_evento'])) ?> – <?= htmlspecialchars($ev['periodo']) ?>
                </div>
              </div>
              <span class="badge rounded-pill 
                <?= $ev['status']==='APROVADO'?'bg-success':
                    ($ev['status']==='REJEITADO'?'bg-danger':
                    ($ev['status']==='CANCELADO'?'bg-warning text-dark':'bg-secondary')) ?>">
                <?= htmlspecialchars($ev['status']) ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="text-center mt-4">
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