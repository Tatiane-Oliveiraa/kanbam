<?php
// Inclui o arquivo de conexão com o banco de dados
include '../database/conn.php'; // Garante que a variável $conn esteja disponível para executar queries

// Lê os dados enviados no corpo da requisição (geralmente via JavaScript usando fetch/AJAX)
$data = json_decode(file_get_contents("php://input"), true); // Converte o JSON recebido em um array associativo

// Extrai o ID da tarefa e o novo status do array recebido
$id = $data['id'] ?? null;       // Se o campo 'id' existir, armazena seu valor; senão, define como null
$status = $data['status'] ?? null; // Se o campo 'status' existir, armazena seu valor; senão, define como null

// Verifica se ambos os valores foram recebidos corretamente
if ($id && $status) {
  // Prepara a instrução SQL para atualizar o status da tarefa com base no ID
  $sql = "UPDATE tasks SET status = :status WHERE id = :id";
  $stmt = $conn->prepare($sql); // Prepara a query para evitar SQL Injection

  // Executa a query passando os parâmetros de forma segura
  $stmt->execute([':status' => $status, ':id' => $id]);

  // Retorna o código HTTP 200 (OK) indicando sucesso na operação
  http_response_code(200);
} else {
  // Se os dados estiverem incompletos, retorna o código HTTP 400 (Bad Request)
  http_response_code(400);
}
?>
