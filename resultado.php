<?php
include 'database/conn.php';
session_start();

$id_usuario = $_SESSION['usuario_id'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Resultado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">QuizDev</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
        <li class="nav-item"><a class="nav-link" href="ranking.php">Ranking</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h1 class="text-center mb-4">Resultado do Quiz</h1>

  <?php
  if (isset($_POST['resposta']) && is_array($_POST['resposta'])) {
    if (!$id_usuario) {
      echo "<div class='alert alert-danger text-center'><i class='bi bi-person-x'></i> Você precisa estar logado para salvar suas respostas.</div>";
    } else {
      $pontuacao = 0;

      foreach ($_POST['resposta'] as $id => $resposta) {
        // Consulta a resposta correta
        $stmt = $conn->prepare("SELECT resposta_correta FROM perguntas WHERE id = ?");
        $stmt->execute([$id]);
        $correta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($correta) {
          if ($resposta === $correta['resposta_correta']) {
            $pontuacao++;
          }

          // Salva a resposta do usuário
          $insert = $conn->prepare("INSERT INTO respostas (id_usuario, id_pergunta, resposta_dada) VALUES (?, ?, ?)");
          $insert->execute([$id_usuario, $id, $resposta]);
        }
      }

      $total = count($_POST['resposta']);
      echo "<h2 class='text-center'>Você acertou $pontuacao de $total perguntas.</h2>";

      if ($pontuacao == $total) {
        echo "<div class='alert alert-success text-center'><i class='bi bi-trophy-fill'></i> Mandou muito bem! Você acertou tudo!</div>";
      } elseif ($pontuacao >= 3) {
        echo "<div class='alert alert-warning text-center'><i class='bi bi-lightbulb'></i> Boa! Mas dá pra melhorar!</div>";
      } else {
        echo "<div class='alert alert-danger text-center'><i class='bi bi-book'></i> Precisa estudar mais sobre programação!</div>";
      }

      echo "<div class='text-center mt-4'><a href='quiz.php' class='btn btn-primary'><i class='bi bi-arrow-repeat'></i> Tentar novamente</a></div>";
    }
  } else {
    echo "<div class='alert alert-danger text-center'><i class='bi bi-exclamation-triangle'></i> Nenhuma resposta foi enviada. Por favor, volte e tente novamente.</div>";
    echo "<div class='text-center mt-4'><a href='quiz.php' class='btn btn-secondary'><i class='bi bi-arrow-left'></i> Voltar ao início</a></div>";
  }
  ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
