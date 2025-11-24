<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db_connect.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    echo '<div class="section" data-section="dashboard" style="display:block;"><p>Usuário não autenticado.</p></div>';
    return;
}

/* =====================================
   BUSCA DE PENDÊNCIAS REAIS DO USUÁRIO
   ===================================== */
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM parcelas 
    WHERE usuario_id = ? 
      AND status = 'pendente'
      AND vencimento <= CURRENT_DATE()
");
$stmt->execute([$usuario_id]);
$qtd_pend = intval($stmt->fetchColumn() ?? 0); ?>
<?php if ($qtd_pend > 0): ?>
<script>
if (window.appNotifs) {
    window.appNotifs.push({
        titulo: "Pagamento Pendente",
        texto: "Você possui parcela(s) vencida(s). Regularize para liberar os treinos."
    });
}
</script>
<?php endif; ?>
<?php
/* =====================================
   FREQUÊNCIA SEMANAL (SIMULAÇÃO)
   ===================================== */
$semana = [
    "SEG", "TER", "QUA", "QUI", "SEX", "SÁB", "DOM"
];

$freq = []; // cada item será: ['dia' => 'SEG', 'valor' => 0..100, 'cor' => 'fb-xxx']

foreach ($semana as $dia) {
    $valor = rand(5, 100); // frequência aleatória
    if ($valor <= 30)       $cor = "fb-red";
    else if ($valor <= 60)  $cor = "fb-yellow";
    else                    $cor = "fb-green";

    $freq[] = [
        'dia'   => $dia,
        'valor' => $valor,
        'cor'   => $cor
    ];
}

?>
<div class="section" data-section="dashboard" style="display:block;">

    <!-- BOAS-VINDAS -->
    <div class="dash-card welcome-card">
        <h2>Bem-vindo, <span><?= htmlspecialchars(strtoupper($_SESSION['usuario_nome'])) ?></span>!</h2>
        <p>ID do aluno: <strong><?= htmlspecialchars($_SESSION['usuario_id']) ?></strong></p>
        <p>Último acesso: <strong><?= date("d/m/Y") ?></strong></p>
    </div>

    <!-- PLANO -->
    <div class="dash-card plan-card">
        <h3>Seu Plano Atual</h3>
        <p>Plano: <strong><?= htmlspecialchars($_SESSION['plano_us'] ?? "Não definido") ?></strong></p>
        <p>Renovação: <strong>Em breve</strong></p>
        <p>Pendências: 
            <strong style="color:<?= $qtd_pend > 0 ? 'red' : '#7fff7f' ?>;">
                <?= $qtd_pend ?> parcela(s)
            </strong>
        </p>
    </div>

    <!-- FREQUÊNCIA SEMANAL -->
    <div class="dash-card freq-card">
        <h3>Frequência Semanal</h3>

        <div class="freq-bars">
            <?php foreach ($freq as $item): ?>
                <div class="freq-col">
                    <div class="freq-bar">
                        <div class="freq-bar-fill <?= $item['cor'] ?>" style="height: <?= $item['valor'] ?>%;"></div>
                    </div>
                    <div class="freq-label"><?= $item['dia'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <p style="font-size:0.85rem; color:#999; margin-top:8px;">
            *Simulação automática — atualiza a cada visita
        </p>
    </div>

</div>
