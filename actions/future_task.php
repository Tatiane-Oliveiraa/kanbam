<?php
// Inclui o arquivo de conexão com o banco de dados
include '../database/conn.php'; // Garante que a variável $conn esteja disponível para uso

// Prepara uma consulta SQL para buscar todas as tarefas cuja data seja maior que a data atual
$stmt = $conn->prepare("SELECT * FROM tasks WHERE data > CURDATE() ORDER BY data ASC");

// Executa a consulta no banco
$stmt->execute();

// Armazena todas as tarefas futuras em um array associativo
$tarefasFuturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Tarefas Futuras</title>
  <!-- Importa o arquivo CSS para estilizar a página -->
  <link rel="stylesheet" href="../assets/CSS/kanban.css">
</head>
<body>
  <!-- Título principal da página -->
  <h1>🔮 Tarefas Futuras</h1>

  <!-- Verifica se existem tarefas futuras no array -->
  <?php if (count($tarefasFuturas) > 0): ?>
    <div class="card-future">
        <ul class="lista-futuras">
          <!-- Loop para exibir cada tarefa futura -->
          <?php foreach ($tarefasFuturas as $task): ?>
            <li>
              <!-- Exibe o título da tarefa, escapando caracteres especiais -->
              <strong><?= htmlspecialchars($task['titulo']) ?></strong><br>

              <!-- Exibe a data e hora da tarefa, formatadas para o padrão brasileiro -->
              <em><?= date('d/m/Y', strtotime($task['data'])) ?> às <?= date('H:i', strtotime($task['hora'])) ?></em><br>

              <!-- Exibe a categoria da tarefa -->
              <span>Categoria: <?= htmlspecialchars($task['categoria']) ?></span><br>

              <!-- Exibe a descrição da tarefa, convertendo quebras de linha para <br> -->
              <p><?= nl2br(htmlspecialchars($task['descricao'])) ?></p>

              <!-- Link para editar a tarefa futura -->
              <a href="#" 
                 class="btn-editar"
                 onclick="openEditModal('<?= $task['id'] ?>','<?= htmlspecialchars($task['titulo'], ENT_QUOTES) ?>','<?= $task['status'] ?>'); return false;">✏️ Editar</a>
              <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">🗑️ Excluir</a>
            </li>
          <?php endforeach; ?>
        </ul>
    </div>
  <?php else: ?>
    <!-- Caso não existam tarefas futuras, exibe uma mensagem informativa -->
    <p>Nenhuma tarefa futura encontrada.</p>
  <?php endif; ?>

  <!-- Link para voltar à página principal do Kanban -->
  <a href="../index.php">⬅️ Voltar ao Kanban</a>

  <!-- Modal de edição -->
  <div id="editModal" class="modal hidden">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">&times;</span>
      <form id="editForm">
        <input type="hidden" name="id" id="edit-id">
        <input type="text" name="titulo" id="edit-titulo" required>
        <select name="status" id="edit-status">
          <option value="todo">A Fazer</option>
          <option value="in-progress">Em Progresso</option>
          <option value="done">Concluído</option>
        </select>
        <button type="submit">Salvar</button>
      </form>
    </div>
  </div>

  <script>
function openEditModal(id, titulo, status) {
  document.getElementById('edit-id').value = id;
  document.getElementById('edit-titulo').value = titulo;
  document.getElementById('edit-status').value = status;
  document.getElementById('editModal').classList.remove('hidden');
}
function closeModal() {
  document.getElementById('editModal').classList.add('hidden');
}

document.getElementById('editForm').onsubmit = function(e) {
  e.preventDefault();
  fetch('update.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      id: document.getElementById('edit-id').value,
      titulo: document.getElementById('edit-titulo').value,
      status: document.getElementById('edit-status').value
    })
  }).then(res => {
    if(res.ok) {
      location.reload();
    }
  });
};
</script>
</body>
</html>
