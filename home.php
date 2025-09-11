<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Bem-vindo ao Planix</title>
<link rel="stylesheet" href="./assets/CSS/kanban.css">
<style>
    .home-container {
    max-width: 400px;
    margin: 80px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08);
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    display: inline-block;
    }
    .btn-login:hover {
    background: linear-gradient(90deg, #0056b3 60%, #0096c7 100%);
    transform: translateY(-2px) scale(1.04);
    }
</style>
</head>
<body>
<div class="home-container">
    <h1>Bem-vindo ao Planix</h1>
    <p>Organize suas tarefas, visualize no Kanban e no calendário.<br>
    Faça login para acessar seu quadro de tarefas.</p>
    <a href="index.php" class="btn-login">Entrar</a>
</div>
</body>
</html>