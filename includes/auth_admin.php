<?php
// includes/auth_admin.php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'funcionario') {
  header('Location: /academia-nome/login.php');
  exit;
}
