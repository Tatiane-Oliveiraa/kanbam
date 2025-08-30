<?php
// Inclui o arquivo de conexão com o banco de dados
include '../database/conn.php';

// Obtém o ID da tarefa via parâmetro GET. Se não existir, define como null
$id = $_GET['id'] ?? null;

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Captura os dados enviados pelo formulário
  $titulo = $_POST['titulo'];   // Novo título da tarefa
  $status = $_POST['status'];   // Novo status da tarefa

  // Prepara a query SQL para atualizar os dados da tarefa no banco
  $sql = "UPDATE tasks SET titulo = :titulo, status = :status WHERE id = :id";
  $stmt = $conn->prepare($sql);

  // Executa a query com os valores fornecidos
  $stmt->execute([
    ':titulo' => $titulo,
    ':status' => $status,
    ':id' => $id
  ]);

  // Redireciona o usuário para a página principal após a atualização
  header("Location: ../index.php");
  exit;
}

// Se o formulário ainda não foi enviado, busca os dados atuais da tarefa para preencher o formulário
$sql = "SELECT * FROM tasks WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);

// Armazena os dados da tarefa em um array associativo
$tasks = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Tarefa</title>
  <!-- Importa o CSS para estilizar o formulário -->
  <link rel="stylesheet" href="./assets/CSS/kanban.css">
</head>
<body>
  <h1>Editar Tarefa</h1>

  <!-- Formulário de edição da tarefa -->
  <form class="add-form" method="POST">
    <!-- Campo de texto para editar o título da tarefa -->
    <input type="text" name="titulo" value="<?= htmlspecialchars($tasks['titulo']) ?>" required>

    <!-- Menu suspenso para selecionar o novo status da tarefa -->
    <select name="status">
      <option value="todo" <?= $tasks['status'] === 'todo' ? 'selected' : '' ?>>A Fazer</option>
      <option value="in-progress" <?= $tasks['status'] === 'in-progress' ? 'selected' : '' ?>>Em Progresso</option>
      <option value="done" <?= $tasks['status'] === 'done' ? 'selected' : '' ?>>Concluído</option>
    </select>

    <!-- Botão para enviar o formulário e salvar as alterações -->
    <button type="submit">Salvar</button>
  </form>
</body>
</html>
