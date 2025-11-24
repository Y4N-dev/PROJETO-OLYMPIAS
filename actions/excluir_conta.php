<?php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/db_connect.php';

// garante que o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["ok" => false, "msg" => "Usuário não autenticado"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

try {
    // apagar parcelas do usuário
    $stmt = $pdo->prepare("DELETE FROM parcelas WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);

    // apagar o usuário
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);

    // destruir sessão
    session_destroy();

    echo json_encode(["ok" => true]);
    exit;

} catch (Exception $e) {
    echo json_encode(["ok" => false, "msg" => "Erro interno: " . $e->getMessage()]);
    exit;
}
