<?php
require_once './database/conn.php';

try {
    if (!isset($conn)) {
        throw new Exception('Erro: conexão com o banco não encontrada.');
    }

    $status = isset($_GET['status']) ? $_GET['status'] : '';

    if ($status && $status !== 'todos') {
        $stmt = $conn->prepare("SELECT titulo, descricao, data, status FROM tasks WHERE status = :status");
        $stmt->bindParam(':status', $status);
    } else {
        $stmt = $conn->prepare("SELECT titulo, descricao, data, status FROM tasks");
    }

    $stmt->execute();
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tarefas);

} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro ao gerar relatório: ' . $e->getMessage()]);
}
