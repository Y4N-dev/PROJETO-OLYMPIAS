document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".cadastro-form");
  const modal = document.getElementById("pagamentoModal");
  if (!modal) return;  // <--- impede execução fora de pagamentos
  const confirmBtn = document.getElementById("confirmarPagamento");
  const loadingArea = document.querySelector(".loading-area");
  const resultArea = document.querySelector(".result-area");
  const resultIcon = document.querySelector(".result-icon");
  const resultMsg = document.querySelector(".result-msg");

  form.addEventListener("submit", (e) => {
    e.preventDefault(); // bloqueia envio real

    // abre o pop-in
    modal.style.display = "flex";
  });

  confirmBtn.addEventListener("click", () => {
    confirmBtn.style.display = "none";
    loadingArea.style.display = "block";

    setTimeout(() => {
      loadingArea.style.display = "none";
      resultArea.style.display = "block";

      const sucesso = Math.random() < 14 / 15; // 14 em 15 chances
      resultIcon.className = "result-icon " + (sucesso ? "result-success" : "result-failure");
      resultMsg.textContent = sucesso
        ? "Pagamento bem-sucedido!"
        : "Pagamento recusado, tente novamente.";

      if (sucesso) {
        setTimeout(() => {
          modal.style.display = "none";
          form.submit(); // agora envia o cadastro real
        }, 2000);
      } else {
        setTimeout(() => {
          modal.style.display = "none";
          window.location.href = "index.php";
        }, 2000);
      }
    }, 3000); // tempo de verificação
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("pagamentoModal");
  const fecharModal = document.getElementById("fecharModal");

  if (fecharModal) {
    fecharModal.addEventListener("click", () => {
      modal.style.display = "none";
    });
  }
});
