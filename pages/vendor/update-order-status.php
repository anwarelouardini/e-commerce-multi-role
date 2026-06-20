<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$status = e($data['status']);

if(!in_array($status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {
    echo json_encode(['success' => false]);
    exit();
}

updateOrderStatus($pdo, $id, $status);
echo json_decode(['success' => true]);


