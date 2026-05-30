-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 30 mai 2026 à 21:48
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `id_customer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`id_cart`, `created_at`, `id_customer`) VALUES
(1, '2026-05-24 10:00:00', 1),
(2, '2026-05-24 11:30:00', 2);

-- --------------------------------------------------------

--
-- Structure de la table `cart_items`
--

CREATE TABLE `cart_items` (
  `id_cart_items` int(11) NOT NULL,
  `quantity_cart_items` int(11) NOT NULL DEFAULT 1,
  `id_cart` int(11) NOT NULL,
  `id_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cart_items`
--

INSERT INTO `cart_items` (`id_cart_items`, `quantity_cart_items`, `id_cart`, `id_product`) VALUES
(3, 1, 2, 9),
(4, 1, 2, 4),
(5, 1, 1, 5),
(6, 1, 1, 4),
(7, 1, 1, 7);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int(11) NOT NULL,
  `name_categorie` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `name_categorie`) VALUES
(6, 'Accessories'),
(3, 'Audio'),
(5, 'Clothing'),
(1, 'Electronics'),
(8, 'phone'),
(7, 'phones'),
(4, 'Photography'),
(2, 'Watches');

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE `customers` (
  `id_customer` int(11) NOT NULL,
  `address_customer` varchar(255) DEFAULT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`id_customer`, `address_customer`, `id_user`) VALUES
(1, '12 Rue Hassan II, Casablanca', 4),
(2, '5 Avenue Mohammed V, Rabat', 5),
(3, '8 Rue Ibn Batouta, Fès', 6);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `date_order` datetime NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `id_customer` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `delivery_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id_order`, `date_order`, `order_status`, `id_customer`, `first_name`, `last_name`, `address`, `city`, `postal_code`, `delivery_method`) VALUES
(1, '2026-04-10 10:15:00', 'delivered', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2026-04-25 14:30:00', 'shipped', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '2026-05-18 09:00:00', 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '2026-03-20 11:00:00', 'delivered', 2, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '2026-05-01 16:45:00', 'processing', 2, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '2026-05-22 08:30:00', 'cancelled', 2, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '2026-04-05 13:20:00', 'delivered', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(8, '2026-05-15 17:00:00', 'shipped', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(9, '2026-05-23 10:10:00', 'pending', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '2026-05-25 16:41:13', 'pending', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'rrarar', '252525', 'Express Courier'),
(11, '2026-05-25 16:41:43', 'pending', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'tyu', '7851', 'Express Courier'),
(12, '2026-05-25 16:45:15', 'processing', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'afaf', '23445', 'Express Courier'),
(13, '2026-05-30 12:10:00', 'shipped', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'a;dkfja;lkjfa', '123424', 'Express Courier'),
(14, '2026-05-30 12:11:14', 'shipped', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'afafdfaf', '134244', 'Express Courier'),
(15, '2026-05-30 12:14:15', 'shipped', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'afasfadsf', '52525', 'Express Courier'),
(16, '2026-05-30 13:04:08', 'pending', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'Casablanca', '20201', 'Express Courier'),
(17, '2026-05-30 13:08:10', 'pending', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'Casablanca', '20021', 'Standard Editorial'),
(18, '2026-05-30 13:08:43', 'shipped', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'Casablanca', '20201', 'Express Courier'),
(19, '2026-05-30 15:16:16', 'delivered', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'Casablanca', '20201', 'Express Courier'),
(20, '2026-05-30 15:19:25', 'pending', 1, 'Ghita', 'El Mir', '12 Rue Hassan II, Casablanca', 'Deroua', '20021', 'Standard Editorial');

-- --------------------------------------------------------

--
-- Structure de la table `orders_items`
--

CREATE TABLE `orders_items` (
  `id_order_items` int(11) NOT NULL,
  `quantity_order_items` int(11) NOT NULL DEFAULT 1,
  `id_order` int(11) NOT NULL,
  `id_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders_items`
--

INSERT INTO `orders_items` (`id_order_items`, `quantity_order_items`, `id_order`, `id_product`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 6),
(3, 1, 2, 2),
(4, 1, 3, 4),
(5, 1, 3, 9),
(6, 1, 4, 5),
(7, 1, 4, 7),
(8, 2, 5, 10),
(9, 1, 5, 6),
(10, 1, 6, 3),
(11, 1, 7, 8),
(12, 1, 7, 9),
(13, 1, 8, 1),
(14, 3, 9, 6),
(15, 1, 9, 10),
(16, 1, 12, 2),
(17, 2, 12, 6),
(18, 1, 13, 2),
(19, 2, 13, 6),
(20, 1, 14, 2),
(21, 2, 14, 6),
(22, 1, 15, 2),
(23, 2, 15, 6),
(24, 1, 16, 9),
(25, 3, 17, 10),
(26, 2, 18, 5),
(27, 1, 19, 1),
(28, 1, 20, 6);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `name_product` varchar(150) NOT NULL,
  `description_product` text DEFAULT NULL,
  `quantity_product` int(11) NOT NULL DEFAULT 0,
  `product_image` varchar(255) DEFAULT NULL,
  `id_seller` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id_product`, `name_product`, `description_product`, `quantity_product`, `product_image`, `id_seller`, `id_categorie`, `price`) VALUES
