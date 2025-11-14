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
        top: 20px;
        right: 20px
    }

    .btn-login:hover {
        background: linear-gradient(90deg, #0056b3 60%, #0096c7 100%);
        transform: translateY(-2px) scale(1.04);
    }

    .btn-logout {
        position: fixed;
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
        outline: none;
        text-decoration: none;
    }

    .btn-logout:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    /* Botão de modo escuro */
    #cadastro_toggleDarkMode {
        position: fixed;
        top: 20px;
        right: 100px;
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

    #cadastro_toggleDarkMode:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    /* Modo escuro */
    body.dark-mode {
        background: linear-gradient(135deg, #0d1117, #1f2a38);
        color: #f0f0f0;
    }

    body.dark-mode .home-container {
        background-color: #161b22;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
    }

    body.dark-mode .btn-logout {
        background-color: #4f9cf9;
        color: #f0f0f0;
    }

    body.dark-mode #cadastro_toggleDarkMode {
        background-color: #4f9cf9;
        color: #f0f0f0;
    }

    body.dark-mode .home-container h1 {
        color: #4f9cf9;
    }

    body.dark-mode .home-container p {
        color: #c9d1d9;
    }

    body.dark-mode .btn-login {
        background: linear-gradient(90deg, #4f9cf9 60%, #3b82f6 100%);
    }
    </style>
</head>



<body>
    <a href="actions/logout.php" class="btn-logout">Sair</a>
    <button id="cadastro_toggleDarkMode">🌙 Alterar Modo</button>


    <div class="home-container">
        <h1>Bem-vindo ao Planix</h1>
        <p>Organize suas tarefas, visualize no Kanban e no calendário.<br>
            Faça login para acessar seu quadro de tarefas.</p>
        <a href="index.php" class="btn-login">Entrar</a>
    </div>

</body>

</html>