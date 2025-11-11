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

    

    footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      background-color: #0056b3;
      color: #fff;
      padding: 20px 60px;
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

     <!--codigo apenas do footer. para caso queira mudar atualiza em todas as paginas e n precisa ficar colocando em uma por uma-->
  <footer>
    <p>&copy; 2025 -  Projeto Planixx | Todos os direitos reservados.</p>

    <div style="margin:15px 0;">
        <a href="https://www.instagram.com/jao_lucas_s" target="_blank" style="margin:0 10px; text-decoration:none; color:#fff;">
            <img src="https://media.discordapp.net/attachments/1263445013745107048/1424426255536619641/2048px-Instagram_icon.png?ex=68e3e7d3&is=68e29653&hm=ae71936848961060621c4ea5812f8e37831faf4959c101052ca634a5256d5d2b&=&format=webp&quality=lossless&width=960&height=960" alt="Instagram" width="24" style="vertical-align:middle; margin-right:5px;">João
        </a>
            <a href="https://www.instagram.com/devtatianeoliveira/?next=%2F" target="_blank" style="margin:0 10px; text-decoration:none; color:#fff;">
            <img src="https://media.discordapp.net/attachments/1263445013745107048/1424426255536619641/2048px-Instagram_icon.png?ex=68e3e7d3&is=68e29653&hm=ae71936848961060621c4ea5812f8e37831faf4959c101052ca634a5256d5d2b&=&format=webp&quality=lossless&width=960&height=960" alt="Instagram" width="24" style="vertical-align:middle; margin-right:5px;">Tatiane

        </a>
        <a href="https://www.instagram.com/cl4rot" target="_blank" style="margin:0 10px; text-decoration:none; color:#fff;">
            <img src="https://media.discordapp.net/attachments/1263445013745107048/1424426255536619641/2048px-Instagram_icon.png?ex=68e3e7d3&is=68e29653&hm=ae71936848961060621c4ea5812f8e37831faf4959c101052ca634a5256d5d2b&=&format=webp&quality=lossless&width=960&height=960" alt="Instagram" width="24" style="vertical-align:middle; margin-right:5px;">Clara
        </a>
        
    </div>
  </footer>

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
    
</body>

</html>