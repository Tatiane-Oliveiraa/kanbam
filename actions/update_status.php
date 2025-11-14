<?php
session_start();
include '../database/conn.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data->id) && isset($data->status) && isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "UPDATE tasks SET status = :status WHERE id = :id AND usuario = :usuario_id";
    $stmt = $conn->prepare($sql);

    $params = [
        ':status' => $data->status,
        ':id' => $data->id,
        ':usuario_id' => $usuario_id
    ];

    if ($stmt->execute($params)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => $stmt->errorInfo()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Dados incompletos ou sessão inválida',
        'debug' => [
            'id' => $data->id ?? null,
            'status' => $data->status ?? null,
            'usuario_id' => $_SESSION['usuario_id'] ?? null
        ]
    ]);
}