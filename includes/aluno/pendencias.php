<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../includes/db_connect.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    echo '<div class="section" data-section="pendencias" style="display:none;"><p>Usuário não autenticado.</p></div>';
    return;
}

// Busca apenas parcelas vencidas e não pagas
$stmt = $pdo->prepare("SELECT id, vencimento, valor FROM parcelas WHERE usuario_id = ? AND status = 'pendente' AND vencimento <= CURRENT_DATE() ORDER BY vencimento ASC");
$stmt->execute([$usuario_id]);
$pendentes = $stmt->fetchAll();
?>
<div class="section" data-section="pendencias" style="display:none;">
  <!--<h2>Pendências Encontradas</h2>-->

  <?php if (!$pendentes): ?>
    <div class="pend-card message-card">
      <div class="icon">⚠</div>
      <h2>Nenhuma Pendência</h2>
      <p>Não há pendências no momento.</p>
    </div>
  <?php else: ?>
    <div class="pendencias-list">
      <?php foreach ($pendentes as $p): ?>
        <div class="pend-item">
          <h3>Parcela Pendente</h3>
          <p><strong>Vencimento:</strong> <?= (new DateTime($p['vencimento']))->format('d/m/Y') ?></p>
          <p><strong>Valor:</strong> R$ <?= number_format($p['valor'],2,',','.') ?></p>
          <p><a href="#" class="ir-para-pagamento" data-parcela="<?= htmlspecialchars($p['id']) ?>">Ir para Pagamentos</a></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>