<?php include 'database/conn.php'; ?>
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
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
    <h1 class="text-center mb-4">Ranking do Quiz</h1>

    <?php
    $sql = "
        SELECT 
            u.nome,
            COUNT(*) AS total_acertos
        FROM usuarios u
        JOIN respostas r ON u.id = r.id_usuario
        JOIN perguntas p ON r.id_pergunta = p.id
        WHERE r.resposta_dada = p.resposta_correta
        GROUP BY u.id, u.nome
        ORDER BY total_acertos DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultados) > 0) {
        echo '<table class="table table-striped table-bordered text-center">';
        echo '<thead class="table-dark">
                <tr>
                    <th>Posição</th>
                    <th>Nome</th>
                    <th>Acertos</th>
                </tr>
              </thead>';
        echo '<tbody>';

        $posicao = 1;
        foreach ($resultados as $row) {
            echo '<tr>';
            echo '<td>' . $posicao++ . '</td>';
            echo '<td>' . htmlspecialchars($row["nome"]) . '</td>';
            echo '<td>' . $row["total_acertos"] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p class="text-center">Nenhum resultado encontrado.</p>';
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include "./footer.php"; ?>
</body>
</html>
