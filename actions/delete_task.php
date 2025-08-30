<?php
// Inclui o arquivo de conexão com o banco de dados
include '../database/conn.php';

// Obtém o ID da tarefa via parâmetro GET na URL (ex: deletar.php?id=3)
// Se não for passado, a variável $id será null
$id = $_GET['id'] ?? null;

// Verifica se o ID foi fornecido
if ($id) {
  // Prepara a instrução SQL para deletar a tarefa com o ID correspondente
  $sql = "DELETE FROM tasks WHERE id = :id";
  $stmt = $conn->prepare($sql);

  // Executa a instrução, passando o ID como parâmetro
  $stmt->execute([':id' => $id]);
}

// Após a exclusão (ou se nenhum ID foi passado), redireciona o usuário para a página principal
header("Location: ../index.php");
exit;
?>
