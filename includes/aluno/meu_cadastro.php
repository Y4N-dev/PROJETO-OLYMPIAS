<div class="section" data-section="meu_cadastro" style="display:none;">

    <div class="mc-wrapper">

        <h2 class="mc-title">ㅤㅤInformações do Aluno</h2>

        <!-- CARD 1 — Dados pessoais -->
        <div class="mc-card">
            <h3>Dados Pessoais</h3>

            <div class="mc-row">
                <span class="label">Nome:</span>
                <span class="value"><?= strtoupper($_SESSION['usuario_nome']); ?></span>
            </div>

            <div class="mc-row">
                <span class="label">Data de Nascimento:</span>
                <span class="value"><?= $_SESSION['data_nascimento']; ?></span>
            </div>

            <div class="mc-row">
                <span class="label">CPF:</span>
                <span class="value"><?= $_SESSION['cpf']; ?></span>
            </div>

            <div class="mc-row">
                <span class="label">E-mail:</span>
                <span class="value"><?= $_SESSION['usuario_email']; ?></span>
            </div>

            <div class="mc-row">
                <span class="label">Telefone:</span>
                <span class="value"><?= $_SESSION['telefone']; ?></span>
            </div>

            <p><strong>Gênero:</strong> <?= htmlspecialchars($_SESSION['genero'] ?? 'Não informado') ?></p>

        </div>

        <!-- CARD 2 — Endereço -->
        <div class="mc-card">
            <h3>Endereço</h3>

            <div class="mc-row">
                <span class="label">Cidade:</span>
                <span class="value"><?= $_SESSION['cidade']; ?></span>
            </div>

            <div class="mc-row">
                <span class="label">CEP:</span>
                <span class="value"><?= $_SESSION['cep']; ?></span>
            </div>

            <div class="mc-row">
                <span class="label">Endereço Completo:</span>
                <span class="value"><?= $_SESSION['endereco']; ?></span>
            </div>
        </div>

        <!-- CARD 3 — Academia & Plano -->
        <div class="mc-card">
            <h3>Informações da Academia</h3>

            <div class="mc-row">
                <span class="label">Unidade:</span>
                <span class="value"><?= strtoupper($_SESSION['unidade']); ?></span>
            </div>

            <div class="mc-row">
                <span class="label">Plano Atual:</span>
                <span class="value"><?= strtoupper($_SESSION['plano_us']); ?></span>
            </div>

            <div class="mc-row">
                <span class="label">Status do Pagamento:</span>
                <span class="value green">Ativo</span>
            </div>
        </div>

    </div>

</div>
