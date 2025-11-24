document.addEventListener("DOMContentLoaded", () => {

    const grid = document.getElementById("treinosGrid");
    const nivelSelect = document.getElementById("treinoNivel");
    const btnNovo = document.getElementById("gerarNovoTreino");

    const dificuldade = {
        iniciante: { series: "-1", reps: "-3", descanso: "90–120s", duracao: "40–55 min" },
        intermediario: { series: "+0", reps: "+0", descanso: "60–90s", duracao: "50–70 min" },
        avancado: { series: "+1", reps: "+3", descanso: "45–75s", duracao: "60–85 min" }
    };

    const superior = {
        "Peito + Tríceps": [
            ["Supino reto", 4, 12],
            ["Supino inclinado", 3, 10],
            ["Crucifixo", 3, 15],
            ["Tríceps pulley", 4, 12],
            ["Tríceps testa", 3, 10]
        ],
        "Costas + Bíceps": [
            ["Puxada alta", 4, 10],
            ["Remada curvada", 3, 12],
            ["Serrote", 3, 12],
            ["Rosca direta", 4, 10],
            ["Rosca alternada", 3, 12]
        ],
        "Ombro + Trapézio": [
            ["Desenvolvimento", 4, 10],
            ["Elevação lateral", 3, 15],
            ["Elevação frontal", 3, 12],
            ["Encolhimento", 4, 12]
        ]
    };

    const inferior = {
        "Perna Completa": [
            ["Agachamento livre", 4, 10],
            ["Leg press", 4, 12],
            ["Cadeira extensora", 3, 15],
            ["Mesa flexora", 3, 12],
            ["Panturrilha", 4, 15]
        ],
        "Quadríceps": [
            ["Agachamento frontal", 4, 10],
            ["Extensora", 4, 12],
            ["Leg press", 3, 12],
            ["Passada", 3, 20]
        ],
        "Posterior + Glúteo": [
            ["Stiff", 4, 10],
            ["Mesa flexora", 4, 12],
            ["Glúteo máquina", 3, 15],
            ["Elevação pélvica", 4, 12]
        ]
    };

    const dias = ["Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"];

    const STORAGE_KEY = "treinos_concluidos_7dias";

    function carregarConcluidos() {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
    }

    function salvarConcluidos(data) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

    let concluidos = carregarConcluidos();

    function gerarTreino() {
        let ordem = [];

        if (generoUsuario === "feminino") {
            ordem = ["inferior", "superior", "inferior", "superior", "inferior"];
        } else {
            ordem = ["superior", "inferior", "superior", "inferior", "superior"];
        }

        const nivel = dificuldade[nivelSelect.value];
        const plano = [];

        for (let i = 0; i < 5; i++) {
            const grupo = ordem[i] === "superior" ? superior : inferior;
            const nomeGrupo = Object.keys(grupo)[Math.floor(Math.random() * Object.keys(grupo).length)];
            const exercicios = grupo[nomeGrupo];

            const treinosAjustados = exercicios.map(([nome, s, r]) => {
                let series = s + (nivel.series === "+1" ? 1 : nivel.series === "-1" ? -1 : 0);
                let reps = r + (nivel.reps === "+3" ? 3 : nivel.reps === "-3" ? -3 : 0);
                return [nome, series, reps];
            });

            plano.push({
                dia: dias[i],
                tipo: nomeGrupo,
                exercicios: treinosAjustados,
                descanso: nivel.descanso,
                duracao: nivel.duracao
            });
        }

        plano.push({
            dia: "Sábado",
            tipo: "Aeróbico leve",
            exercicios: [
                ["Caminhada", "30 min"],
                ["Elíptico", "15 min"],
                ["Alongamento", "5–10 min"]
            ],
            descanso: "Livre",
            duracao: "45–60 min"
        });

        plano.push({
            dia: "Domingo",
            tipo: "Descanso",
            exercicios: [
                ["Alongamento leve", "10 min"],
                ["Hidratação extra", ""],
                ["Descanso ativo opcional", ""]
            ],
            descanso: "—",
            duracao: "Livre"
        });

        render(plano);
    }

    function render(plano) {
        grid.innerHTML = "";

        plano.forEach(t => {
            const card = document.createElement("div");
            card.className = "treino-card";

            const jaConcluido = concluidos[t.dia] === true;

            card.innerHTML = `
                <h3>${t.dia}</h3>
                <p class="tipo-treino">${t.tipo}</p>

                <ul class="lista-exercicios">
                    ${t.exercicios
                        .map(ex => `<li>${ex[0]} — <strong>${ex[1]}x${ex[2]}</strong></li>`)
                        .join("")}
                </ul>

                <p class="treino-info">Descanso: ${t.descanso}</p>
                <p class="treino-info">Duração: ${t.duracao}</p>

                <button class="btn-concluir-treino" data-dia="${t.dia}">
                   ${jaConcluido ? "Treino Concluído ✔" : "Concluir Treino 🔥"}
                </button>
            `;

            if (jaConcluido) card.classList.add("treino-concluido");

            grid.appendChild(card);
        });

        adicionarEventosBotoes();
    }

    function adicionarEventosBotoes() {
        document.querySelectorAll(".btn-concluir-treino").forEach(btn => {
            btn.addEventListener("click", () => {
                const dia = btn.dataset.dia;
                const card = btn.closest(".treino-card");

                if (!dia) return;

                const saved = carregarConcluidos();

                const jaConcluido = !!saved[dia];

                if (jaConcluido) {
                    delete saved[dia];
                    card.classList.remove("treino-concluido");
                    btn.textContent = "Concluir Treino 🔥";
                } else {
                    saved[dia] = true;
                    card.classList.add("treino-concluido");
                    btn.textContent = "Treino Concluído ✔";

                    /* 🔔 NOTIFICAÇÃO IMEDIATA */
                    if (window.appNotifs) {
                        window.appNotifs.push({
                            titulo: "Treino Concluído",
                            texto: `Você concluiu o treino de <b>${dia}</b>! Excelente trabalho!`
                        });
                    }
                }

                salvarConcluidos(saved);

                if (typeof atualizarDashboardBarras === "function") {
                    atualizarDashboardBarras();
                }
            });
        });
    }

    window.getTreinosConcluidos = () => carregarConcluidos();

    nivelSelect.addEventListener("change", gerarTreino);
    btnNovo.addEventListener("click", gerarTreino);

    gerarTreino();

});
