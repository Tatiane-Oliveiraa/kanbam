    <script>
// Controla abertura/fechamento do modal com animação
(function() {
    const modal = document.querySelector('.modal');
    const closeBtn = document.querySelector('.close-modal');

    function closeWithAnimation() {
        if (!modal) return;
        modal.classList.add('closing');
        setTimeout(() => {
            window.location.href = '../index.php';
        }, 220);
    }

    // mostra o modal com classe 'show' (força a animação de entrada)
    if (modal) {
        // pequeno timeout para permitir transições CSS
        setTimeout(() => modal.classList.add('show'), 10);

        // click no overlay (fora do modal-content) fecha
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeWithAnimation();
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeWithAnimation();
        });
    }

    // Fecha com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeWithAnimation();
        }
    });
})();
    </script>

    <?php 
    // 3. Lógica de Atualização (quando o formulário é enviado) if ($_SERVER['REQUEST_METHOD']==='POST' ) {
    $titulo=$_POST['titulo']; $status=$_POST['status']; $descricao=$_POST['descricao']; 
    // Segurança: Garante que o usuário só pode atualizar suas próprias tarefas 
    $sql="UPDATE tasks SET titulo = :titulo, status = :status, descricao
    = :descricao WHERE id = :id AND usuario =
    :usuario_id" ; $stmt=$conn->prepare($sql);

    $stmt->execute([
    ':titulo' => $titulo,
    ':status' => $status,
    ':descricao' => $descricao,
    ':id' => $id,
    ':usuario_id' => $usuario_id
    ]);

    header("Location: ../index.php");
    exit;

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

        <!-- Modal de edição -->
        <div class="modal" role="dialog" aria-labelledby="editTitle" aria-modal="true">
            <div class="modal-content">
                <span class="close-modal" title="Fechar">&times;</span>
                <div class="form-container"
                    style="max-width:680px; margin:0; padding:0; background:transparent; box-shadow:none;">
                    <div class="edit-form"
                        style="background:#fff; padding:28px; border-radius:10px; box-shadow:0 8px 30px rgba(0,0,0,0.12);">
                        <h1 id="editTitle">✏️ Editar Tarefa</h1>
                        <form method="POST" action="edit_task.php?id=<?= $task['id'] ?>">
                            <label for="titulo">Título</label>
                            <input id="titulo" type="text" name="titulo"
                                value="<?= htmlspecialchars($task['titulo']) ?>" required
                                placeholder="Nome curto da tarefa" autofocus>

                            <label for="descricao">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="5"
                                placeholder="Detalhes da tarefa (opcional)"><?= htmlspecialchars($task['descricao']) ?></textarea>

                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>A Fazer
                                </option>
                                <option value="in-progress" <?= $task['status'] === 'in-progress' ? 'selected' : '' ?>>
                                    Em
                                    Progresso</option>
                                <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>Concluído
                                </option>
                            </select>

                            <button type="submit">Salvar Alterações</button>
                        </form>
                        <a href="../index.php" class="back-link">⬅️ Voltar ao Kanban</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Fecha o modal e volta para o índice
        (function() {
            const closeBtn = document.querySelector('.close-modal');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    window.location.href = '../index.php';
                });
            }

            // Fecha com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    window.location.href = '../index.php';
                }
            });
        })();
        </script>

    </body>

    </html>