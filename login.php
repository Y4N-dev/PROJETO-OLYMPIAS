<?php include 'includes/header.php'; ?>

<section class="login-section">
  <div class="login-container">
    <h2>Entrar</h2>

      <?php if (isset($_GET['success']) && $_GET['success'] === 'cadastro_ok'): ?>
        <div class="alert alert-success text-center">
          Cadastro realizado com sucesso! Faça login abaixo.
        </div>
      <?php endif; ?>
      <?php
      if (isset($_SESSION['login_erro'])) {
          echo '<p style="color: #e74c3c; text-align:center; margin-bottom:10px;">' . htmlspecialchars($_SESSION['login_erro']) . '</p>';
          unset($_SESSION['login_erro']);
      }
      ?>

    <form action="actions/login_action.php" method="POST" class="login-form">
      <div class="login-form-group">
        <label for="login_email">E-mail</label>
        <input type="email" id="login_email" name="email" placeholder="Seu e-mail" required>
      </div>
      <link rel="import" href="header.php">
      <div class="login-form-group">
        <label for="login_senha">Senha</label>
        <div class="login-password-field">
          <input type="password" id="login_senha" name="senha" placeholder="Sua senha" required>
          <button type="button" class="login-password-toggle" aria-label="Mostrar/Ocultar senha">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="login-btn">Entrar</button>
    </form>

    <p class="login-alt">
      Ainda não tem uma conta?
      <a href="cadastro.php" class="login-link-cadastro">Crie uma!</a>
    </p>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Funcionalidade de mostrar/ocultar senha
  const toggleButton = document.querySelector('.login-password-toggle');
  if (toggleButton) {
    toggleButton.addEventListener('click', () => {
      const input = toggleButton.parentElement.querySelector('input');
      const icon = toggleButton.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
        toggleButton.classList.add('visible');
      } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
        toggleButton.classList.remove('visible');
      }
    });
  }
});
</script>

<?php include 'includes/footer.php'; ?>
