<div class="section" data-section="suporte" style="display:none;">

    <h2 class="sup-title">Central de Suporte</h2>

    <div class="sup-card">

        <p class="sup-desc">
            Selecione até <strong>10 motivos</strong> e descreva sua situação, nossa equipe irá analisar.
        </p>

        <form id="formSuporte">

            <div class="sup-options">

                <!-- 9 opções padrão -->
                <?php
                $opcoes = [
                    "Problemas com pagamentos", 
                    "Erro nos treinos", 
                    "Problemas no cadastro",
                    "Alteração de plano não funcionou",
                    "Agendamentos com falha",
                    "Horários inconsistentes",
                    "Aplicação muito lenta",
                    "Informações incorretas",
                    "Notificações não aparecem"
                ];

                foreach ($opcoes as $i => $txt): ?>
                    <label class="sup-item">
                        <input type="checkbox" name="motivo[]" value="<?= $txt ?>">
                        <span><?= $txt ?></span>
                    </label>
                <?php endforeach; ?>

                <!-- 10ª opção — Outro -->
                <label class="sup-item">
                    <input type="checkbox" id="supOutro" name="motivo[]" value="Outro">
                    <span>Outro</span>
                </label>

            </div>

            <!-- Campo extra aparece apenas se marcar "Outro" -->
            <textarea id="supTextoExtra" class="sup-textarea" placeholder="Descreva melhor o que aconteceu..." style="display:none;"></textarea>

            <button type="submit" class="sup-btn">Enviar</button>

        </form>

    </div>

    <!-- POPIN DE SUCESSO -->
    <div id="supPop" class="sup-pop-overlay">
        <div class="sup-pop-box">
            <button id="supPopClose" class="sup-pop-close">&times;</button>
            <h3>Mensagem enviada!</h3>
            <p>Sua solicitação foi registrada. Nossa equipe responderá em <strong>Notificações</strong>.</p>
            <button id="supPopOk" class="sup-pop-ok">OK</button>
        </div>
    </div>

</div>
