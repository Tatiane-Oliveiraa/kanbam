<?php
session_start();
include '../database/conn.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$task_id = $_GET['id'] ?? null;

if (!$usuario_id || !$task_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

try {
    $sql = "SELECT * FROM tasks WHERE id = :id AND usuario = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $task_id, ':usuario_id' => $usuario_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        http_response_code(404);
        echo json_encode(['error' => 'Tarefa não encontrada']);
        exit;
    }

    http_response_code(200);
    echo json_encode($task);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar tarefa: ' . $e->getMessage()]);
}
