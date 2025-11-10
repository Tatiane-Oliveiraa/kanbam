<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
  header("Location: ../home.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/CSS/style.css">
</head>

<header>
  <div class="logo">Logo</div>
  <nav>
    <a href="actions/paginainicial.php">Login</a>
    <a href="actions/cadastro.php">Cadastre-se</a>
    <button id="dashboard_toggleDarkMode">🌙 Alterar Modo</button>
  </nav>
</header>

<body>



  <div class="login_container">

    <div class="login_box">
      <!-- Mensagem de erro centralizada -->
      <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem-erro">
          <?= $_SESSION['mensagem']; ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
      <?php endif; ?>

      <h2 class="login_title">Entrar na Conta</h2>
      <form action="login.php" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>

        <button type="submit">Login</button>
      </form>

      <div class="login_cadastroLink">
        <p class="login_cadastroText">Não possui uma conta?</p>
        <a href="cadastro.php" class="login_cadastroButton">📝 Cadastre-se aqui</a>
      </div>
    </div>
  </div>

  <script>
    const toggle = document.getElementById('login_toggleDarkMode');
    const body = document.body;

    if (localStorage.getItem('modo') === 'escuro') {
      body.classList.add('dark-mode');
      toggle.textContent = '☀️ Alterar Modo';
    }

    toggle.addEventListener('click', () => {
      body.classList.toggle('dark-mode');
      const modoAtual = body.classList.contains('dark-mode') ? 'escuro' : 'claro';
      localStorage.setItem('modo', modoAtual);
      toggle.textContent = modoAtual === 'escuro' ? '☀️ Alterar Modo' : '🌙 Alterar Modo';
    });
  </script>
  <?php include '../footer.php'; ?>
</body>

</html>