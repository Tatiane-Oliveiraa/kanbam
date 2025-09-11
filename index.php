<?php
// Inclui o arquivo de conexão com o banco de dados
include './database/conn.php';

// Verifica se foi passada uma categoria via GET e prepara a consulta SQL
$categoria = $_GET['categoria'] ?? ''; // Se não houver categoria, usa string vazia
$sql = "SELECT * FROM tasks";

// Se houver categoria, adiciona cláusula WHERE e prepara o parâmetro
if ($categoria) {
  $sql .= " WHERE categoria = :categoria";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':categoria', $categoria);
} else {
  // Caso contrário, prepara a consulta sem filtro
  $stmt = $conn->prepare($sql);
}

// Executa a consulta e armazena todas as tarefas
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicializa arrays para separar tarefas por status
$todo = [];
$inProgress = [];
$done = [];

// Distribui as tarefas conforme o status
foreach ($tasks as $task) {
  switch ($task['status']) {
    case 'todo': $todo[] = $task; break;
    case 'in-progress': $inProgress[] = $task; break;
    case 'done': $done[] = $task; break;
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <!-- Configurações básicas da página -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kanban com Calendário</title>

  <!-- Estilos e scripts do FullCalendar -->
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>

  <!-- Estilo personalizado do Kanban -->
  <link rel="stylesheet" href="./assets/CSS/kanban.css">
</head>
<body>

  <nav class="navbar">
    <a class="navbar-brand" href="index.php">Planix</a>
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
    </ul>
  </nav>


  <h1>Meu Quadro de Tarefas</h1>

  <!-- Link para visualizar tarefas futuras -->
  <div>
    <a href="./actions/future_task.php" class="link-futuras">🔮 Ver Tarefas Futuras</a>
  </div>

  <!-- Formulário para adicionar nova tarefa -->
  <form class="add-form" action="./actions/add_task.php" method="POST">
    <!-- Campos obrigatórios para título, data e hora -->
    <input type="text" name="titulo" placeholder="Nova tarefa..." required>
    <input type="date" name="data" >
    <input type="time" name="hora" >

    <!-- Seleção de status da tarefa -->
    <select name="status">
      <option value="todo">A Fazer</option>
      <option value="in-progress">Em Progresso</option>
      <option value="done">Concluído</option>
    </select>

    <!-- Seleção de categoria -->
    <select name="categoria" class="data-categoria" >
      <option value="trabalho">Trabalho</option>
      <option value="pessoal">Pessoal</option>
      <option value="estudos">Estudos</option>
    </select>

    <!-- Campo de descrição opcional -->
    <textarea name="descricao" placeholder="Descrição da tarefa..."></textarea>

    <!-- Botão para enviar o formulário -->
    <button type="submit">Adicionar</button>
  </form>

  <!-- Formulário para filtrar tarefas por categoria -->
  <form method="GET" action="">
    <select name="categoria">
      <option value="">Todas</option>
      <option value="trabalho">Trabalho</option>
      <option value="pessoal">Pessoal</option>
      <option value="estudos">Estudos</option>
    </select>
    <button type="submit">Filtrar</button>
  </form>

  <!-- Quadro Kanban com colunas por status -->
  <div class="kanban-board">
    <?php
    // Define os títulos e listas de tarefas por status
    $colunas = [
      'todo' => ['A Fazer', $todo],
      'in-progress' => ['Em Progresso', $inProgress],
      'done' => ['Concluído', $done]
    ];

    // Gera cada coluna do Kanban dinamicamente
    foreach ($colunas as $id => [$titulo, $lista]):
    ?>
      <div class="kanban-column" id="<?= $id ?>">
        <h2><?= $titulo ?></h2>

        <!-- Exibe cada tarefa dentro da coluna -->
        <?php foreach ($lista as $task): ?>
          <?php
          // Verifica se a tarefa é futura com base na data
          $futuro = ($task['data'] > date('Y-m-d')) ? 'true' : 'false';
          ?>
          <div class="task" draggable="true" data-id="<?= $task['id'] ?>" data-futuro="<?= $futuro ?>">
            <!-- Título da tarefa -->
            <strong><?= htmlspecialchars($task['titulo']) ?></strong>

            <!-- Categoria e descrição -->
            <p class="categoria"><?= htmlspecialchars($task['categoria'] ?? '') ?></p>
            <p class="descricao"><?= nl2br(htmlspecialchars($task['descricao'] ?? '')) ?></p>

            <!-- Ações de editar e excluir -->
            <div class="actions">
              <a href="#" onclick="openEditModal('<?= $task['id'] ?>','<?= htmlspecialchars($task['titulo'], ENT_QUOTES) ?>','<?= $task['status'] ?>'); return false;">✏️</a>
              <a href="actions/delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Excluir esta tarefa?')">🗑️</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Calendário que exibe as tarefas -->
  <div id='calendar'></div>

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
    // Inicializa o calendário após o carregamento da página
    document.addEventListener('DOMContentLoaded', function () {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Visualização mensal
        locale: 'pt-br', // Idioma português
        events: './actions/get_tasks_json.php', // Fonte dos eventos
        editable: false, // Eventos não podem ser arrastados
        eventClick: function(info) {
          // Exibe alerta com detalhes da tarefa ao clicar
          alert('Tarefa: ' + info.event.title + '\nDescrição: ' + (info.event.extendedProps.description || 'Sem descrição'));
        }
      });

      calendar.render(); // Renderiza o calendário
    });

    function openEditModal(id, titulo, status) {
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-titulo').value = titulo;
      document.getElementById('edit-status').value = status;
      document.getElementById('editModal').classList.remove('hidden');
    }
    function closeModal() {
      document.getElementById('editModal').classList.add('hidden');
    }

    // Envio via AJAX
    document.getElementById('editForm').onsubmit = function(e) {
      e.preventDefault();
      fetch('./actions/update.php', {
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

  <!-- Script JS para funcionalidades do Kanban -->
  <script src="./assets/JAVASCRIPT/kanban.js"></script>
</body>
</html>
