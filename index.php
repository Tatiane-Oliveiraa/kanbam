<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: ./actions/paginainicial.php");
  exit;
}

include './database/conn.php';
$usuario = $_SESSION['usuario_id'];
$categoria = $_GET['categoria'] ?? '';

$sql = "SELECT * FROM tasks WHERE usuario = :usuario";
if ($categoria) {
  $sql .= " AND categoria = :categoria"; // faz a filtragem
}
$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario', $usuario);
if ($categoria) {
  $stmt->bindParam(':categoria', $categoria);
}

$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$todo = [];
$inProgress = [];
$done = [];

foreach ($tasks as $task) {
  switch ($task['status']) {
    case 'todo':
      $todo[] = $task;
      break;
    case 'in-progress':
      $inProgress[] = $task;
      break;
    case 'done':
      $done[] = $task;
      break;
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
<<<<<<< HEAD
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanban com Calendário</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
    <link rel="stylesheet" href="./assets/CSS/kanban.css">
</head>

=======
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kanbam com Calendário</title>
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
  <link rel="stylesheet" href="./assets/CSS/kanban.css">
</head>

<header>
  <div class="logo">
        <img src="logo.png.png" alt="logo"></div>
  <nav>
    <button id="dashboard_toggleDarkMode">🌙 Alterar Modo</button>
  </nav>
</header>

>>>>>>> a767bb4748d0b96f42afb96f1aba6b612a424cd5

<body>
    <nav class="navbar">
        <a class="navbar-brand" href="index.php">Planix</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="quiz.php">Quiz</a></li>
            <li class="nav-item">
                <a class="nav-link" href="./relatorio.html">📄 Relatório </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./actions/logout.php">Sair</a>

            </li>
        </ul>
    </nav>


    <h1>Meu Quadro de Tarefas</h1>

    <div>
        <a href="./actions/future_task.php" class="link-futuras">🔮 Ver Tarefas Futuras</a>
    </div>

    <form class="add-form" action="./actions/add_task.php" method="POST">
        <input type="text" name="titulo" placeholder="Nova tarefa..." required>
        <input type="date" name="data">
        <input type="time" name="hora">
        <select name="status">
            <option value="todo">A Fazer</option>
            <option value="in-progress">Em Progresso</option>
            <option value="done">Concluído</option>
        </select>
        <select name="categoria" class="data-categoria">
            <option value="trabalho">Trabalho</option>
            <option value="pessoal">Pessoal</option>
            <option value="estudos">Estudos</option>
        </select>
        <textarea name="descricao" placeholder="Descrição da tarefa..."></textarea>
        <button type="submit">Adicionar</button>
    </form>

    <form method="GET" action="">
        <select name="categoria">
            <option value="">Todas</option>
            <option value="trabalho">Trabalho</option>
            <option value="pessoal">Pessoal</option>
            <option value="estudos">Estudos</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <div class="kanban-board">
        <?php
    $colunas = [
      'todo' => ['A Fazer', $todo],
      'in-progress' => ['Em Progresso', $inProgress],
      'done' => ['Concluído', $done]
    ];

    foreach ($colunas as $id => [$titulo, $lista]):
    ?>
        <div class="kanban-column" id="<?= $id ?>">
            <h2><?= $titulo ?></h2>
            <?php foreach ($lista as $task): ?>
            <?php $futuro = ($task['data'] > date('Y-m-d')) ? 'true' : 'false'; ?>
            <div class="task" draggable="true" data-id="<?= $task['id'] ?>" data-futuro="<?= $futuro ?>">
                <strong><?= htmlspecialchars($task['titulo']) ?></strong>
                <p class="categoria"><?= htmlspecialchars($task['categoria'] ?? '') ?></p>
                <p class="descricao"><?= nl2br(htmlspecialchars($task['descricao'] ?? '')) ?></p>
                <div class="actions">
                    <a href="#"
                        onclick="openEditModal('<?= $task['id'] ?>','<?= htmlspecialchars($task['titulo'], ENT_QUOTES) ?>','<?= $task['status'] ?>'); return false;">✏️</a>
                    <a href="actions/delete_task.php?id=<?= $task['id'] ?>"
                        onclick="return confirm('Excluir esta tarefa?')">🗑️</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div id='calendar'></div>

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
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            events: './actions/get_tasks_json.php',
            editable: false,
            eventClick: function(info) {
                alert('Tarefa: ' + info.event.title + '\nDescrição: ' + (info.event.extendedProps
                    .description || 'Sem descrição'));
            }
        });
        calendar.render();
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

    document.getElementById('editForm').onsubmit = function(e) {
        e.preventDefault();
        fetch('./actions/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: document.getElementById('edit-id').value,
                titulo: document.getElementById('edit-titulo').value,
                status: document.getElementById('edit-status').value
            })
        }).then(res => {
            if (res.ok) {
                location.reload();
            }
        });
    };
    </script>

    <script src="./assets/JAVASCRIPT/kanban.js"></script>
    <div id="toast" class="toast hidden"></div>

</body>

</html>