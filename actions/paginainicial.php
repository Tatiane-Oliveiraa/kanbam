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

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

     /* Botão de modo escuro */
    #login_toggleDarkMode {
      top: 20px;
      right: 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s ease, transform 0.2s ease;
      z-index: 1000;
    }

    #login_toggleDarkMode:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }

/* Modo escuro */
    body.dark-mode {
      background: linear-gradient(135deg, #080a0e, #1f2a38);
      color: #f0f0f0;
    }

    body.dark-mode footer {
    background: linear-gradient(135deg, #080a0e, #1f2a38);
      color: #f0f0f0;
    }


    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
        }
</style>


     <button id="login_toggleDarkMode">🌙 Alterar Modo</button>


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
    </div>
    <div class="page-content">
  <!-- todo o conteúdo da página -->
    </div>

    <?php include "../footer.php"; ?>  

</body>

</html>