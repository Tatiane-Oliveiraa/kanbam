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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanban com Calendário</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
    <link rel="stylesheet" href="./assets/CSS/kanban.css">
    <script src="./assets/JAVASCRIPT/theme.js"></script>
</head>


<body>
    <nav class="navbar">
        <a class="navbar-brand" href="index.php">Planix</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="quiz.php">Quiz</a></li>
            <li class="nav-item">
                <a class="nav-link" href="./relatorio.html">📄 Relatório </a>
            </li>
            <li class="nav-item">
                <button id="themeToggleBtn" onclick="toggleDarkMode()" class="nav-link"
                    style="background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%); border: none; color: #fff; cursor: pointer; padding: 8px 16px; border-radius: 6px; font-weight: bold;">🌙
                    Escuro</button>
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
                    <a href="javascript:void(0)"
                        onclick="openEditModal('<?= $task['id'] ?>','<?= htmlspecialchars($task['titulo'], ENT_QUOTES) ?>','<?= $task['status'] ?>'); return false;">✏️</a>
                    <a href="javascript:void(0)"
                        onclick="openDeleteModal('<?= $task['id'] ?>','<?= htmlspecialchars($task['titulo'], ENT_QUOTES) ?>'); return false;">🗑️</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div id='calendar'></div>

    <!-- Modal de edição -->
    <div id="editModal" class="modal hidden">
        <div class="modal-content">
            <span class="close-modal" title="Fechar">&times;</span>
            <div class="edit-form">
                <h1>✏️ Editar Tarefa</h1>
                <form id="editForm">
                    <input type="hidden" name="id" id="edit-id">

                    <label for="edit-titulo">Título</label>
                    <input id="edit-titulo" type="text" name="titulo" required placeholder="Nome curto da tarefa"
                        autofocus>

                    <label for="edit-descricao">Descrição</label>
                    <textarea id="edit-descricao" name="descricao" rows="4"
                        placeholder="Detalhes da tarefa (opcional)"></textarea>

                    <label for="edit-data">Data</label>
                    <input id="edit-data" type="date" name="data">

                    <label for="edit-hora">Horário</label>
                    <input id="edit-hora" type="time" name="hora">

                    <label for="edit-categoria">Categoria</label>
                    <select id="edit-categoria" name="categoria">
                        <option value="trabalho">Trabalho</option>
                        <option value="pessoal">Pessoal</option>
                        <option value="estudos">Estudos</option>
                    </select>

                    <label for="edit-status">Status</label>
                    <select id="edit-status" name="status">
                        <option value="todo">A Fazer</option>
                        <option value="in-progress">Em Progresso</option>
                        <option value="done">Concluído</option>
                    </select>

                    <button type="submit">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <span class="close-modal" title="Cancelar">&times;</span>
            <div style="text-align: center; padding: 20px;">
                <h2>⚠️ Excluir Tarefa</h2>
                <p id="delete-task-title" style="font-size: 1.1em; color: #555; margin: 16px 0;"></p>
                <p style="color: #888; margin-bottom: 24px;">Esta ação não pode ser desfeita.</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button onclick="closeDeleteModal()"
                        style="padding: 10px 24px; background: #ccc; color: #333; border: none; border-radius: 6px; cursor: pointer; font-size: 1em;">Cancelar</button>
                    <button onclick="confirmDelete()"
                        style="padding: 10px 24px; background: #ff5252; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 1em;">Excluir</button>
                </div>
            </div>
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
        const modal = document.getElementById('editModal');

        // Buscar dados completos da tarefa via fetch
        fetch(`./actions/get_task.php?id=${id}`)
            .then(res => res.json())
            .then(task => {
                document.getElementById('edit-id').value = task.id;
                document.getElementById('edit-titulo').value = task.titulo || '';
                document.getElementById('edit-descricao').value = task.descricao || '';
                document.getElementById('edit-data').value = task.data || '';
                document.getElementById('edit-hora').value = task.hora || '';
                document.getElementById('edit-categoria').value = task.categoria || 'trabalho';
                document.getElementById('edit-status').value = task.status || 'todo';

                modal.classList.remove('hidden');
                setTimeout(() => modal.classList.add('show'), 10);
            })
            .catch(err => {
                console.error('Erro ao buscar tarefa:', err);
                alert('Erro ao carregar tarefa. Tente novamente.');
            });
    }

    function closeModal() {
        const modal = document.getElementById('editModal');
        if (!modal) return;
        modal.classList.remove('show');
        modal.classList.add('closing');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('closing');
        }, 220);
    }

    // fecha ao clicar no overlay e com ESC
    (function() {
        const modal = document.getElementById('editModal');
        if (!modal) return;
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
        // atualiza o close button para usar a nova função
        const closeBtn = document.querySelector('#editModal .close-modal');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
    })();

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
                descricao: document.getElementById('edit-descricao').value,
                data: document.getElementById('edit-data').value,
                hora: document.getElementById('edit-hora').value,
                categoria: document.getElementById('edit-categoria').value,
                status: document.getElementById('edit-status').value
            })
        }).then(res => {
            if (res.ok) {
                location.reload();
            }
        });
    };

    // Modal de exclusão
    let deleteTaskId = null;

    function openDeleteModal(id, titulo) {
        const modal = document.getElementById('deleteModal');
        deleteTaskId = id;
        document.getElementById('delete-task-title').textContent = `"${titulo}"`;
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (!modal) return;
        modal.classList.remove('show');
        modal.classList.add('closing');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('closing');
            deleteTaskId = null;
        }, 220);
    }

    function confirmDelete() {
        if (!deleteTaskId) return;
        window.location.href = `./actions/delete_task.php?id=${deleteTaskId}`;
    }

    // Fechar modal de exclusão ao clicar em overlay ou ESC
    (function() {
        const modal = document.getElementById('deleteModal');
        if (!modal) return;
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeDeleteModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && deleteTaskId) closeDeleteModal();
        });
        const closeBtn = document.querySelector('#deleteModal .close-modal');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeDeleteModal);
        }
    })();
    </script>

    <script src="./assets/JAVASCRIPT/kanban.js"></script>
    <div id="toast" class="toast hidden"></div>
    
    <?php include "./footer.php"; ?>

</body>

</html>