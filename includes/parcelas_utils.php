<?php
// includes/parcelas_utils.php
function manterParcelasUsuario(PDO $pdo, $userId) {
    // 1) deletar usuário se >= 3 parcelas vencidas
    $stmt = $pdo->prepare("SELECT COUNT(*) AS qtd FROM parcelas WHERE usuario_id = ? AND status = 'pendente' AND vencimento < CURDATE()");
    $stmt->execute([$userId]);
    $q = (int)$stmt->fetchColumn();
    if ($q >= 3) {
        // deletar usuário (cascata remove parcelas)
        $del = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $del->execute([$userId]);
        return ['deleted' => true];
    }

    // 2) Garantir sempre 12 parcelas futuras (incluindo a atual)
    // Conta quantas parcelas futuras (vencimento >= hoje) existem
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM parcelas WHERE usuario_id = ? AND vencimento >= CURDATE()");
    $stmt->execute([$userId]);
    $count = (int)$stmt->fetchColumn();

    if ($count < 12) {
        // buscar ultima parcela futura e adicionar até totalizar 12
        $stmt = $pdo->prepare("SELECT MAX(vencimento) FROM parcelas WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        $last = $stmt->fetchColumn();
        $start = $last ? new DateTime($last) : new DateTime(); // se null, comece hoje
        // move um mês pra frente antes de inserir
        for ($i = 0; $i < (12 - $count); $i++) {
            $start->modify('+1 month');
            // garantir dia de cobrança: usar DAY from first parcel or from usuarios.data_inicio
            // aqui tentamos manter mesmo dia:
            $venc = $start->format('Y-m-d');
            $insert = $pdo->prepare("INSERT INTO parcelas (usuario_id, vencimento, valor, status) VALUES (?, ?, ?, 'pendente')");
            // valor - poderia buscar do usuario -> $pdo->query...
            $valor = 99.90;
            $insert->execute([$userId, $venc, $valor]);
        }
    }

    return ['deleted' => false];
}
