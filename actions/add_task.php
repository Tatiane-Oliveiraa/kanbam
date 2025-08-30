<?php
// === Conexão com o banco de dados ===
// Inclui o arquivo que estabelece a conexão com o banco usando PDO.
// Esse arquivo deve conter as credenciais e a instância de conexão ($conn).
include '../database/conn.php';
// === Captura dos dados enviados via formulário (método POST) ===
// Usa o operador de coalescência nula (??) para garantir que cada variável tenha um valor padrão,
// mesmo que o campo não tenha sido preenchido no formulário.

$titulo = $_POST['titulo'] ?? '';        // Título da tarefa
$data = $_POST['data'] ?? '';            // Data prevista para a tarefa
$hora = $_POST['hora'] ?? '';            // Hora prevista
$status = $_POST['status'] ?? 'todo';    // Status inicial da tarefa (padrão: "todo")
$categoria = $_POST['categoria'] ?? '';  // Categoria da tarefa (ex: trabalho, pessoal)
$descricao = $_POST['descricao'] ?? '';  // Descrição detalhada da tarefa
// === Verificação de duplicidade ===
// Antes de inserir, verifica se já existe uma tarefa com o mesmo título.
// Isso evita que tarefas duplicadas sejam criadas acidentalmente.

$sqlCheck = "SELECT COUNT(*) FROM tasks WHERE titulo = :titulo"; // Consulta que conta quantas tarefas têm o mesmo título
$stmtCheck = $conn->prepare($sqlCheck);                          // Prepara a query para evitar SQL Injection
$stmtCheck->execute([':titulo' => $titulo]);                     // Executa a consulta passando o título como parâmetro
$exists = $stmtCheck->fetchColumn();                             // Retorna o número de registros encontrados (0 ou mais)
// === Tratamento de duplicidade ===
// Se a variável $exists for maior que 0, significa que já existe uma tarefa com esse título.
// Nesse caso, exibe um alerta para o usuário e redireciona de volta para a página principal.

//if ($exists) {
 // echo "<script>alert('Tarefa já existe'); window.location.href = '../index.php';</script>";
 // exit; // Encerra a execução do script para evitar que a inserção ocorra
//}
// === Inserção da nova tarefa ===
// Se não houver duplicidade, insere os dados no banco de dados.
// Utiliza uma query preparada com parâmetros posicionais (?) para segurança.

$sql = "INSERT INTO tasks (titulo, data, hora, status, categoria, descricao)
        VALUES (?, ?, ?, ?, ?, ?)"; // Define os campos e os valores a serem inseridos

$stmt = $conn->prepare($sql); // Prepara a query para execução segura
$stmt->execute([$titulo, $data, $hora, $status, $categoria, $descricao]); // Executa passando os valores em ordem
// === Redirecionamento após inserção ===
// Após inserir a tarefa com sucesso, redireciona o usuário para a página principal.
// Isso evita que o formulário seja reenviado ao atualizar a página.

header("Location: ../index.php"); // Redireciona para o Kanban ou lista de tarefas
exit; // Encerra o script para garantir que nada mais seja executado
?>
