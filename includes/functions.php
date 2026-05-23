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

function getOrdersStatusClass(string $status): string {
    return match($status) {
        'pending' => 'yellow',
        'shipped' => 'blue',
        'processing' => 'brown',
        'delivered' => 'green',
        'cancelled' => 'red'
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
    $statement = $pdo->prepare('DELETE FROM Users WHERE id_user = :id');
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

function getProductBySeller($pdo, $sellerId) {
    $statement = $pdo->prepare("
        SELECT id_product, name_product, description_product, quantity_product, product_image, price, Categories.name_categorie FROM Products JOIN categories ON Products.id_categorie = Categories.id_categorie WHERE id_seller = :id
    ");

    $statement->bindValue(':id', $sellerId);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function deleteProduct($pdo, $id) {
    $statement = $pdo->prepare("DELETE FROM Products WHERE id_product = :id");
    $statement->bindValue(':id', $id);
    $statement->execute();
}

function getCategories($pdo) {
    $statement = $pdo->prepare('SELECT * FROM categories');

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC
    );
    return $result;
}

function addProduct($pdo, $data) {
    $statement = $pdo->prepare("
        INSERT INTO Products (name_product, description_product, quantity_product, product_image, id_seller, id_categorie, price) VALUES
        (:name, :description, :qte, :img, :sellerId, :categorieId, :price)
    ");

    $statement->bindValue(':name', $data['name']);
    $statement->bindValue(':description', $data['description']);
    $statement->bindValue(':qte', $data['quantity']);
    $statement->bindValue(':img', $data['image']);
    $statement->bindValue(':sellerId', $data['seller_id']);
    $statement->bindValue(':categorieId', $data['id_categorie']);
    $statement->bindValue(':price', $data['price']);
    $statement->execute();
}

function updateProduct($pdo, $id, $data) {
    $statement = $pdo->prepare("
        UPDATE products 
        SET name_product = :name, description_product = :description, quantity_product = :qte, product_image = :img, id_categorie = :id_categorie, price = :price
        WHERE id_product = :id
    ");

    $statement->bindValue(':name', $data['name']);
    $statement->bindValue(':description', $data['description']);
    $statement->bindValue(':qte', $data['quantity']);
    $statement->bindValue(':img', $data['image']);
    $statement->bindValue(':id_categorie', $data['id_categorie']);
    $statement->bindValue(':price', $data['price']);
    $statement->bindValue(':id', $id);
    $statement->execute();
}

function getOrdersBySeller($pdo, $sellerId) {
    $statement = $pdo->prepare("
    SELECT Orders.id_order, Orders.date_order, Orders.order_status, Orders_items.quantity_order_items, Products.id_product, Products.name_product, Products.description_product, Products.quantity_product, Products.product_image, Products.price, Sellers.id_seller, Sellers.store_name, Sellers.seller_rating, customer_user.username, customer_user.lastname FROM Orders JOIN Orders_items ON Orders.id_order = Orders_items.id_order 
    JOIN Products ON Orders_items.id_product = Products.id_product 
    JOIN Sellers ON Products.id_seller = Sellers.id_seller 
    JOIN Customers ON Orders.id_customer = Customers.id_customer 
    JOIN Users AS customer_user ON Customers.id_user = customer_user.id_user 
    WHERE Sellers.id_seller = :id
    ");

    $statement->bindValue(':id', $sellerId);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

function updateOrderStatus($pdo, $id, $status) {
    $statement = $pdo->prepare("
        UPDATE orders
        SET order_status = :status WHERE id_order = :id
    ");

    $statement->bindValue(':status', $status);
    $statement->bindValue(':id', $id);
    $statement->execute();
}

function getOrdersPerDay($pdo, $sellerId) {
    $statement = $pdo->prepare("
    SELECT DATE(Orders.date_order) as day, COUNT(DISTINCT Orders.id_order) as count 
    FROM Orders 
    JOIN Orders_items ON Orders.id_order = Orders_items.id_order
    JOIN Products ON Orders_items.id_product = Products.id_product
    JOIN Sellers ON Products.id_seller = Sellers.id_seller
    WHERE Sellers.id_seller = :id
    AND Orders.date_order >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(Orders.date_order)
    ORDER BY day ASC
    ");

    $statement->bindValue(':id', $sellerId);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

function getSellerById($pdo, $sellerId) {
    $statement = $pdo->prepare("
        SELECT username, lastname, store_name, seller_rating FROM Users JOIN Sellers ON Users.id_user = Sellers.id_user WHERE Sellers.id_seller = :id
    ");

    $statement->bindValue(':id', $sellerId);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function getSellerStats($pdo, $sellerId) {

    $statement = $pdo->prepare("
        SELECT 
        COUNT(DISTINCT o.id_order) AS total_order,
        SUM(p.price * oi.quantity_order_items) AS total_revenue,
        COALESCE(
            SUM(p.price * oi.quantity_order_items) 
            / NULLIF(COUNT(DISTINCT o.id_order), 0),
            0
        ) AS avg_order_value
        FROM products p
        INNER JOIN orders_items oi ON p.id_product = oi.id_product

        INNER JOIN orders o ON oi.id_order = o.id_order
        WHERE p.id_seller = :sellerId
    ");

    $statement->bindValue(':sellerId', $sellerId);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function getRevenuePerDay($pdo, $sellerId) {
    $statement = $pdo->prepare("
        SELECT DATE(Orders.date_order) as day, SUM(price * quantity_order_items) as count 
        FROM Orders 
        JOIN Orders_items ON Orders.id_order = Orders_items.id_order
        JOIN Products ON Orders_items.id_product = Products.id_product
        JOIN Sellers ON Products.id_seller = Sellers.id_seller
        WHERE Sellers.id_seller = :id
        AND Orders.date_order >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(Orders.date_order)
        ORDER BY day ASC
    ");

    $statement->bindValue(':id', $sellerId);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}