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
          <a href="/eventos/esportivo/novo" class="btn btn-primary w-100">Novo Evento Esportivo</a>
        </div>
      </div>
    <?php elseif ($tipo === 'PROFESSOR'): ?>
      <div class="col-md-4">
        <div class="home-card">
          <div class="home-icon bg-warning-subtle text-warning">
            <i data-lucide="presentation"></i>
          </div>
          <h5>Evento</h5>
          <p>Solicite a quadra para palestras, workshops e formaturas.</p>
          <a href="/eventos/nao-esportivo/novo" class="btn btn-warning w-100 text-white">Novo Evento</a>
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
          <a href="/eventos" class="btn btn-success w-100">Analisar Solicitações</a>
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
        <a href="/docs/regulamento.pdf" download="regulamento.pdf" class="btn btn-outline-success w-100">Baixar Regulamento</a>
      </div>
    </div>
  </div>

  <!-- Informações institucionais -->
  <div class="home-info card border-0 shadow-sm mb-5">
    <div class="card-body">
      <h5 class="info-title mb-3 d-flex align-items-center">
        <i data-lucide="building" id="home-icons"  class="me-2" style="width:18px;height:18px;"></i> Informações Institucionais
      </h5>
      <div class="row">
        <!-- Coluna 1 -->
        <div class="col-md-6 mb-3">
          <p class="mb-1 fw-semibold"><i data-lucide="map-pin" class="me-2" style="width:16px;height:16px;"></i> Endereço:</p>
          <p class="text-muted small mb-3">Rodovia BR 153, Km 338+420m<br>Bairro Água do Cateto, Ourinhos-SP</p>

          <p class="mb-1 fw-semibold"><i data-lucide="phone" class="me-2" style="width:16px;height:16px;"></i> Telefone:</p>
          <p class="text-muted small mb-0">(14) 3302-6400</p>
        </div>

        <!-- Coluna 2 -->
        <div class="col-md-6">
          <p class="mb-1 fw-semibold"><i data-lucide="mail" class="me-2" style="width:16px;height:16px;"></i> Email:</p>
          <p class="text-muted small mb-3">email@unifio.edu.br</p>

          <p class="mb-1 fw-semibold"><i data-lucide="message-circle" class="me-2" style="width:16px;height:16px;"></i> WhatsApp:</p>
          <p class="text-muted small mb-0">(14) 99999-9999</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Próximos eventos -->
  <div class="home-events card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="info-title mb-3 d-flex align-items-center">
        <i data-lucide="calendar" id="home-icons" class="me-2" style="width:18px;height:18px;"></i> Próximos Eventos
      </h5>

      <?php if ($proximosEventos): ?>
        <ul class="list-unstyled">
          <?php foreach ($proximosEventos as $ev): ?>
            <li class="event-item d-flex justify-content-between align-items-center border-bottom py-2">
              <div>
                <span class="fw-semibold d-block">
                  <?= htmlspecialchars($ev['finalidade'] ?: $ev['subtipo_esportivo'] ?: $ev['subtipo_nao_esportivo']) ?>
                </span>
                <small class="text-muted">
                  <?= date('d/m/Y', strtotime($ev['data_evento'])) ?> – 
                  <?= $ev['periodo'] === 'P1' ? '19:15 - 20:55' : '21:10 - 22:50' ?>
                </small>
              </div>
              <?php
                // Mapear status do BD para nomes amigáveis
                $statusLabels = [
                  'AGENDADO'  => ['label' => 'Agendado',  'class' => 'bg-success-subtle text-success'],
                  'APROVADO'  => ['label' => 'Agendado',  'class' => 'bg-success text-white'],
                  'REJEITADO' => ['label' => 'Rejeitado', 'class' => 'bg-danger text-white'],
                  'CANCELADO' => ['label' => 'Cancelado', 'class' => 'bg-warning text-dark'],
                  'FINALIZADO'=> ['label' => 'Finalizado','class' => 'bg-secondary text-white'],
                ];

                $currentStatus = $statusLabels[$ev['status']] ?? ['label' => 'Pendente', 'class' => 'bg-secondary'];
                ?>
                <span class="badge rounded-pill px-3 <?= $currentStatus['class'] ?>">
                  <?= $currentStatus['label'] ?>
                </span>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="text-center mt-3">
          <a href="/eventos" class="btn btn-outline-primary btn-sm">Ver Todos os Eventos</a>
        </div>
      <?php else: ?>
        <p class="text-muted small">Nenhum evento futuro encontrado.</p>
      <?php endif; ?>
    </div>
  </div>

</div>


<?php 
$content = ob_get_clean(); 
include VIEW_PATH . "/layout.php"; 
?>