<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    echo "<p>Unidade não encontrada.</p>";
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM academias WHERE id = ?");
$stmt->execute([$id]);
$academia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$academia) {
    echo "<p>Unidade não encontrada.</p>";
    exit;
}
?>

<main class="container mt-5">
    <h1 class="text-center mb-4"><?= htmlspecialchars($academia['nome']) ?></h1>

    <div class="card mb-4" style="background-color:#28a745;color:white;">
        <img src="<?= htmlspecialchars($academia['imagem_url']) ?>" class="card-img-top" alt="Imagem da academia">
        <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($academia['cidade']) ?> - <?= htmlspecialchars($academia['estado']) ?></h4>
            <p class="card-text"><strong>Endereço:</strong> <?= htmlspecialchars($academia['endereco']) ?></p>
            <p class="card-text"><strong>Telefone:</strong> <?= htmlspecialchars($academia['telefone']) ?></p>
            <p class="card-text"><strong>Horário de Funcionamento:</strong> <?= htmlspecialchars($academia['horario_funcionamento']) ?></p>

            <?php if (!empty($academia['map_url'])): ?>
                <div class="map-container mt-4">
                    <iframe src="<?= htmlspecialchars($academia['map_url']) ?>" 
                            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="register.php" class="btn btn-warning btn-lg">Inscreva-se</a>
            </div>
        </div>
    </div>
</main>

<?php
require_once 'includes/footer.php';
?>
