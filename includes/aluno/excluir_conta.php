<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(400);
    echo "Usuário não autenticado.";
    exit;
}

$uid = $_SESSION['usuario_id'];

try {
    $pdo->beginTransaction();

    // Apaga parcelas
    $stmt1 = $pdo->prepare("DELETE FROM parcelas WHERE usuario_id = ?");
    $stmt1->execute([$uid]);

    // Apaga usuário
    $stmt2 = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt2->execute([$uid]);

    $pdo->commit();

    session_destroy();

    echo "OK";
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo "Erro ao excluir a conta.";
}
