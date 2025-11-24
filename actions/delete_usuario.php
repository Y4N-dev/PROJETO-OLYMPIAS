<?php
// actions/delete_account.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['ok' => false, 'msg' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

try {
    $pdo->beginTransaction();

    // opcional: remover parcelas relacionadas
    $stmt = $pdo->prepare("DELETE FROM parcelas WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);

    // remover usuário (cascata manual - ajuste se houver FK com cascade)
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);

    $pdo->commit();

    // encerra sessão e redireciona no front-end
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    session_destroy();

    echo json_encode(['ok' => true]);
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("delete_account.php error: " . $e->getMessage());
    echo json_encode(['ok' => false, 'msg' => 'Erro ao remover conta.']);
    exit;
}
