<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../includes/db_connect.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    echo '<div class="section" data-section="configuracoes" style="display:block;">
            <p>Usuário não autenticado.</p>
          </div>';
    return;
}

/* ================================
   VERIFICAR SE A PRIMEIRA PARCELA ESTÁ PAGA
   ================================ */
$stmt = $pdo->prepare("
    SELECT status 
    FROM parcelas 
    WHERE usuario_id = ?
    ORDER BY id ASC
    LIMIT 1
");
$stmt->execute([$usuario_id]);
$primeira = $stmt->fetch(PDO::FETCH_ASSOC);

// Se a primeira parcela existir e tiver status 'pago', libera edição
$permissaoEdicao = ($primeira && $primeira['status'] === 'pago');

// Variável para bloquear inputs / selects
$disabled = $permissaoEdicao ? "" : "disabled";


/* ================================
   BUSCAR DADOS ATUAIS DO USUÁRIO
   ================================ */
$stmt = $pdo->prepare("SELECT nome, email, telefone, endereco, unidade, plano FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    echo "<p>Erro ao carregar dados.</p>";
    return;
}

$nome_atual      = $dados['nome'];
$email_atual     = $dados['email'];
$telefone_atual  = $dados['telefone'];
$endereco_atual  = $dados['endereco'];
$unidade_atual   = $dados['unidade']; 
$plano_atual     = $dados['plano'];


/* ================================
   BUSCAR UNIDADES DISPONÍVEIS
   ================================ */
$unidades = $pdo->query("SELECT id, nome FROM academias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="section" data-section="configuracoes" style="display:none;">
    <h2 class="config-title">ㅤㅤConfigurações da Conta</h2>

    <?php if (!$permissaoEdicao): ?>
        <p class="aviso-edicao" style="
            color:#ff6666;
            text-align:center;
            background:#3a1f1f;
            padding:10px;
            border-radius:8px;
            margin-bottom:20px;
            border:1px solid #aa3333;
        ">
            ⚠️ Para alterar seus dados, você precisa quitar a primeira parcela.
        </p>
    <?php endif; ?>

    <form id="formConfig" class="config-form">

        <!-- NOME -->
        <label class="cfg-label">Nome Completo</label>
        <input type="text" name="nome" class="cfg-input" value="<?= htmlspecialchars($nome_atual) ?>" required <?= $disabled ?>>

        <!-- EMAIL -->
        <label class="cfg-label">E-mail</label>
        <input type="email" name="email" class="cfg-input" value="<?= htmlspecialchars($email_atual) ?>" required <?= $disabled ?>>

        <!-- TELEFONE -->
        <label class="cfg-label">Telefone</label>
        <input type="text" name="telefone" class="cfg-input" value="<?= htmlspecialchars($telefone_atual) ?>" required <?= $disabled ?>>

        <!-- ENDEREÇO -->
        <label class="cfg-label">Endereço Completo</label>
        <input type="text" name="endereco" class="cfg-input" value="<?= htmlspecialchars($endereco_atual) ?>" required <?= $disabled ?>>

        <!-- UNIDADE -->
        <label class="cfg-label">Unidade</label>
        <select name="unidade" class="cfg-select" required <?= $disabled ?>>
            <?php foreach ($unidades as $u): ?>
                <?php $sel = ($u['id'] == $unidade_atual) ? "selected" : ""; ?>
                <option value="<?= $u['id'] ?>" <?= $sel ?>>
                    <?= htmlspecialchars($u['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- PLANO -->
        <label class="cfg-label">Plano</label>
        <select name="plano" class="cfg-select" required <?= $disabled ?>>
            <option value="mensal" <?= ($plano_atual === 'mensal' ? 'selected' : '') ?>>Mensal</option>
            <option value="anual"  <?= ($plano_atual === 'anual'  ? 'selected' : '') ?>>Anual</option>
        </select>

        <?php if ($permissaoEdicao): ?>
            <button type="submit" class="cfg-salvar-btn">Salvar Alterações</button>
        <?php endif; ?>
    </form>

    <!-- EXCLUIR CONTA -->
    <button id="btnExcluir" class="cfg-excluir-btn">Excluir Inscrição</button>

    <!-- POPIN DE CONFIRMAÇÃO -->
    <div id="popExcluir" class="config-popin">
        <div class="config-popin-box">
            <span id="fecharPop" class="close-popin">×</span>

            <h3>Tem certeza?</h3>
            <p>Deseja excluir sua conta permanentemente? Esta ação não pode ser desfeita.</p>

            <button id="confirmarExcluir" class="popin-confirmar">Sim, excluir</button>
            <button id="cancelarExcluir" class="popin-cancelar">Cancelar</button>
        </div>
    </div>

</div>

<script src="assets/js/configuracoes.js"></script>
