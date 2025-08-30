<?php
// Define os dados de acesso ao banco de dados
$host = 'localhost';     // Endereço do servidor MySQL
$db   = 'kanbam';        // Nome do banco de dados
$user = 'root';          // Usuário do banco
$pass = '';              // Senha do banco (vazia por padrão no XAMPP)

// Cria a conexão usando PDO (interface segura e moderna)
try {
  $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

  // Define o modo de erro para lançar exceções
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  // Se houver erro na conexão, exibe a mensagem e encerra
  echo "Erro na conexão: " . $e->getMessage();
  exit;
}
?>
