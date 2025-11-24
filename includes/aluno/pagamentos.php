<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../includes/db_connect.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    echo '<div class="section" data-section="pagamentos" style="display:none;"><p>Usuário não autenticado.</p></div>';
    return;
}

// Pega as próximas 12 parcelas (assume que tabela já tem as parcelas)
$stmt = $pdo->prepare("SELECT id, vencimento, valor, status, pago_em FROM parcelas WHERE usuario_id = ? ORDER BY vencimento ASC LIMIT 12");
$stmt->execute([$usuario_id]);
$parcelas = $stmt->fetchAll();

?>
<div class="section" data-section="pagamentos" style="display:none;">
<h2>ㅤㅤPagamentos</h2>

<div class="parcelas-grid">
    <?php
    $hoje = new DateTime('today');

    if (!$parcelas) {
        echo '<p>Sem parcelas registradas.</p>';
    } else {
        foreach ($parcelas as $p) {
            $venc = new DateTime($p['vencimento']);
            $classe = '';
            $label = '';
            $botao = false;

            if ($p['status'] === 'pago') {
                $classe = 'parcela-paga';
                $label = 'Pago em: ' . ($p['pago_em'] ? date('d/m/Y H:i', strtotime($p['pago_em'])) : '-');
            } else {
                // status pendente mas vencimento no futuro = aguardando
                if ($venc > $hoje) {
                    $classe = 'parcela-aguardando';
                    $label = 'Aguardando (vencimento: ' . $venc->format('d/m/Y') . ')';
                    $botao = false; // não pode pagar antes do vencimento
                } else {
                    // vencida hoje ou antes e não paga => pendente, pode pagar agora
                    $classe = 'parcela-pendente';
                    $label = 'Vencida: ' . $venc->format('d/m/Y');
                    $botao = true;
                }
            }

            echo '<div class="parcela-card '.$classe.'" data-parcela-id="'.htmlspecialchars($p['id']).'">';
            echo '<div class="parcela-info">';
            echo '<strong>Vencimento:</strong> ' . $venc->format('d/m/Y') . '<br>';
            echo '<strong>Valor:</strong> R$ ' . number_format($p['valor'], 2, ',', '.') . '<br>';
            echo '<small class="parcela-label">' . $label . '</small>';
            echo '</div>';

            if ($p['status'] !== 'pago') {
                if ($botao) {
                    echo '<div class="parcela-actions"><button class="btn-pagar" data-parcela-id="'.htmlspecialchars($p['id']).'">Pagar agora</button></div>';
                } else {
                    echo '<div class="parcela-actions"><button class="btn-aguardando" disabled> Aguardando </button></div>';
                }
            } else {
                echo '<div class="parcela-actions"><span class="badge-pago">Pago</span></div>';
            }

            echo '</div>';
        }
    }
    ?>
</div>
</div>