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

function getSellerStatusClass(string $status): string {
    return match($status) {
        'active' => 'green',
        'inactive' => 'red',
        default => 'grey'
    };
}

function getUserById($pdo, $id) {
    $statement = $pdo->prepare('SELECT Users.username, Users.lastname, Users.email, Users.profile_image, Users.created_at, Roles.role_name FROM users JOIN Roles ON Users.id_role = Roles.id_role WHERE Users.id_user=:id');
    $statement->bindValue(':id', $id);
    
    $statement->execute();
    $result = $statement->fetch();

    return $result;
}

function deleteUserById($pdo, $id) {
    $statement = $pdo->prepare('DELETE FROM Users WHERE :id');
    $statement->bindValue(':id', $id);
    $statement->execute();
}

function getSellers($pdo) {
    $statement = $pdo->prepare('SELECT Users.id_user, Users.username, Users.lastname, Users.status, Users.created_at, Sellers.store_name, Sellers.seller_rating, Roles.role_name FROM Users JOIN Sellers ON Users.id_user = Sellers.id_user JOIN Roles ON Roles.id_role = Users.id_role');
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    return $result;
}

function updateSellerStatus($pdo, $id, $status) {
    $statement = $pdo->prepare('UPDATE Users SET status = :status WHERE id_user = :id');
    $statement->bindValue(':status', $status);
    $statement->bindValue(':id', $id);

    $statement->execute();
}

function getDashboardStats($pdo) {
    $statement = $pdo->prepare("
        SELECT 
            COUNT(*) AS total_users,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_approvals,
            SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) 
                AND YEAR(created_at) = YEAR(NOW()) THEN 1 ELSE 0 END) AS new_users,
            (SELECT COUNT(*) 
            FROM Sellers 
            JOIN Users ON Sellers.id_user = Users.id_user 
            WHERE Users.status = 'active') AS active_sellers
        FROM Users
    ");
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getSellerHealthStatus(int $active, int $totalSellers) {
    $ratio = $active / max($totalSellers, 1) * 100;

    if($ratio < 50) {
        return ['label' => 'At Risk', 'class' => 'red'];
    } else if($ratio >= 50 && $ratio < 80) {
        return ['label' => 'Stable', 'class' => 'grey'];
    } 

    return ['label' => 'Thriving', 'class' => 'green'];
}

function getPendingLevel(int $count): array {
    if($count === 0) {
        return ['label' => 'None', 'class' => 'green'];
    } else if ($count >= 1 && $count <= 5) {
        return ['label' => 'Low', 'class' => 'grey'];
    } else if($count >= 6 && $count <= 10) {
        return ['label'  => 'Medium', 'class' => 'brown'];
    }
    return ['label' => 'High', 'class' => 'red'];
}

function getUserPerDay($pdo) {
    $statement = $pdo->prepare("
        SELECT DATE(created_at) as day, COUNT(*) as count FROM Users
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY day ASC
    ");

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    return $result;
}

function getAdminById($pdo, $id) {
    $statement = $pdo->prepare("
    SELECT Users.username, Users.lastname, Users.email, Users.bio FROM Users JOIN Roles
    ON Users.id_role = Roles.id_role
    WHERE id_user = :id
    ");
    
    $statement->bindValue(':id', $id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function updateAdmin($pdo, $id, $data) {
    $statement = $pdo->prepare("
    UPDATE Users
    SET username = :username, lastname = :lastname, email = :email, bio = :bio
    WHERE id_user = :id
    ");

    $statement->bindValue(':username', $data['firstname']);
    $statement->bindValue(':lastname', $data['lastname']);
    $statement->bindValue(':email', $data['email']);
    $statement->bindValue(':bio', $data['bio']);
    $statement->bindValue(':id', $id);

    $statement->execute();
}

function updateAdminPassword($pdo, $id, $password) {
    $statement = $pdo->prepare("
    UPDATE Users
    SET password = :password
    WHERE id_user = :id
    ");

    $statement->bindValue(':password', $password);
    $statement->bindValue(':id', $id);
    $statement->execute();
}