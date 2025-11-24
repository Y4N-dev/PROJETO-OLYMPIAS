<?php
// actions/update_usuario.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['ok' => false, 'msg' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método inválido.']);
    exit;
}

// Coleta e sanitize
$nome     = trim($_POST['nome'] ?? '');
$email    = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$unidade  = trim($_POST['unidade'] ?? '');
$plano    = trim($_POST['plano'] ?? '');

if ($nome === '' || $email === '') {
    echo json_encode(['ok' => false, 'msg' => 'Nome e e-mail são obrigatórios.']);
    exit;
}

// RESTRIÇÃO: permitir alteração apenas se a "primeira parcela" já estiver paga.
// Entendemos "primeira parcela" como a parcela com menor vencimento para esse usuário.
try {
    $stmt = $pdo->prepare("
        SELECT pago_em
        FROM parcelas
        WHERE usuario_id = ?
        ORDER BY vencimento ASC
        LIMIT 1
    ");
    $stmt->execute([$usuario_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // se não existir parcela (caso raro), bloqueamos por segurança
    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Dados de parcelas não encontrados.']);
        exit;
    }

    // Se a primeira parcela ainda não foi paga (pago_em NULL), negar alteração
    if (empty($row['pago_em'])) {
        echo json_encode(['ok' => false, 'msg' => 'Alterações só permitidas após a primeira parcela ser registrada como paga.']);
        exit;
    }

    // OK — atualiza os campos permitidos
    $update = $pdo->prepare("
        UPDATE usuarios SET
            nome = ?, email = ?, telefone = ?, endereco = ?, unidade = ?, plano = ?
        WHERE id = ?
    ");

    $ok = $update->execute([
        $nome, $email, $telefone, $endereco,
        $unidade ?: null,
        $plano ?: null,
        $usuario_id
    ]);

    if ($ok) {
        // atualizar session (para refletir mudanças na UI)
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_email'] = $email;
        $_SESSION['telefone'] = $telefone;
        $_SESSION['endereco'] = $endereco;
        $_SESSION['unidade'] = $unidade;
        $_SESSION['plano_us'] = $plano;

        echo json_encode(['ok' => true, 'nome' => $nome]);
        exit;
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Falha ao atualizar.']);
        exit;
    }

} catch (Exception $e) {
    error_log("update_usuario.php error: " . $e->getMessage());
    echo json_encode(['ok' => false, 'msg' => 'Erro interno.']);
    exit;
}
