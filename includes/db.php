<?php


$envFile = parse_ini_file(__DIR__ . '/../.env');

try {
    $pdo = new PDO("mysql:host={$envFile['DB_HOST']};port={$envFile['DB_PORT']};dbname={$envFile['DB_NAME']};charset=utf8mb4", $envFile['DB_USER'], $envFile['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
    ]);
} catch(PDOException $err) {
    echo 'A problem occured with the database connection...';
    die();
}