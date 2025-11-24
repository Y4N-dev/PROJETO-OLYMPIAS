<?php
// includes/auth_check.php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header('Location: /academia-nome/login.php');
  exit;
}
