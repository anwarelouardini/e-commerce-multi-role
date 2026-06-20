# 👤 EL OUARDINI Anwar — Dashboard Admin / Vendeur

> **Partie back-office** du projet E-Commerce Multi-Rôles  
> Université Mundiapolis — Cycle Ingénieur 1A | Module Développement Web | 2025–2026

---

## 📋 Responsabilité dans le projet

Je suis responsable de toute la partie **back-office** de la plateforme, c'est-à-dire l'interface d'administration et le dashboard vendeur. Ces deux espaces permettent de piloter l'ensemble de la plateforme e-commerce.

---

## 🗂️ Fichiers dont je suis responsable

### Pages Admin

```
pages/admin/
├── dashboard.php         → Tableau de bord principal (statistiques globales)
├── users.php             → Liste et gestion des utilisateurs
├── user-profile.php      → Profil détaillé d'un utilisateur
├── delete-user.php       → Suppression d'un utilisateur
├── sellers.php           → Gestion des vendeurs (activation / désactivation)
├── update-seller-status.php → Mise à jour du statut vendeur (AJAX)
└── settings.php          → Paramètres du compte administrateur
```

### Pages Vendeur

```
pages/vendor/
├── dashboard.php         → Tableau de bord vendeur (commandes, revenus)
├── add-product.php       → Ajout d'un nouveau produit
├── edit-product.php      → Modification d'un produit existant
├── delete-product.php    → Suppression d'un produit
├── product-overview.php  → Vue d'ensemble des produits du vendeur
├── add-category.php      → Ajout d'une catégorie
├── orders.php            → Gestion des commandes reçues
├── update-order-status.php → Mise à jour statut commande (AJAX)
└── seller-profile.php    → Profil du vendeur
```

### Fonctions back-end (dans `includes/functions.php`)

```php
// Admin
getUsers($pdo)                          // Récupérer tous les utilisateurs
getUserById($pdo, $id)                  // Récupérer un utilisateur par ID
deleteUserById($pdo, $id)               // Supprimer un utilisateur
getSellers($pdo)                        // Récupérer tous les vendeurs
updateSellerStatus($pdo, $id, $status)  // Activer / désactiver un vendeur
getDashboardStats($pdo)                 // Statistiques globales (revenus, utilisateurs, commandes)
getUserPerDay($pdo)                     // Inscriptions par jour (données pour le graphique)
getRevenueTrend(float $revenue)         // Tendance des revenus
getSellerHealthStatus(int $active, int $total) // Santé du réseau vendeurs
getPendingLevel(int $count)             // Niveau d'alerte vendeurs en attente
verifyAdminPassword($pdo, $adminId, $password) // Vérification mot de passe admin
updateAdminSettings($pdo, ...)          // Mise à jour profil admin

// Vendeur
getProductBySeller($pdo, $sellerId)     // Produits du vendeur connecté
addProduct($pdo, $data)                 // Créer un produit
updateProduct($pdo, $id, $data)         // Modifier un produit
deleteProduct($pdo, $id)               // Supprimer un produit
getOrdersBySeller($pdo, $sellerId)      // Commandes reçues par le vendeur
updateOrderStatus($pdo, $id, $status)  // Changer le statut d'une commande
getOrdersPerDay($pdo, $sellerId)        // Commandes par jour (graphique)
getSellerStats($pdo, $sellerId)         // Stats vendeur (CA, nb commandes)
getRevenuePerDay($pdo, $sellerId)       // Revenus par jour
getSellerById($pdo, $sellerId)         // Infos du vendeur connecté
getCategories($pdo)                     // Liste des catégories disponibles
```

### CSS associés

```
assets/css/pages/
├── dashboard-admin.css
├── sellers-admin.css
├── settings-admin.css
├── seller-profile.css
└── vendor-product-overview.css

assets/css/components/
├── cards.css             → Cartes statistiques
├── cards-total.css
├── chart.css             → Graphiques
└── status-indicator.css  → Indicateurs de statut
```

---

## ✅ Fonctionnalités développées

### 🔷 Dashboard Admin

| Fonctionnalité         | Description                                                        |
| ---------------------- | ------------------------------------------------------------------ |
| Statistiques globales  | Nombre d'utilisateurs, vendeurs actifs, commandes totales, revenus |
| Graphique inscriptions | Courbe des nouveaux utilisateurs par jour                          |
| Indicateur de tendance | Statut du revenu total (en hausse / stable / en baisse)            |
| Alertes vendeurs       | Niveau d'alerte sur les vendeurs en attente de validation          |

### 🔷 Gestion des Utilisateurs

| Fonctionnalité           | Description                                        |
| ------------------------ | -------------------------------------------------- |
| Liste des utilisateurs   | Affichage avec rôle, statut, et date d'inscription |
| Recherche en temps réel  | Filtrage JavaScript côté client                    |
| Voir le profil           | Détail complet d'un utilisateur                    |
| Supprimer un utilisateur | Suppression sécurisée avec confirmation            |

### 🔷 Gestion des Vendeurs

| Fonctionnalité          | Description                                    |
| ----------------------- | ---------------------------------------------- |
| Vue d'ensemble          | Compteurs : actifs / en attente / inactifs     |
| Validation des vendeurs | Activer ou désactiver un compte vendeur        |
| Mise à jour AJAX        | Changement de statut sans rechargement de page |

### 🔷 Dashboard Vendeur

| Fonctionnalité                 | Description                                          |
| ------------------------------ | ---------------------------------------------------- |
| Statistiques personnelles      | CA, nombre de commandes, produits actifs             |
| Graphiques revenus & commandes | Courbes par jour                                     |
| Gestion produits (CRUD)        | Ajouter, modifier, supprimer, consulter ses produits |
| Upload image produit           | Envoi et stockage de l'image produit                 |
| Gestion catégories             | Ajouter de nouvelles catégories                      |
| Gestion commandes              | Voir les commandes et mettre à jour leur statut      |

---

## 🔒 Sécurité mise en place

- Protection des pages admin/vendeur par vérification de session et de rôle (`$_SESSION['role']`)
- Requêtes préparées PDO sur toutes les fonctions (protection contre les injections SQL)
- Échappement des sorties HTML avec la fonction `e()` (protection XSS)
- Vérification du mot de passe actuel avant mise à jour des paramètres admin

---

## 🗄️ Tables de base de données utilisées

| Table         | Usage                                        |
| ------------- | -------------------------------------------- |
| `users`       | Récupération et suppression des utilisateurs |
| `sellers`     | Gestion des vendeurs et de leur statut       |
| `products`    | CRUD produits du vendeur                     |
| `categories`  | Association produit-catégorie                |
| `orders`      | Visualisation et mise à jour des commandes   |
| `order_items` | Détail des articles par commande             |

---

## ⚙️ Technologies utilisées

- **PHP** (PDO, sessions, traitement fichiers)
- **MySQL** (requêtes préparées)
- **JavaScript** (filtrage DOM, appels AJAX pour les mises à jour de statut)
- **CSS** (composants réutilisables : cards, charts, status indicators)

---

## 🚀 Comment tester ma partie

1. Se connecter avec un compte **Admin** → accès à `/pages/admin/dashboard.php`
2. Se connecter avec un compte **Vendeur** → accès à `/pages/vendor/dashboard.php`
3. Comptes de test disponibles dans le script `ecommerce.sql`

---

## 👨‍💻 Auteur

**EL OUARDINI Anwar** — Responsable Back-Office (Admin & Vendeur)  
Projet réalisé dans le cadre du module Développement Web — S6  
Université Mundiapolis, Campus Nouaceur | 2025–2026
