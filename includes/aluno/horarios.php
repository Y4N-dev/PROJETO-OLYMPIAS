<div class="section" data-section="horarios" style="display:none;">

    <h2 class="horarios-title">ㅤㅤHorários da Unidade</h2>

    <div class="horarios-grid">

    <?php
        $dias = [
            "Segunda" => ["06:00", "22:00"],
            "Terça"   => ["06:00", "22:00"],
            "Quarta"  => ["06:00", "22:00"],
            "Quinta"  => ["06:00", "22:00"],
            "Sexta"   => ["06:00", "22:00"],
            "Sábado"  => ["08:00", "18:00"],
            "Domingo" => ["08:00", "14:00"]
        ];

        foreach ($dias as $dia => $info):
            list($abertura, $fechamento) = $info;
    ?>

        <div class="horario-card" data-dia="<?= $dia ?>">
            <div class="card-header">
                <i class="bi bi-calendar2-week"></i>
                <h3><?= $dia ?></h3>
            </div>

            <p class="hora"><strong>Abertura:</strong> <span class="hora-abertura"><?= $abertura ?></span></p>
            <p class="hora"><strong>Fechamento:</strong> <span class="hora-fechamento"><?= $fechamento ?></span></p>

            <p class="status aberta">
                Status: <strong class="status-text">Aberta</strong>
            </p>
        </div>

    <?php endforeach; ?>

    </div>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const cards = document.querySelectorAll(".horario-card");

    if (cards.length === 0) return;

    // → Chance de 1 em 20 para manutenção
    const manutencaoAtiva = Math.random() < 5/20;

    // → Chance de 1 em 20 para feriado
    const fechadoAtivo = Math.random() < 5/20;

    let manutencaoIndex = null;
    let fechadoIndex = null;

    if (manutencaoAtiva) {
        manutencaoIndex = Math.floor(Math.random() * cards.length);
    }

    if (fechadoAtivo) {
        do {
            fechadoIndex = Math.floor(Math.random() * cards.length);
        } while (fechadoIndex === manutencaoIndex);
    }

    cards.forEach((card, i) => {

        // cria bloco de status se não existir
        let status = card.querySelector(".status-box");
        if (!status) {
            status = document.createElement("div");
            status.classList.add("status-box");
            card.prepend(status);
        }

        if (i === manutencaoIndex) {
            status.className = "status-box status-manutencao";
            status.innerText = "Manutenção";
            card.classList.add("card-manutencao", "card-indisponivel");
        }
        else if (i === fechadoIndex) {
            status.className = "status-box status-fechado";
            status.innerText = "Fechado (Feriado)";
            card.classList.add("card-fechado", "card-indisponivel");
        }
        else {
            status.className = "status-box status-aberto";
            status.innerText = "Aberto";
        }

    });

});
</script>

</div>
