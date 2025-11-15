<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Bem-vindo ao Planix</title>
    <link rel="stylesheet" href="./assets/CSS/kanban.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="./assets/JAVASCRIPT/theme.js"></script>
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
            font-size: 1.8em;
        }

        .home-container .username {
            color: #007bff;
            font-weight: bold;
        }

        .home-container p {
            color: #333;
            margin-bottom: 28px;
        }

        html.dark-mode .home-container p {
            color: #e4e4e4;
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

        body {
            background: linear-gradient(135deg, #007bff, #6fb1fc) !important;
            transition: background 0.3s ease;
        }

        html.dark-mode body {
            background: #1a1a1a !important;
        }

        html.dark-mode .home-container {
            background: #2a2a2a;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.4);
        }

        html.dark-mode .home-container h1 {
            color: #00c6ff;
        }

        html.dark-mode .home-container .username {
            color: #00c6ff;
        }

        #themeToggleBtn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
        }
    </style>
</head>



<body>
    <?php
    session_start();
    include("./database/conn.php");

    // Buscar nome do usuário
    $usuario_nome = "Usuário";
    if (isset($_SESSION['usuario_id'])) {
        try {
            $stmt = $conn->prepare("SELECT nome FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $_SESSION['usuario_id']);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario) {
                // Pegar apenas o primeiro nome
                $nome_completo = htmlspecialchars($usuario['nome']);
                $usuario_nome = explode(' ', $nome_completo)[0];
            }
        } catch (PDOException $e) {
            // Usar valor padrão em caso de erro
        }
    }
    ?>
    <a href="actions/logout.php" class="btn-logout">Sair</a>
    <button id="themeToggleBtn" onclick="toggleDarkMode()"
        style="background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%); border: none; color: #fff; cursor: pointer; padding: 10px 16px; border-radius: 8px; font-size: 14px; font-weight: bold; position: fixed; top: 20px; right: 100px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); z-index: 999;">🌙
        Escuro</button>


    <div class="home-container">
        <h1>Bem-vindo, <span class="username"><?= $usuario_nome ?></span>!</h1>
        <p>Organize suas tarefas, visualize no Kanban e no calendário.<br>
            Faça login para acessar seu quadro de tarefas.</p>
        <a href="index.php" class="btn-login">Entrar</a>
    </div>

</body>

</html>