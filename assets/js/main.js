// assets/js/main.js

// ================== NAVBAR SCROLL ==================
window.addEventListener("scroll", () => {
  const navbar = document.querySelector(".navbar");
  if (!navbar) return;
  if (window.scrollY > 10) navbar.classList.add("scrolled");
  else navbar.classList.remove("scrolled");
});

// ================== CARROSSEL ==================
(function () {
  document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector(".carousel-track");
    const dotsContainer = document.querySelector(".carousel-dots");
    const dots = dotsContainer
      ? Array.from(dotsContainer.querySelectorAll(".dot"))
      : [];
    const items = track
      ? Array.from(track.querySelectorAll(".carousel-item"))
      : [];

    if (!track || !items.length) {
      console.warn(
        "Carrossel: elemento .carousel-track ou slides não encontrado."
      );
      return;
    }

    // Dinamicamente ajustar largura da track ao número real de slides
    const total = items.length;
    track.style.width = `${total * 100}%`;

    // Garante que cada item ocupe 100% da viewport do carrossel
    items.forEach((it) => {
      it.style.flex = "0 0 100%";
      it.style.maxWidth = "100%";
    });

    // Se os dots não foram criados no HTML, criamos automaticamente
    if (!dots.length && dotsContainer) {
      for (let i = 0; i < total; i++) {
        const btn = document.createElement("button");
        btn.className = `dot ${i === 0 ? "active" : ""}`;
        btn.dataset.index = i;
        btn.setAttribute("aria-label", `Slide ${i + 1}`);
        dotsContainer.appendChild(btn);
      }
    }

    // re-ler os dots (caso tenhamos criado)
    const finalDots = dotsContainer
      ? Array.from(dotsContainer.querySelectorAll(".dot"))
      : [];
    if (finalDots.length !== total) {
      console.warn(
        "Carrossel: número de dots diferente do número de slides. Ajustando automaticamente."
      );
    }

    // atribui data-index se estiver faltando
    finalDots.forEach((d, i) => {
      d.dataset.index = d.dataset.index ?? i;
    });

    let index = 0;
    let intervalId = null;
    const delay = 5000; // 7s

    function update() {
      // calcula deslocamento exato (100% por slide)
      const offset = -index * 100;
      track.style.transform = `translateX(${offset}%)`;
      finalDots.forEach((d, i) => d.classList.toggle("active", i === index));
    }

    function next() {
      index = (index + 1) % total;
      update();
    }

    function start() {
      stop();
      intervalId = setInterval(next, delay);
    }
    function stop() {
      if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
      }
    }

    // dots click
    finalDots.forEach((d) => {
      d.addEventListener("click", function () {
        const idx = parseInt(this.dataset.index, 10);
        if (!isNaN(idx)) {
          index = idx;
          update();
          start();
        }
      });
    });

    // pausa no hover
    const wrapper = document.querySelector(".carousel-wrapper");
    if (wrapper) {
      wrapper.addEventListener("mouseenter", stop);
      wrapper.addEventListener("mouseleave", start);
    }

    // debug imagens: exibe fontes e dimensão natural quando carregadas
    const imgs = track.querySelectorAll("img");
    imgs.forEach((img, i) => {
      if (img.complete) {
        console.log(
          `IMG[${i}] ready:`,
          img.src,
          img.naturalWidth,
          img.naturalHeight
        );
      } else {
        img.addEventListener("load", () => {
          console.log(
            `IMG[${i}] loaded:`,
            img.src,
            img.naturalWidth,
            img.naturalHeight
          );
        });
        img.addEventListener("error", () => {
          console.warn("Falha ao carregar imagem do carrossel:", img.src);
          img.style.opacity = "0.4";
        });
      }
    });

    // start
    update();
    start();
  });
})();

// ================== MOTIVACIONAIS (IntersectionObserver) ==================
(function () {
  document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".motiv-card");
    if (cards.length) {
      const obs = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) entry.target.classList.add("visible");
            else entry.target.classList.remove("visible");
          });
        },
        { threshold: 0.2 }
      );
      cards.forEach((c) => obs.observe(c));
    }
  });
})();

//================== REVEAL GLOBAL (textos & blocos) ==================
(function () {
  document.addEventListener("DOMContentLoaded", function () {
    const reveals = document.querySelectorAll(
      ".reveal, .reveal-section, .motiv-text, .motiv-galeria, .card, .detalhes-card"
    );
    if (!reveals.length) return;
    const obs = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) entry.target.classList.add("visible");
          else entry.target.classList.remove("visible");
        });
      },
      { threshold: 0.15 }
    );
    reveals.forEach((el) => {
      obs.observe(el);
      // Garante que comece visível se já estiver no viewport
      if (el.getBoundingClientRect().top < window.innerHeight) {
        el.classList.add('visible');
      }
    });

  });
})();

