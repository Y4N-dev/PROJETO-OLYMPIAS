<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <?php include 'includes/carrossel.php'; ?>
  <!-- then welcome title -->
  <div class="text-center mb-5">

    <h1 class="fw-bold text-warning"><i>BEM-VINDO À </i><span class="bem-vindo-olympias">OLYMPIA'S</span>!</h1>
    <p class="text-light-emphasis">Transforme seu corpo, sua mente e sua rotina com a nossa academia virtual.</p>
    
    <?php include 'includes/sobre.php'; ?>
    <?php include 'includes/motivacionais.php'; ?>
  </div>

  <section class="row g-4">
    <?php
    require 'includes/db_connect.php';
    $stmt = $pdo->query("SELECT * FROM academias LIMIT 6");
    while ($row = $stmt->fetch()):
    ?>
    <div class="col-md-4">
      <div class="card shadow border-0">
        <img src="<?php echo htmlspecialchars($row['imagem_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['nome']); ?>">
        <div class="card-body">
          <h5 class="card-title text-dark"><?= htmlspecialchars($row['nome']) ?></h5>
          <p class="card-text text-muted"><?= htmlspecialchars($row['endereco']) ?></p>
          <a href="unidades.php?unidade=<?= urlencode($row['id']) ?>" class="btn btn-warning">Ver Detalhes</a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </section>
  <script src="assets/js/main.js" defer></script>
</body>
</html>

<?php include 'includes/footer.php'; ?>