(1, 'Zenith Quartz Minimalist', 'Montre élégante minimaliste avec bracelet en cuir.', 42, 'watche.jpg', 1, 2, 129.00),
(2, 'Acoustic Pro Wireless', 'Casque sans fil premium avec réduction de bruit.', 5, 'headset.jpg', 1, 3, 249.00),
(3, 'Lumix Core Prime', 'Appareil photo professionnel 24 MP.', 0, 'camera.jpg', 1, 4, 899.00),
(4, 'Smart Band Pro', 'Bracelet connecté avec capteur de fréquence cardiaque.', 25, 'band.jpg', 1, 1, 79.00),
(5, 'UltraBook X500', 'Ordinateur portable ultra-fin, Intel i7, 16 Go RAM.', 10, 'laptop.jpg', 1, 1, 1199.00),
(6, 'Gaming Mouse RGB', 'Souris gaming 16000 DPI, rétroéclairage RGB.', 30, 'mouse.jpg', 1, 1, 45.00),
(7, 'Leather Jacket Classic', 'Veste en cuir véritable, coupe droite.', 8, 'jacket.jpg', 2, 5, 349.00),
(8, 'Vintage Sunglasses', 'Lunettes de soleil UV400, monture tortoise.', 0, 'sunglasses.jpg', 2, 6, 59.00),
(9, 'Silk Scarf Premium', 'Foulard en soie, motifs géométriques.', 15, 'scarf.jpg', 2, 6, 89.00),
(10, 'Canvas Sneakers', 'Baskets en toile, semelle caoutchouc confort.', 20, 'sneakers.jpg', 2, 5, 75.00),
(23, 'Smart Phone', '', 12, 'smartphone-1780150012.jpg', 1, 8, 555.00);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id_role` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id_role`, `role_name`) VALUES
(1, 'admin'),
(2, 'seller'),
(3, 'customer');

-- --------------------------------------------------------

--
-- Structure de la table `sellers`
--

CREATE TABLE `sellers` (
  `id_seller` int(11) NOT NULL,
  `store_name` varchar(150) NOT NULL,
  `seller_rating` decimal(3,2) DEFAULT 0.00,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sellers`
--

INSERT INTO `sellers` (`id_seller`, `store_name`, `seller_rating`, `id_user`) VALUES
(1, 'AlaTech Store', 4.70, 2),
(2, 'Sara Fashion Hub', 4.30, 3);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `id_role` int(11) NOT NULL,
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'pending',
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `username`, `lastname`, `email`, `password`, `phone_number`, `id_role`, `status`, `profile_image`, `created_at`, `bio`) VALUES
(1, 'Admin', 'GAAM', 'admin@gaam.com', 'admin123', '0600000001', 1, 'active', NULL, '2025-01-01 08:00:00', 'Administrateur principal de la plateforme.'),
(2, 'Youssef', 'Alami', 'seller1@gaam.com', 'seller123', '0600000002', 2, 'active', NULL, '2025-02-10 09:00:00', 'Vendeur spécialisé en électronique.'),
(3, 'Sara', 'Bennani', 'seller2@gaam.com', 'seller123', '0600000003', 2, 'active', NULL, '2025-03-05 10:30:00', 'Mode et accessoires tendance.'),
(4, 'Ghita', 'El Mir', 'customer1@gaam.com', 'customer123', '0600000004', 3, 'active', NULL, '2025-04-01 11:00:00', NULL),
(5, 'Khalid', 'Mansouri', 'customer2@gaam.com', 'customer123', '0600000005', 3, 'active', NULL, '2025-05-10 14:00:00', NULL),
(6, 'Zineb', 'Moussaoui', 'customer3@gaam.com', 'customer123', '0600000006', 3, 'active', NULL, '2025-06-15 16:00:00', NULL),
(7, 'Hassan', 'Tazi', 'pending_seller@gaam.com', 'seller123', '0600000007', 2, 'pending', NULL, '2026-05-20 12:00:00', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD UNIQUE KEY `id_customer` (`id_customer`);

--
-- Index pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id_cart_items`),
  ADD KEY `id_cart` (`id_cart`),
  ADD KEY `id_product` (`id_product`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`),
  ADD UNIQUE KEY `name_categorie` (`name_categorie`);

--
-- Index pour la table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_customer`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Index pour la table `orders_items`
--
ALTER TABLE `orders_items`
  ADD PRIMARY KEY (`id_order_items`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_product` (`id_product`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `id_seller` (`id_seller`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id_seller`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id_cart_items` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `orders_items`
--
ALTER TABLE `orders_items`
  MODIFY `id_order_items` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id_seller` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`id_cart`) REFERENCES `cart` (`id_cart`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Contraintes pour la table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`);

--
-- Contraintes pour la table `orders_items`
--
ALTER TABLE `orders_items`
  ADD CONSTRAINT `orders_items_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_items_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`id_seller`) REFERENCES `sellers` (`id_seller`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`);

--
-- Contraintes pour la table `sellers`
--
ALTER TABLE `sellers`
  ADD CONSTRAINT `sellers_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
