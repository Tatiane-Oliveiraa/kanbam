<?php
session_start();
include("../database/conn.php");

if (isset($_SESSION['usuario_id'])) {
  header("Location: ../home.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  try {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
      $_SESSION['usuario_id'] = $usuario['id'];
      header("Location: ../home.php");
      exit;
    } else {
      $_SESSION['mensagem'] = "E-mail ou senha incorretos!";
      $_SESSION['mensagem_tipo'] = "erro";
      header("Location: paginainicial.php");
      exit;
    }
  } catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro no login: " . $e->getMessage();
    $_SESSION['mensagem_tipo'] = "erro";
    header("Location: paginainicial.php");
    exit;
  }
}
?>
