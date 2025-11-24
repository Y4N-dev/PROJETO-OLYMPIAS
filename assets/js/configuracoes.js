document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("formConfig");
    const btnExcluir = document.getElementById("btnExcluir");

    const pop = document.getElementById("popExcluir");
    const fecharPop = document.getElementById("fecharPop");
    const cancelarExcluir = document.getElementById("cancelarExcluir");
    const confirmarExcluir = document.getElementById("confirmarExcluir");

    /* =============================
       SALVAR ALTERAÇÕES
    ============================= */
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const dados = new FormData(form);

            fetch("actions/salvar_configuracoes.php", {
                method: "POST",
                body: dados
            })
            .then(r => r.json())
            .then(res => {
                if (res.ok) {
                    alert("Alterações salvas com sucesso!");
                    location.reload();
                } else {
                    alert("Erro: " + res.msg);
                }
            })
            .catch(() => alert("Erro de rede."));
        });
    }


    /* =============================
       POPIN DE EXCLUSÃO
    ============================= */
    btnExcluir.addEventListener("click", () => {
        pop.style.display = "flex";
    });

    fecharPop.addEventListener("click", () => {
        pop.style.display = "none";
    });

    cancelarExcluir.addEventListener("click", () => {
        pop.style.display = "none";
    });

    // === EXCLUSÃO DE CONTA ===
    confirmar.addEventListener("click", () => {
        fetch("actions/excluir_conta.php", {
            method: "POST"
        })
        .then(r => r.json())
        .then(json => {
            if (json.ok) {
                window.location.href = "index.php"; // volta à tela inicial
            } else {
                alert("Erro: " + json.msg);
            }
        })
        .catch(() => alert("Erro de rede"));
    });

});
