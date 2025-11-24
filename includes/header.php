<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OLYMPIA'S - Academia Virtual</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/principal/header.css">
  <link rel="stylesheet" href="assets/css/principal/footer.css">

  <!-- ===== CSS ESPECÍFICO ===== -->
  <?php
  $page = basename($_SERVER['PHP_SELF']);
  switch ($page) {
      case 'index.php':
          echo '<link rel="stylesheet" href="assets/css/principal/style.css">';
          echo '<link rel="stylesheet" href="assets/css/principal/carrossel/style.css">';
          echo '<link rel="stylesheet" href="assets/css/principal/conteudo_motivacional/style.css">';
          echo '<link rel="stylesheet" href="assets/css/principal/blocos_unidades/style.css">';
          echo '<link rel="stylesheet" href="assets/css/componentes/cards.css">';
          break;

      case 'unidades.php':
      case 'unidade_detalhes.php':
          echo '<link rel="stylesheet" href="assets/css/unidades/style.css">';
          echo '<link rel="stylesheet" href="assets/css/principal/style.css">';
          break;
      
      case 'sobre.php';
          echo '';
          break;

      case 'motivacionais.php';
          echo '';
          echo '<link rel="stylesheet" href="assets/css/principal/conteudo_motivacional/style.css">';
          break;

      case 'cadastro.php':
          echo '<link rel="stylesheet" href="assets/css/cadastro/style.css">';
          echo '<link rel="stylesheet" href="assets/css/cadastro/popin_pagamento.css">';
          echo '<link rel="stylesheet" href="assets/css/principal/style.css">';
          break;

      case 'login.php':
          echo '<link rel="stylesheet" href="assets/css/login/style.css">';
          echo '<link rel="stylesheet" href="assets/css/principal/style.css">';
          break;

      case 'contatos.php':
          echo '<link rel="stylesheet" href="assets/css/contato/style.css">';
          break;

      case 'painel.php':
          echo '<link rel="stylesheet" href="assets/css/painel_usuarios/style.css">';
          break;

      case 'carrossel.php':
          echo '<link rel="stylesheet" href="assets/css/principal/carrossel/style.css">';
          break;
  }
  ?>
</head>

    <!-- ============ TELA DE CARREGAMENTO GLOBAL ============ -->
<style>
#loading-screen {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(20, 20, 20, 1);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  transition: opacity 0.3s ease;
  opacity: 1;
  visibility: visible;
}

#loading-ring {
  border: 6px solid transparent;
  border-top: 6px solid #2ecc71;
  border-right: 6px solid #f1c40f;
  border-radius: 50%;
  width: 80px;
  height: 80px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<div id="loading-screen">
  <div id="loading-ring"></div>
</div>

  <body>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
      <a class="navbar-brand fw-bold text-warning" href="index.php"><strong>OLYMPIA'S</strong></a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link text-light" href="unidades.php"><strong>Unidades</strong></a></li>
          <li class="nav-item"><a class="nav-link text-light" href="contatos.php"><strong>Contato</strong></a></li>
          <?php if (isset($_SESSION['usuario_id'])): ?>
            <li class="nav-item"><a class="nav-link text-success fw-bold" href="painel.php">Painel</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="actions/logout.php"><strong>Sair</strong></a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link text-light" href="login.php"><strong>Entrar</strong></a></li>
          <?php endif; ?>
        </ul>
      </div>
    </header>

    <main>
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const loader = document.getElementById("loading-screen");
          setTimeout(() => {
            loader.style.opacity = "0";
            setTimeout(() => {
              loader.style.visibility = "hidden";
              document.body.classList.add("loaded");
            }, 1500);
          }, 800); // tempo total visível = 0.8s antes do fade
        });
      </script>