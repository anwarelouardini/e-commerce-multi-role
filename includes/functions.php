<?php

require_once __DIR__ . '/db.php';

function e($value) {
    return htmlspecialchars($value, ENT_SUBSTITUTE, 'UTF-8');
}

function getUsers($pdo): ?array {
    $statement = $pdo->prepare('SELECT Users.id_user, Users.username, Users.lastname, Users.email, Users.phone_number, Users.status, Roles.role_name FROM Users JOIN Roles ON `Users`.`id_role` = `Roles`.`id_role`');
    $statement->execute();

    $results = $statement->fetchALL(PDO::FETCH_ASSOC);
    return $results;
}

function getRoleClass(string $role): string {
    return match($role) {
        'customer' => 'red',
        'admin' => 'blue',
        default => 'grey'
    };
}

function getStatusClass(string $status): string {
    return match($status) {
        'active' => 'active',
        'pending' => 'pending',
        default => 'inactive'
    };
}


