<?php
session_start();
include '../database/conn.php';

// 1. Segurança: Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header("Location: paginainicial.php");
  exit;
}

$usuario_id = $_SESSION['usuario_id'];
$id = $_GET['id'] ?? null;

// 2. Validação: Garante que um ID foi passado
if (!$id) {
  die("ID da tarefa não fornecido.");
}

// 3. Lógica de Atualização (quando o formulário é enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = $_POST['titulo'];
  $status = $_POST['status'];
  $descricao = $_POST['descricao'];

  // Segurança: Garante que o usuário só pode atualizar suas próprias tarefas
  $sql = "UPDATE tasks SET titulo = :titulo, status = :status, descricao = :descricao WHERE id = :id AND usuario = :usuario_id";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':titulo' => $titulo,
    ':status' => $status,
    ':descricao' => $descricao,
    ':id' => $id,
    ':usuario_id' => $usuario_id
  ]);

  header("Location: ../index.php");
  exit;
}

// 4. Busca os dados da tarefa para preencher o formulário
// Segurança: Garante que o usuário só pode ver/editar suas próprias tarefas
$sql = "SELECT * FROM tasks WHERE id = :id AND usuario = :usuario_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// 5. Validação: Se a tarefa não existe ou não pertence ao usuário, exibe um erro.
if (!$task) {
  die("Tarefa não encontrada ou você não tem permissão para editá-la.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="../assets/CSS/kanban.css">
</head>

<body>
    <nav class="navbar">
        <a class="navbar-brand" href="../index.php">Planix</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <div class="edit-form">
            <h1>✏️ Editar Tarefa</h1>
            <form method="POST" action="edit_task.php?id=<?= $task['id'] ?>">
                <input type="text" name="titulo" value="<?= htmlspecialchars($task['titulo']) ?>" required>
                <textarea name="descricao"><?= htmlspecialchars($task['descricao']) ?></textarea>
                <select name="status">
                    <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>A Fazer</option>
                    <option value="in-progress" <?= $task['status'] === 'in-progress' ? 'selected' : '' ?>>Em Progresso
                    </option>
                    <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>Concluído</option>
                </select>
                <button type="submit">Salvar Alterações</button>
            </form>
            <a href="../index.php" class="back-link">⬅️ Voltar ao Kanban</a>
        </div>
    </div>

</body>

</html>