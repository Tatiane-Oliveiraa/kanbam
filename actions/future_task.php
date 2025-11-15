<?php
session_start();
$usuario = $_SESSION['usuario_id'];
// Inclui o arquivo de conexão com o banco de dados
include '../database/conn.php'; // Garante que a variável $conn esteja disponível para uso

// Prepara uma consulta SQL para buscar todas as tarefas cuja data seja maior que a data atual
$stmt = $conn->prepare("SELECT * FROM tasks WHERE usuario=$usuario AND data > CURDATE() ORDER BY data ASC");

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
    <script src="../assets/JAVASCRIPT/theme.js"></script>
</head>

<body>
    <!-- Navbar com botão de dark mode -->
    <nav class="navbar" style="margin-bottom: 20px;">
        <a class="navbar-brand" href="../index.php">Planix</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <button id="themeToggleBtn" onclick="toggleDarkMode()" class="nav-link"
                    style="background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%); border: none; color: #fff; cursor: pointer; padding: 8px 16px; border-radius: 6px; font-weight: bold;">🌙
                    Escuro</button>
            </li>
        </ul>
    </nav>

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
                <em><?= date('d/m/Y', strtotime($task['data'])) ?> às
                    <?= date('H:i', strtotime($task['hora'])) ?></em><br>

                <!-- Exibe a categoria da tarefa -->
                <span>Categoria: <?= htmlspecialchars($task['categoria']) ?></span><br>

                <!-- Exibe a descrição da tarefa, convertendo quebras de linha para <br> -->
                <p><?= nl2br(htmlspecialchars($task['descricao'])) ?></p>

                <!-- Link para editar a tarefa futura -->
                <a href="javascript:void(0)" class="btn-editar"
                    onclick="openEditModal('<?= $task['id'] ?>','<?= htmlspecialchars($task['titulo'], ENT_QUOTES) ?>','<?= $task['status'] ?>'); return false;">✏️
                    Editar</a>
                <a href="javascript:void(0)" class="btn-excluir"
                    onclick="openDeleteModal('<?= $task['id'] ?>','<?= htmlspecialchars($task['titulo'], ENT_QUOTES) ?>'); return false;">🗑️
                    Excluir</a>
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
    function openEditModal(id, titulo, status) {
        const modal = document.getElementById('editModal');

        // Buscar dados completos da tarefa via fetch
        fetch(`../actions/get_task.php?id=${id}`)
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

    document.getElementById('editForm').onsubmit = function(e) {
        e.preventDefault();
        fetch('update.php', {
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
    })();

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
        window.location.href = `delete_task.php?id=${deleteTaskId}`;
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
</body>

</html>