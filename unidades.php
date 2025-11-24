<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';
?>

<main class="unidades-section">
  <h1 class="titulo-unidades">Nossas Unidades</h1>

  <div class="lista-unidades">
    <?php
    $stmt = $pdo->query("SELECT * FROM academias ORDER BY cidade ASC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
      $id = htmlspecialchars($row['id']);
      $nome = htmlspecialchars($row['nome']);
      $cidade = htmlspecialchars($row['cidade']);
      $endereco = htmlspecialchars($row['endereco']);
      $imagem = htmlspecialchars($row['imagem_url']);
    ?>
      <section id="unidade-<?= $id ?>" class="bloco-unidade">
        <div class="bloco-imagem">
          <img src="<?= $imagem ?>" alt="<?= $nome ?>">
        </div>

        <div class="bloco-info">
          <h2><?= $nome ?> – <?= $cidade ?></h2>
          <hr>
          <p><strong>Endereço:</strong> <?= $endereco ?></p>
          <p><strong>Horário:</strong> Seg. à Sex. 06h às 22h | Sáb. e Dom. 08h às 18h</p>
          <p><strong>Profissionais:</strong> 10 durante a semana | 5 no fim de semana</p>
          <p><strong>Modalidades:</strong> Fitness, Pilates, CrossFit, BeatDance</p>
          <p><strong>Equipamentos:</strong> Esteiras, Escadas, Bicicletas, Barras Fixas</p>
          <div class="planos">
            <p><b>Plano Mensal:</b> R$ 99,90</p>
            <p><b>Plano Anual:</b> R$ 958,80 (R$ 79,90/mês)</p>
          </div>
          <a href="cadastro.php?unidade=<?= htmlspecialchars($row['id']) ?>" class="btn-inscrever">Inscrever-se</a>
        </div>
      </section>
    <?php endwhile; ?>
  </div>
</main>

<script>
// ===== Scroll automático até a unidade =====
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const unidade = params.get("unidade");
  if (unidade) {
    const alvo = document.querySelector(`[id*="${unidade}"]`);
    if (alvo) {
      setTimeout(() => {
        alvo.scrollIntoView({ behavior: "smooth", block: "center" });
      }, 1200); // executa após o loader desaparecer
    }
  }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const unidade = params.get("unidade");
  if (unidade) {
    const alvo = document.querySelector(`#unidade-${unidade}`);
    if (alvo) {
      alvo.scrollIntoView({ behavior: "smooth", block: "center" });
    }
  }
});
</script>

<?php include 'includes/footer.php'; ?>
