<?php
session_start();
include("../database/conn.php");

try {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $cpf = $_POST['cpf'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $data_nascimento = $_POST['data_nascimento'];

  $cep = $_POST['cep'];
  $logradouro = $_POST['logradouro'];
  $bairro = $_POST['bairro'];
  $cidade = $_POST['cidade'];
  $estado = $_POST['estado'];

  // Verifica se já existe uma conta com o mesmo CPF
  $verifica = $conn->prepare("SELECT id FROM usuarios WHERE cpf = :cpf");
  $verifica->bindParam(':cpf', $cpf);
  $verifica->execute();

  if ($verifica->rowCount() > 0) {
    $_SESSION['mensagem'] = "Ei! Já existe uma conta com esse CPF. Tenta fazer login 😉";
    $_SESSION['mensagem_tipo'] = "erro";
    header("Location: cadastro.php");
    exit;
  }

  // Insere novo usuário
  $sql = "INSERT INTO usuarios (nome, email, cpf, senha, data_nascimento, cep, logradouro, bairro, cidade, estado)
          VALUES (:nome, :email, :cpf, :senha, :data_nascimento, :cep, :logradouro, :bairro, :cidade, :estado)";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':nome', $nome);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':cpf', $cpf);
  $stmt->bindParam(':senha', $senha);
  $stmt->bindParam(':data_nascimento', $data_nascimento);
  $stmt->bindParam(':cep', $cep);
  $stmt->bindParam(':logradouro', $logradouro);
  $stmt->bindParam(':bairro', $bairro);
  $stmt->bindParam(':cidade', $cidade);
  $stmt->bindParam(':estado', $estado);

  $stmt->execute();

  $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
  $_SESSION['mensagem_tipo'] = "sucesso";
  header("Location: cadastro.php");
  exit;

} catch (PDOException $e) {
  $_SESSION['mensagem'] = "Erro ao cadastrar: " . $e->getMessage();
  $_SESSION['mensagem_tipo'] = "erro";
  header("Location: cadastro.php");
  exit;
}
?>
