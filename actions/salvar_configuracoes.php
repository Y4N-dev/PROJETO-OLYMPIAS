<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header("Content-Type: application/json");

require_once __DIR__ . "/../../includes/db_connect.php";

$usuario_id = $_SESSION["usuario_id"] ?? null;
if (!$usuario_id) {
    echo json_encode(["ok" => false, "msg" => "Usuário não autenticado."]);
    exit;
}

/* ============================
   Verificar se a PRIMEIRA parcela está paga
============================ */
$stmt = $pdo->prepare("SELECT status FROM parcelas WHERE usuario_id = ? ORDER BY id ASC LIMIT 1");
$stmt->execute([$usuario_id]);
$primeira = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$primeira || $primeira["status"] !== "pago") {
    echo json_encode(["ok" => false, "msg" => "Você só pode alterar os dados após pagar a primeira parcela."]);
    exit;
}

/* ============================
   Atualizar dados
============================ */
$nome = $_POST["nome"] ?? "";
$email = $_POST["email"] ?? "";
$telefone = $_POST["telefone"] ?? "";
$endereco = $_POST["endereco"] ?? "";
$unidade = $_POST["unidade"] ?? "";
$plano = $_POST["plano"] ?? "";

$stmt = $pdo->prepare("
    UPDATE usuarios 
    SET nome = ?, email = ?, telefone = ?, endereco = ?, unidade = ?, plano = ?
    WHERE id = ?
");

$ok = $stmt->execute([$nome, $email, $telefone, $endereco, $unidade, $plano, $usuario_id]);

echo json_encode([
    "ok" => $ok,
    "msg" => $ok ? "" : "Erro ao atualizar o registro."
]);
