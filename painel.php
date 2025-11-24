<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$usuario_nome = strtoupper($_SESSION['usuario_nome']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Aluno - OLYMPIA'S</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/global.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/navbar.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/sidebar.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/modal.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/dashboard.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/treinos.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/pagamentos.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/meuCadastro.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/agendamentos.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/pendencias.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/horarios.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/notificacoes.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/configuracoes.css">
    <link rel="stylesheet" href="assets/css/painel_aluno/suporte.css">

</head>
<body>
    <div class="painel-container">
      <!-- HEADER / NAVBAR DO PAINEL -->
      <header class="painel-navbar">
        <button id="menuToggle" class="menu-toggle" aria-label="Abrir/fechar menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
        <div class="navbar-animation" aria-hidden="false">
          <span id="animPrimary" class="anim-text anim-primary">OLYMPIA'S</span>
          <span id="animSecondary" class="anim-text anim-secondary">A Academia Virtual do Seu Jeito!</span>
        </div>
      </header>

      <!-- ===== SIDEBAR ===== -->
      <aside class="sidebar" id="sidebar">
          <nav>
            <ul>
                <li class="active" data-section="dashboard"><i class="bi bi-speedometer2"></i> Dashboard</li>
                <li data-section="pendencias"><i class="bi bi-wallet2"></i> Pendências</li>
                <li data-section="horarios"><i class="bi bi-calendar-week"></i> Horários</li>
                <li data-section="agendamentos"><i class="bi bi-clipboard-check"></i> Agendamentos</li>
                <li data-section="meu_cadastro"><i class="bi bi-person-lines-fill"></i> Meu Cadastro</li>
                <li data-section="treinos"><i class="bi bi-heart-pulse"></i> Meus Treinos</li>
                <li data-section="pagamentos"><i class="bi bi-credit-card"></i> Pagamentos</li>
                <li data-section="notificacoes"><i class="bi bi-bell"></i> Notificações</li>
                <li data-section="configuracoes"><i class="bi bi-gear"></i> Configurações</li>
                <li data-section="suporte"><i class="bi bi-headset"></i> Suporte</li>
                <li data-section="logout" class="red-sair"><i class="bi bi-box-arrow-right"></i> Sair</li>
            </ul>
          </nav>
      </aside>

      <!-- ===== CONTEÚDO PRINCIPAL ===== -->
      <main class="main-content">
          <?php include 'includes/aluno/dashboard.php'; ?>
          <?php include 'includes/aluno/meu_cadastro.php'; ?>
          <?php include 'includes/aluno/horarios.php'; ?>
          <?php include 'includes/aluno/treinos.php'; ?>
          <?php include 'includes/aluno/agendamentos.php'; ?>
          <?php include 'includes/aluno/pagamentos.php'; ?>
          <?php include 'includes/aluno/pendencias.php'; ?>
          <?php include 'includes/aluno/notificacoes.php'; ?>
          <?php include 'includes/aluno/configuracoes.php'; ?>
          <?php include 'includes/aluno/suporte.php'; ?>
      </main>

      <!-- ===== MODAL DE PAGAMENTO (apenas 1 cópia - aqui) ===== -->
      <div id="pagamentoModal" class="modal-overlay" aria-hidden="true" style="display:none;">
        <div class="modal-box">
          <button id="fecharModal" class="modal-close" aria-label="Fechar pop-in">&times;</button>
          <h3 class="modal-title">Pague o boleto para concluir o pagamento!</h3>
          <div class="qr-container">
            <img src="assets/img/fake_qr.png" alt="QR Code para pagamento" class="qr-fake">
          </div>
          <button id="confirmarPagamento" class="modal-btn">Confirmar pagamento</button>

          <div class="loading-area" style="display:none;">
            <div class="spinner"></div>
            <p class="status-msg">Verificando pagamento...</p>
          </div>

          <div class="result-area" style="display:none;">
            <div class="result-icon"></div>
            <p class="result-msg"></p>
          </div>
        </div>
      </div>

    </div>

    <script src="assets/js/suporte.js" defer></script>
    <script src="assets/js/configuracoes.js" defer></script>
    <script src="assets/js/app_notificacoes.js"></script>
    <script src="assets/js/painel_aluno.js" defer></script>
    <script src="assets/js/pagamentos.js" defer></script>
</body>
</html>
