-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 20 juin 2026 à 13:27
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
(7, 'Footwear'),
(8, 'Home & Living'),
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
(1, '10 Rue Hassan II, Fès', 14),
(2, '112 Boulevard Zerktouni, Agadir', 15),
(3, '18 Avenue Mohammed V, Fès', 16),
(4, '24 Rue Allal Ben Abdellah, Agadir', 17),
(5, '109 Rue Hassan II, Rabat', 18),
(6, '145 Rue Hassan II, Rabat', 19),
(7, '58 Rue Allal Ben Abdellah, Agadir', 20),
(8, '16 Rue Allal Ben Abdellah, Casablanca', 21),
(9, '150 Boulevard Zerktouni, Kénitra', 22),
(10, '13 Avenue Mohammed V, Oujda', 23),
(11, '12 Rue Allal Ben Abdellah, Kénitra', 24),
(12, '35 Rue Ibn Batouta, Tétouan', 25),
(13, '108 Avenue Mohammed V, Tétouan', 26),
(14, '139 Rue Hassan II, Rabat', 27),
(15, '147 Rue Ibn Batouta, Marrakech', 28),
(16, '144 Avenue Mohammed V, Rabat', 29),
(17, '27 Rue Allal Ben Abdellah, Rabat', 30),
(18, '147 Avenue Mohammed V, Tanger', 31),
(19, '96 Rue Hassan II, Agadir', 32),
(20, '141 Rue Hassan II, Agadir', 33),
(21, '145 Rue Hassan II, Marrakech', 34),
(22, '159 Avenue Mohammed V, Rabat', 35),
(23, '128 Rue Allal Ben Abdellah, Kénitra', 36),
(24, '110 Rue Ibn Batouta, Fès', 37),
(25, '120 Rue Allal Ben Abdellah, Oujda', 38);

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
(1, '2026-06-02 04:46:00', 'shipped', 9, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2026-06-02 07:43:00', 'delivered', 25, 'Hiba', 'Belmir', '24 Rue Allal Ben Abdellah, Oujda', 'Oujda', '22676', 'Standard Delivery'),
(3, '2026-06-06 05:55:00', 'shipped', 21, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '2026-06-13 08:55:00', 'pending', 4, 'Hassan', 'Tazi', '117 Rue Ibn Batouta, Agadir', 'Agadir', '31319', 'Standard Delivery'),
(5, '2026-06-15 21:50:00', 'shipped', 8, 'Sanaa', 'Hajji', '98 Rue Ibn Batouta, Casablanca', 'Casablanca', '93886', 'Express Courier'),
(6, '2026-06-09 15:47:00', 'pending', 19, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '2026-06-14 13:45:00', 'processing', 18, 'Tarik', 'Naciri', '150 Rue Allal Ben Abdellah, Tanger', 'Tanger', '86484', 'Standard Delivery'),
(8, '2026-05-27 13:20:00', 'delivered', 22, 'Ayoub', 'Bouzid', '17 Rue Allal Ben Abdellah, Rabat', 'Rabat', '60019', 'Standard Delivery'),
(9, '2026-06-02 00:12:00', 'delivered', 21, 'Meriem', 'El Amrani', '76 Rue Allal Ben Abdellah, Marrakech', 'Marrakech', '30730', 'Standard Delivery'),
(10, '2026-06-12 01:12:00', 'delivered', 4, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '2026-06-10 12:26:00', 'cancelled', 17, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '2026-06-15 23:55:00', 'delivered', 16, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '2026-06-14 01:05:00', 'processing', 7, NULL, NULL, NULL, NULL, NULL, NULL),
(14, '2026-06-03 03:03:00', 'shipped', 4, 'Hassan', 'Tazi', '17 Rue Ibn Batouta, Agadir', 'Agadir', '12757', 'Express Courier'),
(15, '2026-06-16 15:03:00', 'delivered', 11, 'Yasmine', 'Houssini', '61 Rue Ibn Batouta, Kénitra', 'Kénitra', '97684', 'Standard Delivery'),
(16, '2026-06-05 10:10:00', 'shipped', 14, 'Mehdi', 'Benjelloun', '25 Rue Allal Ben Abdellah, Rabat', 'Rabat', '56438', 'Standard Delivery'),
(17, '2026-06-15 18:39:00', 'pending', 4, 'Hassan', 'Tazi', '49 Rue Zerktouni, Agadir', 'Agadir', '68800', 'Express Courier'),
(18, '2026-06-16 00:07:00', 'delivered', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(19, '2026-06-13 05:03:00', 'delivered', 13, 'Houda', 'Fassi', '100 Rue Ibn Batouta, Tétouan', 'Tétouan', '69638', 'Standard Delivery'),
(20, '2026-06-14 11:57:00', 'shipped', 18, 'Tarik', 'Naciri', '81 Rue Hassan II, Tanger', 'Tanger', '16572', 'Standard Delivery'),
(21, '2026-06-16 15:17:00', 'pending', 13, 'Houda', 'Fassi', '146 Rue Mohammed V, Tétouan', 'Tétouan', '85880', 'Express Courier'),
(22, '2026-06-05 01:40:00', 'cancelled', 7, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '2026-06-12 07:01:00', 'pending', 3, 'Zineb', 'Moussaoui', '160 Rue Zerktouni, Fès', 'Fès', '23104', 'Express Courier'),
(24, '2026-05-26 10:37:00', 'delivered', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '2026-05-29 08:01:00', 'processing', 5, 'Fatima', 'Chraibi', '28 Rue Zerktouni, Rabat', 'Rabat', '30374', 'Standard Delivery'),
(26, '2026-06-04 09:28:00', 'shipped', 2, 'Khalid', 'Mansouri', '109 Rue Ibn Batouta, Agadir', 'Agadir', '15778', 'Express Courier'),
(27, '2026-06-08 19:33:00', 'shipped', 4, 'Hassan', 'Tazi', '177 Rue Mohammed V, Agadir', 'Agadir', '81511', 'Express Courier'),
(28, '2026-06-11 16:03:00', 'delivered', 22, 'Ayoub', 'Bouzid', '27 Rue Ibn Batouta, Rabat', 'Rabat', '83385', 'Standard Delivery'),
(29, '2026-05-26 17:49:00', 'processing', 11, NULL, NULL, NULL, NULL, NULL, NULL),
(30, '2026-06-15 16:06:00', 'shipped', 7, NULL, NULL, NULL, NULL, NULL, NULL),
(31, '2026-06-14 07:43:00', 'pending', 25, 'Hiba', 'Belmir', '165 Rue Zerktouni, Oujda', 'Oujda', '62386', 'Standard Delivery'),
(32, '2026-06-14 09:58:00', 'delivered', 14, 'Mehdi', 'Benjelloun', '81 Rue Allal Ben Abdellah, Rabat', 'Rabat', '89457', 'Express Courier'),
(33, '2026-05-24 00:17:00', 'processing', 24, NULL, NULL, NULL, NULL, NULL, NULL),
(34, '2026-05-23 22:40:00', 'processing', 4, NULL, NULL, NULL, NULL, NULL, NULL),
(35, '2026-06-02 00:52:00', 'delivered', 22, NULL, NULL, NULL, NULL, NULL, NULL),
(36, '2026-06-06 17:41:00', 'delivered', 14, NULL, NULL, NULL, NULL, NULL, NULL),
(37, '2026-06-14 15:42:00', 'shipped', 21, NULL, NULL, NULL, NULL, NULL, NULL),
(38, '2026-06-03 10:09:00', 'delivered', 1, 'Ghita', 'El Mir', '122 Rue Zerktouni, Fès', 'Fès', '19545', 'Standard Delivery'),
(39, '2026-06-12 21:16:00', 'delivered', 25, NULL, NULL, NULL, NULL, NULL, NULL),
(40, '2026-06-12 19:27:00', 'shipped', 18, 'Tarik', 'Naciri', '32 Rue Allal Ben Abdellah, Tanger', 'Tanger', '27477', 'Standard Delivery'),
(41, '2026-05-28 00:32:00', 'pending', 24, NULL, NULL, NULL, NULL, NULL, NULL),
(42, '2026-06-04 09:32:00', 'delivered', 10, 'Adam', 'Sahmi', '86 Rue Ibn Batouta, Oujda', 'Oujda', '80798', 'Express Courier'),
(43, '2026-06-14 11:56:00', 'shipped', 11, NULL, NULL, NULL, NULL, NULL, NULL),
(44, '2026-05-26 23:36:00', 'shipped', 12, 'Anas', 'Ouardini', '100 Rue Allal Ben Abdellah, Tétouan', 'Tétouan', '80545', 'Express Courier'),
(45, '2026-06-02 05:14:00', 'delivered', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(46, '2026-06-06 17:55:00', 'processing', 5, NULL, NULL, NULL, NULL, NULL, NULL),
(47, '2026-06-13 07:39:00', 'pending', 13, 'Houda', 'Fassi', '108 Rue Ibn Batouta, Tétouan', 'Tétouan', '20735', 'Standard Delivery'),
(48, '2026-06-16 06:46:00', 'processing', 25, NULL, NULL, NULL, NULL, NULL, NULL),
(49, '2026-05-22 11:07:00', 'delivered', 5, 'Fatima', 'Chraibi', '122 Rue Hassan II, Rabat', 'Rabat', '83920', 'Express Courier'),
(50, '2026-06-01 12:41:00', 'delivered', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(51, '2026-06-15 08:06:00', 'processing', 4, NULL, NULL, NULL, NULL, NULL, NULL),
(52, '2026-06-09 06:56:00', 'shipped', 11, 'Yasmine', 'Houssini', '108 Rue Allal Ben Abdellah, Kénitra', 'Kénitra', '23833', 'Standard Delivery');

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
(1, 1, 1, 25),
(2, 1, 2, 20),
(3, 3, 2, 9),
(4, 2, 2, 26),
(5, 1, 3, 19),
(6, 1, 3, 7),
(7, 3, 3, 23),
(8, 3, 4, 7),
(9, 3, 4, 22),
(10, 3, 4, 9),
(11, 1, 5, 27),
(12, 1, 5, 25),
(13, 2, 5, 2),
(14, 2, 6, 7),
(15, 3, 6, 21),
(16, 2, 6, 16),
(17, 2, 7, 8),
(18, 1, 7, 5),
(19, 1, 7, 17),
(20, 3, 8, 18),
(21, 3, 8, 28),
(22, 1, 8, 1),
(23, 3, 9, 24),
(24, 3, 10, 27),
(25, 1, 10, 21),
(26, 1, 10, 17),
(27, 2, 11, 16),
(28, 2, 11, 1),
(29, 1, 11, 4),
(30, 2, 12, 5),
(31, 3, 12, 22),
(32, 2, 13, 23),
(33, 3, 13, 10),
(34, 3, 14, 1),
(35, 3, 14, 3),
(36, 3, 15, 18),
(37, 3, 15, 5),
(38, 3, 16, 15),
(39, 3, 16, 28),
(40, 3, 16, 24),
(41, 1, 16, 2),
(42, 1, 17, 6),
(43, 2, 17, 9),
(44, 3, 17, 15),
(45, 1, 17, 8),
(46, 2, 18, 6),
(47, 2, 18, 14),
(48, 3, 19, 23),
(49, 3, 19, 24),
(50, 2, 19, 26),
(51, 1, 19, 18),
(52, 1, 20, 2),
(53, 1, 20, 17),
(54, 3, 21, 14),
(55, 1, 22, 8),
(56, 3, 22, 9),
(57, 3, 22, 13),
(58, 1, 23, 17),
(59, 2, 23, 9),
(60, 3, 24, 20),
(61, 1, 24, 26),
(62, 3, 24, 21),
(63, 2, 25, 20),
(64, 1, 25, 7),
(65, 3, 25, 23),
(66, 2, 26, 25),
(67, 1, 26, 5),
(68, 3, 26, 21),
(69, 2, 27, 19),
(70, 1, 27, 18),
(71, 1, 27, 5),
(72, 1, 28, 8),
(73, 1, 28, 28),
(74, 1, 29, 26),
(75, 2, 29, 22),
(76, 1, 29, 28),
(77, 3, 29, 24),
(78, 1, 30, 12),
(79, 1, 30, 10),
(80, 1, 30, 27),
(81, 3, 30, 26),
(82, 2, 31, 4),
(83, 3, 32, 19),
(84, 2, 32, 7),
(85, 1, 32, 9),
(86, 3, 32, 2),
(87, 1, 33, 12),
(88, 3, 33, 14),
(89, 2, 34, 17),
(90, 2, 34, 10),
(91, 2, 34, 22),
(92, 2, 35, 20),
(93, 2, 35, 19),
(94, 3, 36, 15),
(95, 2, 36, 22),
(96, 3, 36, 7),
(97, 1, 37, 27),
(98, 3, 38, 21),
(99, 2, 38, 19),
(100, 2, 38, 7),
(101, 2, 38, 23),
(102, 2, 39, 25),
(103, 3, 40, 25),
(104, 3, 40, 15),
(105, 2, 40, 20),
(106, 3, 41, 9),
(107, 2, 41, 25),
(108, 3, 41, 8),
(109, 2, 41, 27),
(110, 2, 42, 5),
(111, 3, 42, 8),
(112, 2, 43, 2),
(113, 3, 43, 7),
(114, 3, 43, 27),
(115, 1, 43, 14),
(116, 1, 44, 8),
(117, 2, 44, 9),
(118, 2, 44, 14),
(119, 3, 44, 16),
(120, 3, 45, 13),
(121, 2, 46, 2),
(122, 2, 46, 9),
(123, 3, 47, 24),
(124, 1, 48, 25),
(125, 1, 49, 23),
(126, 3, 49, 9),
(127, 3, 49, 25),
(128, 3, 49, 12),
(129, 3, 50, 13),
(130, 3, 50, 23),
(131, 3, 50, 7),
(132, 1, 50, 3),
(133, 3, 51, 12),
(134, 3, 52, 21),
(135, 1, 52, 27),
(136, 2, 52, 15);

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
(1, 'Zenith Quartz Minimalist', 'Montre elegante minimaliste avec bracelet en cuir.', 25, 'watche.jpg', 1, 2, 129.00),
(2, 'Acoustic Pro Wireless', 'Casque sans fil premium avec reduction de bruit.', 0, 'headset.jpg', 2, 3, 249.00),
(3, 'Studio Headset Edition', 'Casque audio studio, confort longue duree.', 0, 'headset-1779624848.jpg', 3, 3, 189.00),
(4, 'Lumix Core Prime', 'Appareil photo professionnel 24 MP.', 30, 'camera.jpg', 6, 4, 899.00),
(5, 'Lumix Travel Compact', 'Appareil photo compact, ideal voyage.', 8, 'camera-1779624618.jpg', 8, 4, 459.00),
(6, 'Smart Band Pro', 'Bracelet connecte avec capteur de frequence cardiaque.', 5, 'band.jpg', 9, 1, 79.00),
(7, 'UltraBook X500', 'Ordinateur portable ultra-fin, Intel i7, 16 Go RAM.', 5, 'laptop.jpg', 10, 1, 1199.00),
(8, 'Gaming Mouse RGB', 'Souris gaming 16000 DPI, retroeclairage RGB.', 3, 'mouse.jpg', 11, 1, 45.00),
(9, 'Razer Firefly V2 Chroma', 'Tapis de souris gaming illumine RGB.', 30, 'razerfireflyv2chroma-1779622870.jpg', 1, 1, 39.00),
(10, 'Logitech G240 Cloth Pad', 'Tapis de souris gaming surface tissu.', 0, 'logitechg240clothgamingmousepad-1779030340.jpg', 2, 1, 19.00),
(11, 'X10 Mouse Lite Noir', 'Souris sans fil legere, design epure.', 25, 'X10MouseLiteNoir-1779045383.webp', 3, 1, 29.00),
(12, 'Leather Jacket Classic', 'Veste en cuir veritable, coupe droite.', 30, 'jacket.jpg', 6, 5, 349.00),
(13, 'Vintage Sunglasses', 'Lunettes de soleil UV400, monture tortoise.', 18, 'sunglasses.jpg', 8, 6, 59.00),
(14, 'Silk Scarf Premium', 'Foulard en soie, motifs geometriques.', 0, 'scarf.jpg', 9, 6, 89.00),
(15, 'Canvas Sneakers', 'Baskets en toile, semelle caoutchouc confort.', 20, 'sneakers.jpg', 10, 7, 75.00),
(16, 'Smart Phone X12', 'Smartphone 128 Go, ecran AMOLED 6.5 pouces.', 12, 'smartphone-1780148304.jpg', 11, 1, 555.00),
(17, 'Smart Phone X12 Pro', 'Version Pro, 256 Go, triple capteur photo.', 0, 'smartphone-1780148465.jpg', 1, 1, 699.00),
(18, 'Smart Phone Lite', 'Smartphone entree de gamme, bon rapport qualite/prix.', 0, 'smartphone-1780148520.jpg', 2, 1, 349.00),
(19, 'Smart Phone Max', 'Grand ecran 6.8 pouces, batterie longue duree.', 0, 'smartphone-1780148672.jpg', 3, 1, 799.00),
(20, 'Smart Phone Mini', 'Format compact, performant au quotidien.', 5, 'smartphone-1780148783.jpg', 6, 1, 459.00),
(21, 'Smart Phone Edge', 'Ecran incurve, design premium.', 5, 'smartphone-1780148829.jpg', 8, 1, 849.00),
(22, 'Smart Phone Plus', 'Version intermediaire, excellent autonomie.', 18, 'smartphone-1780148904.jpg', 9, 1, 599.00),
(23, 'Smart Phone Vision', 'Camera grand angle, ideal photographes amateurs.', 20, 'smartphone-1780149085.jpg', 10, 1, 649.00),
(24, 'Smart Phone Air', 'Ultra-leger, finition aluminium.', 0, 'smartphone-1780149279.jpg', 11, 1, 529.00),
(25, 'Smart Phone Prime', 'Edition limitee, 512 Go de stockage.', 18, 'smartphone-1780150012.jpg', 1, 1, 899.00),
(26, 'Smart Phone Classic', 'Modele fiable, bon compromis performance/prix.', 5, 'smartphone-1780146134.jpg', 2, 1, 399.00),
(27, 'Smart Phone Eco', 'Edition eco-responsable, materiaux recycles.', 30, 'smartphone-1780146545.jpg', 3, 1, 379.00),
(28, 'Smart Phone Origin', 'Premiere generation revisitee.', 25, 'smartphone-1780145959.jpg', 6, 1, 329.00);

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
(1, 'AlaTech Store', 4.02, 2),
(2, 'Sara Fashion Hub', 3.80, 3),
(3, 'Idrissi Market', 4.45, 4),
(4, 'Casa Watches Co.', 0.00, 5),
(5, 'Atlas Sound Systems', 0.00, 6),
(6, 'Medina Leather Goods', 3.69, 7),
(7, 'Rif Electronics', 0.00, 8),
(8, 'Souk Style', 4.30, 9),
(9, 'Casablanca Gadgets', 4.08, 10),
(10, 'Marrakech Threads', 3.68, 11),
(11, 'Naciri Outdoors', 4.26, 12),
(12, 'Belhaj Home Decor', 0.00, 13);

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
(1, 'Admin', 'GAAM', 'admin@gaam.com', '$2b$10$/eR91SYjgU08i1iQtzirbueJvp1RzHOqBuv/Umg9zj3ywQSDvdiYi', '0600000001', 1, 'active', NULL, '2025-11-30 18:00:00', 'Administrateur principal de la plateforme.'),
(2, 'Youssef', 'Alami', 'youssef.seller1@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000002', 2, 'active', NULL, '2026-05-11 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(3, 'Sara', 'Bennani', 'sara.seller2@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000003', 2, 'active', NULL, '2026-05-12 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(4, 'Karim', 'Idrissi', 'karim.seller3@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000004', 2, 'active', NULL, '2026-04-26 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(5, 'Nadia', 'Tazi', 'nadia.seller4@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000005', 2, 'pending', NULL, '2026-06-16 11:00:00', 'Boutique partenaire GAAM specialisee.'),
(6, 'Hamza', 'Berrada', 'hamza.seller5@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000006', 2, 'pending', NULL, '2026-06-12 23:00:00', 'Boutique partenaire GAAM specialisee.'),
(7, 'Salma', 'Chraibi', 'salma.seller6@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000007', 2, 'active', NULL, '2026-05-13 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(8, 'Othmane', 'Fassi', 'othmane.seller7@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000008', 2, 'pending', NULL, '2026-06-13 12:00:00', 'Boutique partenaire GAAM specialisee.'),
(9, 'Imane', 'Cherkaoui', 'imane.seller8@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000009', 2, 'active', NULL, '2025-12-31 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(10, 'Reda', 'Amrani', 'reda.seller9@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000010', 2, 'active', NULL, '2026-02-01 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(11, 'Lina', 'Sebti', 'lina.seller10@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000011', 2, 'active', NULL, '2026-03-24 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(12, 'Yassine', 'Naciri', 'yassine.seller11@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000012', 2, 'active', NULL, '2026-01-25 18:00:00', 'Boutique partenaire GAAM specialisee.'),
(13, 'Soraya', 'Belhaj', 'soraya.seller12@gaam.com', '$2b$10$kahqUgc0kgg5yhkxq/e//.jz9XBdU1sZ/C5uZqvLgdwxN7Vl2FEd6', '0600000013', 2, 'inactive', NULL, '2026-06-13 10:00:00', 'Boutique partenaire GAAM specialisee.'),
(14, 'Ghita', 'El Mir', 'ghita.elmir@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000014', 3, 'active', NULL, '2026-06-16 18:00:00', NULL),
(15, 'Khalid', 'Mansouri', 'khalid.mansouri@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000015', 3, 'active', NULL, '2026-03-01 18:00:00', NULL),
(16, 'Zineb', 'Moussaoui', 'zineb.moussaoui@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000016', 3, 'active', NULL, '2026-04-07 18:00:00', NULL),
(17, 'Hassan', 'Tazi', 'hassan.tazi@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000017', 3, 'active', NULL, '2026-04-23 18:00:00', NULL),
(18, 'Fatima', 'Chraibi', 'fatima.chraibi@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000018', 3, 'active', NULL, '2026-05-22 18:00:00', NULL),
(19, 'Mohammed', 'Berrada', 'mohammed.berrada@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000019', 3, 'active', NULL, '2026-03-12 18:00:00', NULL),
(20, 'Nour', 'Tahiri', 'nour.tahiri@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000020', 3, 'active', NULL, '2026-03-18 18:00:00', NULL),
(21, 'Sanaa', 'Hajji', 'sanaa.hajji@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000021', 3, 'active', NULL, '2026-04-11 18:00:00', NULL),
(22, 'Iliass', 'Raji', 'iliass.raji@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000022', 3, 'active', NULL, '2026-02-20 18:00:00', NULL),
(23, 'Adam', 'Sahmi', 'adam.sahmi@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000023', 3, 'active', NULL, '2026-05-17 18:00:00', NULL),
(24, 'Yasmine', 'Houssini', 'yasmine.houssini@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000024', 3, 'active', NULL, '2026-05-28 18:00:00', NULL),
(25, 'Anas', 'Ouardini', 'anas.ouardini@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000025', 3, 'active', NULL, '2026-04-03 18:00:00', NULL),
(26, 'Houda', 'Fassi', 'houda.fassi@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000026', 3, 'active', NULL, '2026-03-17 18:00:00', NULL),
(27, 'Mehdi', 'Benjelloun', 'mehdi.benjelloun@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000027', 3, 'active', NULL, '2026-04-29 18:00:00', NULL),
(28, 'Rim', 'Saidi', 'rim.saidi@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000028', 3, 'active', NULL, '2026-06-06 18:00:00', NULL),
(29, 'Walid', 'El Khatib', 'walid.elkhatib@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000029', 3, 'active', NULL, '2026-04-04 18:00:00', NULL),
(30, 'Asmae', 'Rachidi', 'asmae.rachidi@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000030', 3, 'active', NULL, '2026-04-19 18:00:00', NULL),
(31, 'Tarik', 'Naciri', 'tarik.naciri@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000031', 3, 'active', NULL, '2026-03-12 18:00:00', NULL),
(32, 'Soukaina', 'Bensouda', 'soukaina.bensouda@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000032', 3, 'active', NULL, '2026-02-21 18:00:00', NULL),
(33, 'Othman', 'Lahlou', 'othman.lahlou@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000033', 3, 'active', NULL, '2026-05-07 18:00:00', NULL),
(34, 'Meriem', 'El Amrani', 'meriem.elamrani@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000034', 3, 'active', NULL, '2026-03-19 18:00:00', NULL),
(35, 'Ayoub', 'Bouzid', 'ayoub.bouzid@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000035', 3, 'active', NULL, '2026-04-10 18:00:00', NULL),
(36, 'Loubna', 'Kabbaj', 'loubna.kabbaj@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000036', 3, 'active', NULL, '2026-05-05 18:00:00', NULL),
(37, 'Driss', 'Sqalli', 'driss.sqalli@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000037', 3, 'active', NULL, '2026-04-16 18:00:00', NULL),
(38, 'Hiba', 'Belmir', 'hiba.belmir@mail.com', '$2b$10$7BtmfPP3nwsm05qy0z5cou7jOmH7JZaVRSJA2FB6RGufKrIsLtFWS', '0600000038', 3, 'active', NULL, '2026-02-19 18:00:00', NULL);

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
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id_cart_items` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `orders_items`
--
ALTER TABLE `orders_items`
  MODIFY `id_order_items` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id_seller` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
