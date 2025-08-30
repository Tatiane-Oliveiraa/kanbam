<?php
// Inclui o arquivo de conexão com o banco de dados
include '../database/conn.php';

// Executa uma consulta SQL para selecionar todos os registros da tabela 'tasks'
$stmt = $conn->query("SELECT * FROM tasks");

// Recupera todos os resultados da consulta como um array associativo
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicializa um array vazio que irá armazenar os eventos formatados
$eventos = [];

// Itera sobre cada tarefa retornada do banco
foreach ($tasks as $task) {
  // Adiciona um novo evento ao array, formatando os dados conforme necessário
  $eventos[] = [
    'id' => $task['id'],                         // ID da tarefa
    'title' => $task['titulo'],                  // Título da tarefa
    'start' => $task['data'] . 'T' . $task['hora'] // Combina data e hora no formato ISO 8601
  ];
}

// Converte o array de eventos para JSON e imprime na tela
echo json_encode($eventos);
?>
