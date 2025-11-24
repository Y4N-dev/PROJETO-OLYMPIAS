document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("pagamentoModal");
  const fecharModal = document.getElementById("fecharModal");
  const confirmarBtn = document.getElementById("confirmarPagamento");
  let parcelaSelecionada = null; // id

  // abrir modal quando clicar em "Pagar agora"
  document.addEventListener("click", (e) => {
    const pagarBtn = e.target.closest(".btn-pagar");
    if (!pagarBtn) return;
    e.preventDefault();
    parcelaSelecionada = pagarBtn.dataset.parcelaId;
    openModal();
  });

  function openModal() {
    if (!modal) return;
    modal.style.display = "flex";
    modal.setAttribute("aria-hidden", "false");
    // reset UI
    modal.querySelector(".loading-area").style.display = "none";
    modal.querySelector(".result-area").style.display = "none";
    confirmarBtn.style.display = "inline-block";
  }

  function closeModal() {
    if (!modal) return;
    modal.style.display = "none";
    modal.setAttribute("aria-hidden", "true");
    parcelaSelecionada = null;
  }

  if (fecharModal) fecharModal.addEventListener("click", closeModal);

  // confirmar pagamento (sempre sucesso — alteração solicitada)
  if (confirmarBtn) {
    confirmarBtn.addEventListener("click", () => {
      confirmarBtn.style.display = "none";
      const loading = modal.querySelector(".loading-area");
      const result = modal.querySelector(".result-area");
      loading.style.display = "block";

      // simulação de processamento
      setTimeout(() => {
        loading.style.display = "none";
        result.style.display = "block";
        result.querySelector(".result-msg").textContent = "Pagamento bem-sucedido!";
        result.querySelector(".result-icon").className = "result-icon result-success";

        // chama endpoint para marcar como pago no banco
        if (parcelaSelecionada) {
          fetch('actions/pagar_parcela.php', {
            method: 'POST',
            body: JSON.stringify({ id: parcelaSelecionada }),
            headers: { 'Content-Type': 'application/json' }
          }).then(r => r.json()).then(json => {
            // dentro do then(json => { ... })
            if (json.ok) {

                /* 🔔 NOTIFICAÇÃO IMEDIATA */
                if (window.appNotifs) {
                    window.appNotifs.push({
                        titulo: "Pagamento realizado",
                        texto: "Seu pagamento foi confirmado com sucesso!"
                    });
                }

                const card = document.querySelector(`.parcela-card[data-parcela-id="${parcelaSelecionada}"]`);
                if (card) {
                    card.classList.remove('parcela-pendente','parcela-aguardando');
                    card.classList.add('parcela-paga');
                    const actions = card.querySelector('.parcela-actions');
                    if (actions) actions.innerHTML = '<span class="badge-pago">Pago</span>';
                    updateDashboardPendencias();
                }
            }
            if (json.ok) {
              // atualizar UI: trocar cartão para 'Pago' e remover botão
              const card = document.querySelector(`.parcela-card[data-parcela-id="${parcelaSelecionada}"]`);
              if (card) {
                card.classList.remove('parcela-pendente','parcela-aguardando');
                card.classList.add('parcela-paga');
                const actions = card.querySelector('.parcela-actions');
                if (actions) actions.innerHTML = '<span class="badge-pago">Pago</span>';
                // atualizar contador no dashboard (faz nova requisição opcional ou decrementa localmente)
                updateDashboardPendencias();
              }
            } else {
              result.querySelector(".result-msg").textContent = "Erro: " + (json.msg || 'não foi possível registrar');
              result.querySelector(".result-icon").className = "result-icon result-failure";
            }
          }).catch(err => {
            result.querySelector(".result-msg").textContent = "Erro de rede";
            result.querySelector(".result-icon").className = "result-icon result-failure";
          }).finally(() => {
            // fecha modal após 1.5s
            setTimeout(closeModal, 1500);
          });
        } else {
          setTimeout(closeModal, 1200);
        }
      }, 1100);
    });
  }

  function updateDashboardPendencias() {
    // opção simples: recarrega via fetch um pedaço do dashboard (mais robusto seria endpoint)
    // Aqui vamos recarregar a página pequena (poderia ser otimizado)
    fetch(window.location.href, { method: 'GET' })
      .then(r => r.text())
      .then(html => {
        // extrair trecho do dashboard (simples regex)
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const novoDash = doc.querySelector('.section[data-section="dashboard"]');
        const atual = document.querySelector('.section[data-section="dashboard"]');
        if (novoDash && atual) atual.innerHTML = novoDash.innerHTML;
      }).catch(()=>{/* sem falha visível */});
  }

  // fechar modal se clicar fora da caixa
  window.addEventListener('click', (e) => {
    if (!modal) return;
    if (e.target === modal) closeModal();
  });

  // link "Ir para Pagamentos" (na aba pendencias)
  document.addEventListener("click", (e) => {
    const a = e.target.closest(".ir-para-pagamento");
    if (!a) return;
    e.preventDefault();
    const id = a.dataset.parcela;
    // abre tab pagamentos
    document.querySelectorAll('.sidebar nav ul li').forEach(li => li.classList.remove('active'));
    const liPag = document.querySelector('.sidebar nav ul li[data-section="pagamentos"]');
    if (liPag) liPag.classList.add('active');

    // mostra section correta
    document.querySelectorAll('.main-content .section').forEach(s => s.style.display = 'none');
    const sec = document.querySelector('.main-content .section[data-section="pagamentos"]');
    if (sec) sec.style.display = 'block';
    // rola para a parcela
    setTimeout(()=> {
      const card = document.querySelector(`.parcela-card[data-parcela-id="${id}"]`);
      if (card) card.scrollIntoView({behavior:'smooth', block:'center'});
    }, 200);
  });

});
