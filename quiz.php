<?php
include './database/conn.php';

// Verifica se a conexão está OK
if (!$conn) {
    die("Erro na conexão.");
}

// Executa a consulta
try {
    $stmt = $conn->prepare("SELECT * FROM perguntas ORDER BY RAND() LIMIT 5");
    $stmt->execute();
    $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Quiz de Programação</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./assets/CSS/quiz.css">
  <script>
  let tempo = 30;
  let tempoTotal = tempo;
  let timer = setInterval(() => {
    tempo--;
    document.getElementById("tempo").innerText = tempo + "s";
    document.getElementById("barra-tempo").style.width = (tempo / tempoTotal * 100) + "%";

    if (tempo <= 5 && tempo > 0) {
      document.getElementById("tempo").classList.add("animate__shakeX");
      if (typeof beep === "function") beep();
    } else {
      document.getElementById("tempo").classList.remove("animate__shakeX");
    }

    if (tempo <= 0) {
      clearInterval(timer);
      document.forms["quizForm"].submit();
    }
  }, 1000);

  function beep() {
    try {
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const o = ctx.createOscillator();
      const g = ctx.createGain();
      o.type = "sine";
      o.connect(g);
      g.connect(ctx.destination);
      o.frequency.value = 880;
      g.gain.value = 0.1;
      o.start();
      setTimeout(() => { o.stop(); ctx.close(); }, 100);
    } catch (e) {}
  }
</script>
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="quiz.php">QuizDev</a>
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
    <h1 class="text-center mb-4">Quiz de Programação</h1>
    <div class="progress mb-2" style="height: 12px;">
      <div id="barra-tempo" class="progress-bar bg-danger" role="progressbar" style="width: 100%;"></div>
    </div>
    <div class="alert alert-danger text-center" id="tempo">30s</div>

    <form method="POST" action="resultado.php" name="quizForm">
      <?php foreach ($perguntas as $p): ?>
        <div class='card mb-3'>
          <div class='card-body'>
            <h5 class='card-title'><?= htmlspecialchars($p['pergunta']) ?></h5>
            <?php foreach (['a','b','c','d'] as $letra): ?>
              <div class='form-check'>
                <input class='form-check-input' type='radio' name='resposta[<?= $p['id'] ?>]' value='<?= strtoupper($letra) ?>' id='<?= $p['id'] ?>_<?= $letra ?>'>
                <label class='form-check-label' for='<?= $p['id'] ?>_<?= $letra ?>'><?= htmlspecialchars($p["alternativa_$letra"]) ?></label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle"></i> Enviar
        </button>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
