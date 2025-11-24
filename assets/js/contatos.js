document.addEventListener("DOMContentLoaded", () => {

    /* ===== ANIMAÇÃO DE ENTRADA ===== */
    const cards = document.querySelectorAll(".card-anim");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) e.target.classList.add("visible");
        });
    }, { threshold: 0.2 });

    cards.forEach(c => observer.observe(c));


    /* ===== POP-IN ===== */
    const modal = document.getElementById("contatoModal");
    const btnClose = document.getElementById("fecharContato");
    const btnOk = document.getElementById("contatoOk");
    const form = document.getElementById("contatoForm");

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        modal.style.display = "flex";
        form.reset();
    });

    btnClose.onclick = () => modal.style.display = "none";
    btnOk.onclick = () => modal.style.display = "none";

    window.onclick = (e) => {
        if (e.target === modal) modal.style.display = "none";
    };
});