// ==================== VALIDAR SENHA ===========================
// Validação de senha e confirmação
function validatePasswords() {
  const senha = document.querySelector("#input_senha, #senha");
  const confirmarSenha = document.querySelector("#input_confirm_senha, #confirmar_senha");
  const btnCadastrar = document.getElementById("btn-cadastrar");
  const mensagem = document.querySelector(".password-message");

  if (!senha || !confirmarSenha || !mensagem) return;

  function validar() {
    const senhaValue = senha.value;
    const confirmarValue = confirmarSenha.value;

    // Limpa classes anteriores
    senha.classList.remove("error", "success");
    confirmarSenha.classList.remove("error", "success");
    mensagem.classList.remove("error", "success");

    // Se ambos os campos estiverem vazios, não mostra mensagem
    if (!senhaValue && !confirmarValue) {
      mensagem.textContent = "";
      return;
    }

    // Se as senhas não coincidem
    if (senhaValue !== confirmarValue) {
      senha.classList.add("error");
      confirmarSenha.classList.add("error");
      mensagem.textContent = "As senhas não coincidem";
      mensagem.classList.add("error");
      btnCadastrar.disabled = true;
    } else {
      senha.classList.add("success");
      confirmarSenha.classList.add("success");
      mensagem.textContent = "Senhas coincidem!";
      mensagem.classList.add("success");
      btnCadastrar.disabled = false;
    }
  }

  // Adiciona listeners para validação em tempo real
  senha.addEventListener("input", validar);
  confirmarSenha.addEventListener("input", validar);
}

// Funcionalidade de mostrar/ocultar senha
document.addEventListener("DOMContentLoaded", function () {
  const passwordFields = document.querySelectorAll(".password-field");

  // Inicializa a validação de senha
  validatePasswords();

  passwordFields.forEach((field) => {
    const input = field.querySelector("input");
    const button = field.querySelector(".password-toggle");
    if (!input || !button) return;

    const icon = button.querySelector("i");
    if (!icon) return;

    // Garante que o ícone inicial é o olho aberto
    icon.classList.remove("bi-eye-slash");
    icon.classList.add("bi-eye");

    button.addEventListener("click", function (e) {
      e.preventDefault();

      const isPassword = input.type === "password";
      input.type = isPassword ? "text" : "password";

      // Corrige a troca dos ícones
      if (isPassword) {
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
        icon.style.color = "limegreen"; // cor verde ao abrir
      } else {
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
        icon.style.color = "#ccc"; // volta ao cinza
      }

      input.focus();
    });
  });
});

// Máscara CPF e Telefone
document.addEventListener("DOMContentLoaded", () => {
  const cpf = document.querySelector("#input_cpf");
  const tel = document.querySelector("#input_telefone");
  const cep = document.querySelector("#input_cep");

  if (cpf) {
    cpf.addEventListener("input", () => {
      let v = cpf.value.replace(/\D/g, "");
      v = v.replace(/(\d{3})(\d)/, "$1.$2");
      v = v.replace(/(\d{3})(\d)/, "$1.$2");
      v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
      cpf.value = v;
    });
  }

  if (tel) {
    tel.addEventListener("input", () => {
      let v = tel.value.replace(/\D/g, "");
      v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
      v = v.replace(/(\d{5})(\d{4})$/, "$1-$2");
      tel.value = v;
    });
  }

  if (cep) {
    cep.addEventListener("input", () => {
      let v = cep.value.replace(/\D/g, "");
      v = v.replace(/^(\d{5})(\d)/, "$1-$2");
      cep.value = v;
    });
  }
});

// ========================== PÁGINA UNIDADES (NÃO SÃO OS CARDS DA PÁGINA PRINCIPAL) ================================
// assets/js/unidades.js
document.addEventListener('DOMContentLoaded', () => {
  // 1) Loader: remove após 700ms (0.7s) — mantendo breve visibilidade.
  const loader = document.getElementById('page-loader');
  if (loader) {
    setTimeout(() => {
      loader.classList.add('hidden');
      // opcional: remove do DOM após transição pra liberar
      setTimeout(() => loader.remove(), 1300);
    }, 700);
  }

  // 2) Scroll para unidade, se existe ?unidade=slug
  const params = new URLSearchParams(window.location.search);
  const unidade = params.get('unidade');
  if (unidade) {
    // espera pequena para garantir que elementos renderizem
    setTimeout(() => {
      const target = document.getElementById(unidade);
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        // highlight rápido: adiciona e remove classe visible para animação
        target.classList.add('visible');
      }
    }, 400); // 0.4s
  }

  // 3) Small reveal: marcar .unidade-card visíveis quando no viewport
  const cards = document.querySelectorAll('.unidade-card');
  if ('IntersectionObserver' in window && cards.length) {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
        else entry.target.classList.remove('visible');
      });
    }, { threshold: 0.12 });
    cards.forEach(c => obs.observe(c));
  } else {
    // fallback: mostra todos
    cards.forEach(c => c.classList.add('visible'));
  }
});
