<?php
session_start();
include '../database/conn.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data->id) && isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];

    // Montar UPDATE dinamicamente com os campos fornecidos
    $updates = [];
    $params = [':id' => $data->id, ':usuario_id' => $usuario_id];

    if (isset($data->status)) {
        $updates[] = "status = :status";
        $params[':status'] = $data->status;
    }
    if (isset($data->titulo)) {
        $updates[] = "titulo = :titulo";
        $params[':titulo'] = $data->titulo;
    }
    if (isset($data->descricao)) {
        $updates[] = "descricao = :descricao";
        $params[':descricao'] = $data->descricao;
    }
    if (isset($data->data)) {
        $updates[] = "data = :data";
        $params[':data'] = $data->data;
    }
    if (isset($data->hora)) {
        $updates[] = "hora = :hora";
        $params[':hora'] = $data->hora;
    }
    if (isset($data->categoria)) {
        $updates[] = "categoria = :categoria";
        $params[':categoria'] = $data->categoria;
    }

    if (empty($updates)) {
        echo json_encode([
            'success' => false,
            'error' => 'Nenhum campo para atualizar'
        ]);
        exit;
    }

    $sql = "UPDATE tasks SET " . implode(', ', $updates) . " WHERE id = :id AND usuario = :usuario_id";
    $stmt = $conn->prepare($sql);

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
        'error' => 'Dados incompletos ou sessão inválida'
    ]);
}
