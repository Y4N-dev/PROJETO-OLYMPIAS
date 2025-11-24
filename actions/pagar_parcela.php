<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    echo json_encode(['ok' => false, 'msg' => 'Usuário não autenticado']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$id = intval($data['id'] ?? 0);
if (!$id) {
    echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
    exit;
}

// verifica se parcela pertence ao usuário e se ainda não foi paga
$stmt = $pdo->prepare("SELECT id, status FROM parcelas WHERE id = ? AND usuario_id = ? LIMIT 1");
$stmt->execute([$id, $usuario_id]);
$p = $stmt->fetch();

if (!$p) {
    echo json_encode(['ok' => false, 'msg' => 'Parcela não encontrada']);
    exit;
}

if ($p['status'] === 'pago') {
    echo json_encode(['ok' => false, 'msg' => 'Parcela já foi paga']);
    exit;
}

// marca como paga
$upd = $pdo->prepare("UPDATE parcelas SET status = 'pago', pago_em = NOW() WHERE id = ? AND usuario_id = ? LIMIT 1");
$ok = $upd->execute([$id, $usuario_id]);

if ($ok) {
    echo json_encode(['ok' => true, 'msg' => 'Pagamento confirmado', 'id' => $id]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Erro ao atualizar parcela']);
}
