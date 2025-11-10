<?php
session_start();
include(__DIR__ . "/database/conn.php"); // Caminho absoluto seguro

if (!isset($_SESSION['usuario_id'])) {
  header("Location: actions/paginainicial.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/CSS/dashboard.css">
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


  <div class="dashboard_container">
    <h2 class="dashboard_title">Bem-vindo ao Planix, Jão!</h2>
    <p class="dashboard_text">Aqui você pode gerenciar suas tarefas, metas e produtividade.</p>

    <a href="actions/logout.php" class="dashboard_logoutButton">🚪 Sair</a>
    <a href="relatorio.html">
  <button style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px;">
    📄 Ver Relatório de Tarefas
  </button>
</a>

  </div>

  <?php include 'footer.php'; ?>

  <script>
    const toggle = document.getElementById('dashboard_toggleDarkMode');
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
</body>
</html>
