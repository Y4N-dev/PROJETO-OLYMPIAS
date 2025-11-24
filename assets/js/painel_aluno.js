// assets/js/painel_aluno.js
// PAINEL DO ALUNO — NAVEGAÇÃO LATERAL + TOGGLE + ANIMAÇÃO
// Versão limpa, atual e compatível com o painel REAL.

document.addEventListener("DOMContentLoaded", function () {

  // ----- ELEMENTOS IMPORTANTES -----
  const sidebarItems = document.querySelectorAll(".sidebar nav ul li[data-section]");
  const sections = document.querySelectorAll(".main-content .section");
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const body = document.body;

  // Animação da navbar
  const primary = document.getElementById("animPrimary");
  const secondary = document.getElementById("animSecondary");

  // ----- NAVEGAÇÃO POR <li data-section="..."> -----
  if (sidebarItems.length && sections.length) {
    sidebarItems.forEach(item => {
      item.addEventListener("click", () => {

        // Troca destaque do item selecionado
        sidebarItems.forEach(i => i.classList.remove("active"));
        item.classList.add("active");

        // Mostra apenas a section correspondente
        const target = item.dataset.section;
        sections.forEach(sec => {
          sec.style.display = (sec.dataset.section === target) ? "block" : "none";
        });

        // Logout
        if (target === "logout") {
          window.location = "actions/logout.php";
        }
      });
    });
  }
  // ----- BOTÕES INTERNOS QUE TAMBÉM NAVEGAM ENTRE AS ABAS -----
  const internalButtons = document.querySelectorAll("button[data-section]");

  internalButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      
      const target = btn.dataset.section;

      // Trocar seção visível
      sections.forEach(sec => {
        sec.style.display = (sec.dataset.section === target) ? "block" : "none";
      });

      // Atualizar destaque da sidebar
      sidebarItems.forEach(item => {
        if (item.dataset.section === target) {
          item.classList.add("active");
        } else {
          item.classList.remove("active");
        }
      });

      // Fechar sidebar em mobile (opcional)
      sidebar.classList.remove("active");
      body.classList.remove("sidebar-open");
      menuToggle.classList.remove("active");
    });
    });


  // ----- TOGGLE DA SIDEBAR (menu hambúrguer) -----
  if (menuToggle) {
    menuToggle.addEventListener("click", function () {
      this.classList.toggle("active");
      if (sidebar) sidebar.classList.toggle("active");
      body.classList.toggle("sidebar-open");
    });
  }

  // ----- ANIMAÇÃO DO TEXTO (NAVBAR) -----
  if (primary && secondary) {
    let showingSecondary = false;

    function swapTexts() {
      if (!showingSecondary) {
        primary.style.transform = "translateY(-40px)";
        primary.style.opacity = "0";
        secondary.style.transform = "translateY(0)";
        secondary.style.opacity = "1";
      } else {
        secondary.style.transform = "translateY(40px)";
        secondary.style.opacity = "0";
        primary.style.transform = "translateY(0)";
        primary.style.opacity = "1";
      }
      showingSecondary = !showingSecondary;
    }

    // estado inicial
    primary.style.transform = "translateY(0)";
    primary.style.opacity = "1";
    secondary.style.transform = "translateY(40px)";
    secondary.style.opacity = "0";

    // primeira troca em 5s, depois a cada 10s
    setTimeout(swapTexts, 5000);
    setInterval(swapTexts, 10000);
  }

  /* --- Simulação de frequência semanal /Dashboard.php --- */
  const barras = document.querySelectorAll('.section[data-section="dashboard"] .progress-card .bar');

  barras.forEach(bar => {
      const altura = 20 + Math.random() * 60; // 20px a 80px
      bar.style.height = altura + "px";
      bar.style.background = "#0b7300";
  });
});

/* ========== LÓGICA DINÂMICA DA ABA HORÁRIOS ========== */
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".horario-card");

    if (!cards.length) return;

    // Chance de 1/10 para feriado (fechado)
    if (Math.random() < 0.1) {
        const feriadoCard = cards[Math.floor(Math.random() * cards.length)];
        aplicarStatusEspecial(feriadoCard, "feriado", "Fechado (feriado)");
    }

    // Chance de 1/15 para manutenção
    if (Math.random() < 0.0666) {
        const manutCard = cards[Math.floor(Math.random() * cards.length)];
        aplicarStatusEspecial(manutCard, "manutencao", "Manutenção");
    }

    function aplicarStatusEspecial(card, classe, texto) {
        const status = card.querySelector(".status");
        const statusText = card.querySelector(".status-text");

        // altera status
        status.className = `status ${classe}`;
        statusText.textContent = texto;

        // bloqueia horários
        const horas = card.querySelectorAll(".hora");
        horas.forEach(h => h.classList.add("bloqueada"));

        const spans = card.querySelectorAll(".hora span");
        spans.forEach(s => (s.textContent = "-----"));
    }
});
document.addEventListener("DOMContentLoaded", () => {

    const links = document.querySelectorAll(".painel-sidebar a");
    const sections = document.querySelectorAll(".section");

    function abrirAba(sec) {
        sections.forEach(s => s.style.display = "none");
        sec.style.display = "block";

        // Soft-refresh suave (150 ms)
        setTimeout(() => {
            sec.dispatchEvent(new Event("refreshAba"));
        }, 150);
    }

    links.forEach(a => {
        a.addEventListener("click", e => {
            e.preventDefault();

            const alvo = a.getAttribute("data-target");
            const sec = document.querySelector(`.section[data-section="${alvo}"]`);

            if (sec) abrirAba(sec);
        });
    });

    // Abre dashboard por padrão
    abrirAba(document.querySelector('.section[data-section="dashboard"]'));
});
