// SUPORTE – lógica do formulário e popin

document.addEventListener("DOMContentLoaded", () => {

    const chkOutro = document.getElementById("supOutro");
    const txtExtra = document.getElementById("supTextoExtra");
    const form = document.getElementById("formSuporte");

    const pop = document.getElementById("supPop");
    const popClose = document.getElementById("supPopClose");
    const popOk = document.getElementById("supPopOk");

    // Mostrar textarea quando marcar "Outro"
    if (chkOutro) {
        chkOutro.addEventListener("change", () => {
            txtExtra.style.display = chkOutro.checked ? "block" : "none";
        });
    }

    // Envio do formulário (simulado)
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            // apenas simulação
            pop.style.display = "flex";

            // limpar campos
            form.reset();
            txtExtra.style.display = "none";
        });
    }

    // Fechar popin
    popClose.addEventListener("click", () => pop.style.display = "none");
    popOk.addEventListener("click", () => pop.style.display = "none");

    // Fechar clicando fora
    window.addEventListener("click", (e) => {
        if (e.target === pop) pop.style.display = "none";
    });

});
