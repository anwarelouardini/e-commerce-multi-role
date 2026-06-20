<?php

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id_seller'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Récupérer le nom depuis le corps JSON
$body = json_decode(file_get_contents('php://input'), true);
$name = ucfirst(strtolower(trim($body['name'] ?? '')));

if (empty($name)) {
    http_response_code(400);
    echo json_encode(['error' => 'Category name is required']);
    exit;
}

// Vérifier si elle existe déjà (éviter les doublons)
$check = $pdo->prepare('SELECT id_categorie, name_categorie FROM categories WHERE name_categorie = ? LIMIT 1');
$check->execute([$name]);
$existing = $check->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    echo json_encode(['id' => $existing['id_categorie'], 'name' => $existing['name_categorie']]);
    exit;
}

// Insérer la nouvelle catégorie
$stmt = $pdo->prepare('INSERT INTO categories (name_categorie) VALUES (?)');
$stmt->execute([$name]);
$newId = (int) $pdo->lastInsertId();

echo json_encode(['id' => $newId, 'name' => $name]);