<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Bem-vindo ao Planix</title>
  <link rel="stylesheet" href="./assets/CSS/kanban.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/javascript/script.js" defer></script>
  <style>
    .home-container {
      max-width: 400px;
      margin: 80px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
      padding: 40px 32px;
      text-align: center;
      animation: fadeIn 0.7s;
    }

    .home-container h1 {
      color: #007bff;
      margin-bottom: 18px;
    }

    .home-container p {
      color: #333;
      margin-bottom: 28px;
    }

    .btn-login {
      background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%);
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 12px 28px;
      font-size: 1.1em;
      cursor: pointer;
      text-decoration: none;
      font-weight: 500;
      transition: background 0.2s, transform 0.1s;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
      display: inline-block;
    }

    .btn-login:hover {
      background: linear-gradient(90deg, #0056b3 60%, #0096c7 100%);
      transform: translateY(-2px) scale(1.04);
    }
  </style>
</head>

<header>
  <div class="logo">Logo</div>
  <nav>
    <a href="actions/paginainicial.php">Login</a>
    <a href="actions/cadastro.php">Cadastre-se</a>
    <button id="theme-toggle" class="theme-toggle" aria-label="Alternar tema">
  </nav>
</header>

<body>
  
    <svg id="theme-icon" class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
      <circle cx="12" cy="12" r="5" />
      <line x1="12" y1="1" x2="12" y2="3" />
      <line x1="12" y1="21" x2="12" y2="23" />
      <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
      <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
      <line x1="1" y1="12" x2="3" y2="12" />
      <line x1="21" y1="12" x2="23" y2="12" />
      <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
      <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
    </svg>
  </button>
  <div class="home-container">
    <h1>Bem-vindo ao Planix</h1>
    <p>Organize suas tarefas, visualize no Kanban e no calendário.<br>
      Faça login para acessar seu quadro de tarefas.</p>
    <a href="index.php" class="btn-login">Entrar</a>
  </div>
  <?php include 'footer.php'; ?>
</body>

</html>