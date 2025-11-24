<?php
// includes/popin_pagamento.php
?>
<div id="pagamentoModal" class="modal-overlay" aria-hidden="true" style="display:none;">
  <div class="modal-box">
    <button id="fecharModal" class="modal-close" aria-label="Fechar pop-in">&times;</button>
    <h3 class="modal-title">Pague o boleto para concluir o pagamento</h3>
    <div class="qr-container">
      <img src="assets/img/fake_qr.png" alt="QR Code para pagamento" class="qr-fake">
    </div>

    <div class="loading-area" style="display:none;">
      <div class="spinner"></div>
      <p class="status-msg">Processando pagamento...</p>
    </div>

    <div class="result-area" style="display:none;">
      <div class="result-icon"></div>
      <p class="result-msg"></p>
    </div>

    <div class="modal-actions">
      <button id="confirmarPagamento" class="modal-btn">Confirmar pagamento</button>
    </div>
  </div>
</div>
