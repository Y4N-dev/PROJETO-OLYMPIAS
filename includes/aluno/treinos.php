<div class="section" data-section="treinos" style="display:none;">

    <?php
    require_once __DIR__ . '/../../includes/db_connect.php';

    // ===== VERIFICAR PARCELAS VENCIDAS =====
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM parcelas 
        WHERE usuario_id = ?
            AND status = 'pendente'
            AND vencimento <= CURDATE()
    ");
    $stmt->execute([$_SESSION['usuario_id']]);
    $pendenciasVencidas = (int) $stmt->fetchColumn();

    if ($pendenciasVencidas > 0) {
        echo '
            <div class="pendencia-aviso" style="
                background:#331a1a;
                padding:20px;
                border-radius:10px;
                border:2px solid #ff4444;
                color:#ff9999;
                margin:25px;
                margin-top: 30px;
                margin-left: 20px;
                margin-right: 20px;
                margin-bottom: 20px;
                text-align:center;
                font-size:1.1rem;">
                ⚠ <strong>Você possui pendências financeiras.</strong><br>
                Regularize para liberar seus treinos.
            </div>
        ';
        return; // Impede o restante da aba de ser carregado
    }
    ?>

    <!-- CABEÇALHO NORMAL DA ABA DE TREINOS -->
    <h2 class="titulo-treinos">ㅤㅤMeu Plano de Treinos</h2>

    <!-- SELETOR DE DIFICULDADE -->
    <div class="treino-controles">
        <label>Dificuldade:</label>

        <select id="treinoNivel">
            <option value="iniciante">Iniciante</option>
            <option value="intermediario" selected>Intermediário</option>
            <option value="avancado">Avançado</option>
        </select>
    </div>

    <!-- GRID DE TREINOS -->
    <div id="treinosGrid" class="treinos-grid"></div>

    <!-- BOTÃO GERAR NOVO TREINO -->
    <button id="gerarNovoTreino" class="novo-treino-btn">
        Gerar novo treino
    </button>

    <script>
        const generoUsuario = "<?= strtolower($_SESSION['genero'] ?? 'outro') ?>";
    </script>

    <script src="assets/js/treinos.js"></script>
</div>