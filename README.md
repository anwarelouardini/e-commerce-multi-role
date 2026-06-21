# 👤 EL MIR Ghita — Authentification & Gestion des Profils
**Partie Authentification du projet E-Commerce Multi-Rôles**  
Université Mundiapolis — Cycle Ingénieur 1A | Module Développement Web | 2025–2026

---

## 📋 Responsabilité dans le projet

Je suis responsable de toute la partie **authentification et gestion des profils** de la plateforme GAAM. Cette partie constitue le point d'entrée sécurisé de l'ensemble du site et couvre la connexion, l'inscription, la gestion des sessions, et les interfaces de profil pour les deux rôles principaux : **Customer** et **Seller**.

---

## 🗂️ Fichiers dont je suis responsable

### Pages Authentification
```
pages/authentification/
├── login.php           → Formulaire de connexion avec gestion de session
├── auth-signup.php     → Formulaire d'inscription (Customer / Seller)
├── auth.js             → Validation JavaScript côté client
└── logout.php          → Destruction de session et redirection
```

### Page Profil Customer
```
pages/customer/
├── user-profil.php     → Profil utilisateur avec commandes et infos personnelles
└── logout.php          → Déconnexion depuis le profil customer
```

### Page Profil Vendeur
```
pages/vendor/
├── seller-profile.php  → Profil vendeur avec stats boutique et produits
└── logout.php          → Déconnexion depuis le profil vendeur
```

### Fichier partagé avec l'équipe
```
includes/
└── db.php              → Connexion PDO partagée (utilise le fichier .env)
```

---

## ✅ Fonctionnalités développées

### 🔷 Connexion (login.php)

| Fonctionnalité | Description |
|---|---|
| Authentification sécurisée | Vérification email + mot de passe via `password_verify()` |
| Gestion des rôles | Redirection automatique selon le rôle (Customer / Seller / Admin) |
| Vérification du statut | Blocage des comptes `pending` ou `inactive` avec message d'erreur |
| Session PHP | Création de session avec `user_id`, `role`, `username`, `email`, `profile_image` |
| Validation JS | Vérification des champs avant soumission côté client |
| Protection anti-boucle | Redirection si l'utilisateur est déjà connecté |

### 🔷 Inscription (auth-signup.php)

| Fonctionnalité | Description |
|---|---|
| Double rôle | Sélection Customer ou Seller via un select dynamique |
| Champ Store Name | Apparaît/disparaît en JavaScript selon le rôle sélectionné |
| Hashage du mot de passe | `password_hash()` avec algorithme BCRYPT |
| Statut automatique | Customer → `active` directement / Seller → `pending` (approbation admin requise) |
| Transaction PDO | Insertion atomique dans `users` + `customers` ou `sellers` |
| Message d'attente | Redirection vers login avec message pour les sellers en attente |

### 🔷 Profil Utilisateur Customer (user-profil.php)

| Fonctionnalité | Description |
|---|---|
| Données dynamiques | Nom, email, téléphone, adresse et date d'inscription depuis la BDD |
| Statistiques commandes | Nombre total de commandes et commandes en transit |
| Commandes récentes | Tableau des 5 dernières commandes avec statut, date et montant |
| Modal d'édition | Modification du prénom, nom, téléphone, adresse et bio |
| Mise à jour BDD | Requêtes `UPDATE` sur les tables `users` et `customers` |
| Protection d'accès | Redirection vers login si non connecté, redirection vers seller-profile si role=seller |

### 🔷 Profil Vendeur Seller (seller-profile.php)

| Fonctionnalité | Description |
|---|---|
| Infos boutique dynamiques | Nom de la boutique, rating, nombre de ventes et nombre de produits |
| Bio / description | Texte personnalisé de la boutique depuis la BDD |
| Stock affiché | Produits du vendeur avec images, prix et catégories |
| Modal d'édition | Modification du prénom, nom, nom de boutique, téléphone et bio |
| Mise à jour BDD | Requêtes `UPDATE` sur les tables `users` et `sellers` |
| Responsive mobile | Navbar mobile avec bottom nav, adapté aux petits écrans |

---

## 🔒 Sécurité mise en place

- **Sessions PHP** : Vérification de `$_SESSION['user_id']` sur toutes les pages protégées
- **Contrôle d'accès par rôle** : Un customer ne peut pas accéder au profil seller et vice versa
- **Requêtes préparées PDO** : Protection totale contre les injections SQL
- **Hashage des mots de passe** : `password_hash()` BCRYPT sur toutes les nouvelles inscriptions
- **Échappement XSS** : `htmlspecialchars()` sur toutes les données affichées
- **Statut pending** : Les sellers sont bloqués jusqu'à validation par l'administrateur
- **Protection .env** : Les credentials BDD ne sont pas exposés dans le code

---

## 🗄️ Tables de base de données utilisées

| Table | Usage |
|---|---|
| `users` | Authentification, lecture et mise à jour des infos personnelles |
| `roles` | Vérification du rôle (admin=1, seller=2, customer=3) |
| `customers` | Lecture et mise à jour de l'adresse et id_customer |
| `sellers` | Lecture et mise à jour du store_name et seller_rating |
| `orders` | Comptage et affichage des commandes du customer |
| `orders_items` | Détail des articles par commande |
| `products` | Affichage du stock du vendeur dans son profil |
| `categories` | Catégorie associée à chaque produit affiché |

---

## ⚙️ Technologies utilisées

- **PHP** (Sessions, PDO, password_hash, transactions)
- **MySQL / MariaDB** (Requêtes JOIN, UPDATE, INSERT transactionnel)
- **JavaScript** (Validation formulaires, toggle modal, affichage/masquage champs)
- **HTML / CSS** (Structure des pages, modal d'édition, responsive mobile)
- **XAMPP** (Serveur local Apache + PHP + MariaDB)

---

## 📐 Structure des Sessions

```php
$_SESSION['user_id']       // ID de l'utilisateur connecté
$_SESSION['username']      // Prénom
$_SESSION['lastname']      // Nom
$_SESSION['email']         // Email
$_SESSION['role']          // ID du rôle (1=admin, 2=seller, 3=customer)
$_SESSION['role_name']     // Nom du rôle
$_SESSION['profile_image'] // Image de profil
$_SESSION['id_customer']   // ID customer (si role=customer)
$_SESSION['id_seller']     // ID seller (si role=seller)
$_SESSION['store_name']    // Nom de la boutique (si role=seller)
```

---

## 🚀 Comment tester ma partie

1. Lancer **XAMPP** (Apache + MySQL)
2. Importer `ecommerce.sql` dans phpMyAdmin
3. Accéder à `http://localhost/e-commerce-multi-role/pages/authentification/login.php`

### Comptes de test disponibles :

| Email | Mot de passe | Rôle | Résultat attendu |
|---|---|---|---|
| `admin@gaam.com` | `admin123` | Admin | Redirection vers profil admin |
| `ghita.elmir@gaam.com` | `customer123` | Customer | Redirection vers profil customer |
| `youssef.seller1@gaam.com` | `seller123` | Seller | Redirection vers profil vendeur |
| Nouveau compte Seller | — | Seller | Message "en attente d'approbation" |
| Nouveau compte Customer | — | Customer | Redirection directe vers profil |

### Scénarios à tester :
- ✅ Login avec un compte customer → profil customer avec données BDD
- ✅ Login avec un compte seller → profil vendeur avec boutique et produits
- ✅ Login avec un compte inactif → message d'erreur affiché
- ✅ Inscription d'un customer → session créée, redirection vers profil
- ✅ Inscription d'un seller → statut pending, message d'attente
- ✅ Modifier le profil via le modal → données mises à jour en BDD
- ✅ Logout → session détruite, retour au login
- ✅ Accès direct à user-profil.php sans être connecté → redirection login

---

## 🌿 Collaboration GitHub

Pour ne pas impacter le travail des coéquipiers, j'ai travaillé sur une branche dédiée :

```bash
git checkout -b feature/auth-profiles
git add pages/authentification/
git add pages/customer/
git add pages/vendor/logout.php
git add pages/vendor/seller-profile.php
git commit -m "feat: add login, register, user-profile, seller-profile with PHP & JS"
git push origin feature/auth-profiles
```

Une **Pull Request** a été créée pour merger dans `main` après validation de l'équipe.

---

## 👨‍💻 Auteur

**EL MIR Ghita** — Responsable Authentification & Gestion des Profils  
Projet réalisé dans le cadre du module Développement Web — S6  
Université Mundiapolis, Campus Nouaceur | 2025–2026

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

---

# 👤 HOUSSINI Abdelmouniim — Interface Client Front-End
**Partie vitrine client du projet E-Commerce Multi-Rôles**  
Université Mundiapolis — Cycle Ingénieur 1A | Module Développement Web | 2025–2026

---

## 📋 Responsabilité dans le projet

Je suis responsable de toute la **partie client front-end** de la plateforme, c'est-à-dire l'ensemble des pages visibles par les utilisateurs finaux (acheteurs) qui naviguent sur la boutique. Ces pages constituent la vitrine publique de GAAM et couvrent la découverte des produits, la navigation par catégorie, les filtres interactifs et le détail d'un produit.

---

## 🗂️ Fichiers dont je suis responsable

### Pages Client
```
pages/
├── home.php                → Page d'accueil (hero, catégories, curation, newsletter)
├── product-catalog.php     → Catalogue produits avec filtres interactifs
├── category-page.php       → Page de catégorie avec hero banner et sidebar filtres
└── product-detail.php      → Page détail d'un produit (variantes, avis, CTA)
```

### Includes partagés
```
includes/
├── header.php              → Navigation partagée (logo, liens, icônes)
└── footer.php              → Footer partagé + bottom nav mobile
```

### Styles CSS
```
assets/css/
├── main.css                → Styles globaux, responsive, tous les composants
└── variables.css           → Design system (couleurs, polices, espacements)
```

### JavaScript
```
assets/js/
├── update-slider.js        → Logique double slider prix (price range)
├── filters.js              → Filtrage produits par prix, couleur, rating
└── smooth-scrolling.js     → Navigation fluide entre les sections
```

---

## ✅ Fonctionnalités développées

### 🏠 Page d'Accueil (`home.php`)

| Fonctionnalité | Description |
|---|---|
| Hero Section | Image plein écran mobile / split 2 colonnes desktop avec pseudo-élément `::after` |
| Shop by Category | Grille 3 cartes avec images de fond et overlay gradient |
| The Curation | Grille produits avec badges, cœur de favoris et hover zoom |
| Newsletter | Bloc email avec pill input+bouton fusionnés sur desktop |
| Responsive complet | Mobile (1 col), Tablette (2 col), Desktop (4 col grille produits) |

### 🛍️ Page Catalogue (`product-catalog.php`)

| Fonctionnalité | Description |
|---|---|
| Layout Sidebar + Grille | Sidebar 25% sticky + contenu 75% via `display: flex` |
| Double slider prix | Deux `input[type=range]` superposés avec barre colorée dynamique |
| Filtrage en temps réel | Masquage/affichage des cards selon la plage de prix sélectionnée |
| Color Palette | 5 points colorés cliquables avec anneau de sélection `::after` |
| Rating étoiles | 5 étoiles cliquables avec état actif cumulatif |
| Navigation desktop | Grid 3 colonnes : logo gauche / liens centre / icônes droite |
| Pagination | Boutons circulaires avec état actif + flèches navigation |

### 📂 Page Catégorie (`category-page.php`)

| Fonctionnalité | Description |
|---|---|
| Hero Banner | Image pleine largeur (100% viewport), hauteur 48rem, overlay gradient |
| Sidebar filtres | Price Range (checkboxes), Material (tags), Sustainability (note) |
| Grille 3 colonnes | Produits avec badges Limited Edition / New Arrival / Exclusive |
| Barre de tri | Compteur de résultats + sélecteur Featured/Prix/Nouveautés |
| Isolation CSS | Suffixe `_2` sur toutes les classes pour éviter les conflits |
| Breadcrumb mobile | Navigation fil d'ariane sur mobile |

### 🔍 Page Détail Produit (`product-detail.php`)

| Fonctionnalité | Description |
|---|---|
| Hero image | Image plein écran avec badge Premium Series positionné en absolu |
| Sélecteur de finition | 3 points colorés cliquables (navy, silver, gold) |
| Sélecteur dimensions | Boutons pill avec état actif/outline (38mm / 42mm) |
| Feature Cards | Heritage (fond clair) + Resistance (fond `--primary`) côte à côte |
| Section Journal | Étoiles Font Awesome, citation, avatar auteur, barres de notation |
| Barres de notation | Build Quality et Delivery avec remplissage dynamique via `style="width: X%"` |
| Bottom CTA fixe | Bouton bag circulaire + bouton Buy Now pleine largeur (mobile) |

---

## 📐 Système Responsive

### Breakpoints

| Breakpoint | Cible | Changements principaux |
|---|---|---|
| Base | Mobile (< 768px) | 1 colonne, bottom nav fixe, images pleine largeur |
| 768px–1099px | Tablette | 2 colonnes produits, bottom nav cachée |
| ≥ 1100px | Desktop | Sidebar + grille 3 col, nav horizontale sticky |
| ≥ 1400px | Large desktop | Sidebar élargie 24rem, gaps augmentés |

### Navigation adaptative

- **Mobile** : bottom nav fixe avec 4 icônes (Home, Search, Cart, Account)
- **Desktop** : header sticky `grid-template-columns: 1fr auto 1fr` — logo gauche, liens centre, icônes droite

---

## 🎨 Design System

### Variables CSS (`variables.css`)

```css
:root {
    --primary:       #1a237e;   /* Bleu marine principal */
    --secondary:     rgb(113, 116, 154);
    --light-grey:    #faf8fd;   /* Fond global */
    --tertiary:      #5c1800;   /* Badges Sale / accent */
    --neutral:       #77767d;
    --bg:            #ffffff;
    --border-radius: 1rem;
    --primary-font:  "Manrope", sans-serif;
}
```

### Micro-interactions

- Zoom au survol des images : `transform: scale(1.04–1.08)` + `overflow: hidden`
- Underline animé sur les liens nav : `width: 0 → 100%` via `::after`
- Opacité cœur favoris au survol : `opacity: 0.25 → 1`
- Transitions boutons : `transition: all 0.2s`

---

## ⚙️ Fonctions JavaScript principales (`update-slider.js`, `filters.js`)

```javascript
// Double slider prix
function updateSlider() {
    let min = parseInt(rMin.value);
    let max = parseInt(rMax.value);
    if (min >= max) { rMin.value = max - 10; min = max - 10; }
    const pMin = (min / 1000) * 100;
    const pMax = (max / 1000) * 100;
    fill.style.left  = pMin + '%';
    fill.style.width = (pMax - pMin) + '%';
    rangeDisplay.textContent = '$' + min + ' – ' + (max >= 1000 ? '$1000+' : '$' + max);
    filterProducts(min, max);
}

// Filtrage produits par prix
function filterProducts(min, max) {
    document.querySelectorAll('.product-card').forEach(card => {
        const priceEl = card.querySelector('.product-card__price');
        if (!priceEl) return;
        const price = parseFloat(priceEl.textContent.replace(/[^0-9.]/g, ''));
        card.style.display = (price >= min && price <= max) ? 'block' : 'none';
    });
}

// Rating étoiles
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', () => {
        const val = parseInt(star.dataset.val);
        document.querySelectorAll('.star').forEach(s => {
            s.classList.toggle('active', parseInt(s.dataset.val) <= val);
        });
    });
});
```

---

## 🔌 Intégration PHP — Includes partagés

```php
<?php
$pageTitle   = 'Living Essentials';
$currentPage = 'catalog';
$searchBar   = true; // active la barre de recherche dans le header
include '../includes/header.php';
?>

<main>
    <!-- contenu spécifique à la page -->
</main>

<?php include '../includes/footer.php'; ?>
```

### Variables de contexte disponibles

| Variable | Type | Description |
|---|---|---|
| `$pageTitle` | `string` | Titre de l'onglet navigateur |
| `$currentPage` | `string` | Page active pour le surlignage du lien nav |
| `$searchBar` | `bool` | Affiche barre de recherche au lieu des icônes |
| `$extraCss` | `string` | Chemin vers un CSS supplémentaire |
| `$extraJs` | `string` | Chemin vers un JS supplémentaire |

---

## 🗄️ Tables de base de données utilisées

| Table | Usage |
|---|---|
| `products` | Affichage des produits sur home, catalog et category |
| `categories` | Filtres par catégorie sur la page catalogue |
| `orders_items` | Calcul du prix pour le filtrage dynamique |
| `sellers` | Affichage du nom du vendeur sur la fiche produit |

---

## 🔒 Bonnes pratiques implémentées

- **CSS** : Variables centralisées, mobile first, pas de `!important` sauf héritage
- **HTML** : Balises sémantiques (`<header>`, `<main>`, `<section>`, `<aside>`, `<footer>`), `alt` sur toutes les images, `aria-label` sur les boutons icônes
- **JS** : Fichiers séparés par responsabilité, `defer` sur tous les scripts, une fonction = une responsabilité
- **Performance** : Transitions sur `transform` et `opacity` uniquement (sans reflow), images optimisées avec paramètres `?w=600&q=80`

---

## 🚀 Comment tester ma partie

1. Lancer le serveur XAMPP (Apache + MySQL)
2. Accéder à `http://localhost/GAAM/pages/home.php` → page d'accueil
3. Accéder à `http://localhost/GAAM/pages/product-catalog.php` → catalogue avec filtres
4. Accéder à `http://localhost/GAAM/pages/category-page.php` → page catégorie
5. Accéder à `http://localhost/GAAM/pages/product-detail.php` → détail produit
6. Tester les filtres prix, couleur et rating sur le catalogue
7. Redimensionner la fenêtre pour vérifier le responsive (mobile / tablette / desktop)

> Aucun compte requis pour accéder aux pages client — elles sont publiques.

---

## 👨‍💻 Auteur

**HOUSSINI Abdelmouniim** — Responsable Interface Client Front-End  
Projet réalisé dans le cadre du module Développement Web — S6  
Université Mundiapolis, Campus Nouaceur | 2025–2026

# 👤 SAHMI Adam — Tunnel d'Achat & Historique des Commandes

> **Partie Processus d'Achat** du projet E-Commerce Multi-Rôles  
> Université Mundiapolis — Cycle Ingénieur 1A | Module Développement Web | 2025–2026

---

## 📋 Responsabilité dans le projet

Je suis responsable de toute la partie **Tunnel de Conversion (Checkout)** et **Traçabilité Client** de la plateforme. Cela inclut la gestion dynamique du panier d'achat, la collecte sécurisée des informations de livraison, le traitement transactionnel en base de données pour figer la commande, ainsi que l'interface permettant au client de consulter l'historique détaillé de ses achats.

---

## 🗂️ Fichiers dont je suis responsable

### Pages Client (Processus d'Achat)

```text
pages/public/
├── cart.php              → Affichage dynamique du panier et calculs financiers
├── shipping.php          → Formulaire de livraison avec pré-remplissage intelligent
└── history.php           → Traitement backend de la commande et affichage de l'historique
```

### Architecture & Sécurité

```text
/
├── db.php                → Script de connexion sécurisée à la BDD
└── .env                  → Fichier d'environnement (ignoré par Git) protégeant les identifiants locaux
```

### CSS & JavaScript associés

```text
assets/css/pages/
├── cart.css              → Styles spécifiques à la grille du panier
├── shipping.css          → Mise en page du formulaire et des cartes de livraison
└── history.css           → Styles des cartes de commandes et des miniatures produits

assets/js/pages/client/
└── shipping.js           → Interactivité côté client pour l'étape de livraison
```

---

## ✅ Fonctionnalités développées

### 🛒 Page Panier (`cart.php`)

| Fonctionnalité | Description |
|---|---|
| Récupération des données | Requête SQL `JOIN` entre `cart_items` et `products` pour extraire les détails (image, nom, prix). |
| Calculs dynamiques | Algorithme PHP calculant le sous-total (prix × quantité), la TVA (8%), et les frais d'expédition. |
| Affichage modulaire | Génération de cartes produits à la volée via une boucle PHP `while`. |
| Gestion des états vides | Affichage d'un message d'erreur ergonomique si le panier est vide. |

### 📦 Page de Livraison (`shipping.php`)

| Fonctionnalité | Description |
|---|---|
| Smart Autofill | Requête `LEFT JOIN` (`users` + `customers`) pour pré-remplir automatiquement le prénom, nom et adresse du client connecté. |
| Sélection d'expédition | Boutons radio interactifs permettant de choisir entre "Express Courier" et "Standard Editorial". |
| Séparation des données | Sauvegarde de l'adresse d'expédition uniquement pour la commande en cours, sans écraser l'adresse de résidence globale. |
| Transmission sécurisée | Envoi des données vers le script de traitement via la méthode HTTP `POST`. |

### 📜 Page Historique & Validation (`history.php`)

| Fonctionnalité | Description |
|---|---|
| Création de commande | Insertion des données de livraison dans `orders` et récupération de l'ID généré. |
| Figeage du panier | Transfert automatisé des articles depuis `cart_items` vers `orders_items` pour archiver le contenu exact. |
| Affichage chronologique | Récupération des anciennes commandes triées par date décroissante (`ORDER BY date_order DESC`). |
| Détails natifs | Utilisation de la balise HTML5 `<details>` pour un menu déroulant fluide des informations de livraison (sans JS). |
| Miniatures produits | Sous-requête SQL dynamique affichant les images exactes des produits achetés dans chaque carte de commande. |

---

## 🔒 Sécurité et Bonnes Pratiques

- **Variables d'Environnement (`.env`)** : Implémentation d'un système `.env` via `db.php` pour sécuriser les mots de passe de la BDD et éviter les *merge conflicts* sur GitHub entre développeurs.
- **Requêtes préparées** : Toutes les insertions et sélections utilisent `prepare()` et `bind_param()` / `execute()` pour une protection totale contre les injections SQL.
- **Échappement des données** : Utilisation systématique de la fonction `e()` (`htmlspecialchars`) sur les données affichées dans l'historique pour prévenir les failles XSS.
- **Vérification de Session** : Redirection automatique vers la page de login si un utilisateur non authentifié tente d'accéder au tunnel d'achat.

---

## 🗄️ Tables de base de données utilisées

| Table | Usage |
|---|---|
| `cart_items` | Lecture des articles placés dans le panier par le client. |
| `orders` | Insertion des métadonnées de livraison (`first_name`, `address`, `delivery_method`). |
| `orders_items` | Table de liaison (Many-to-Many) archivant les produits d'une commande validée. |
| `users` & `customers` | Récupération des informations du profil pour le pré-remplissage du formulaire. |
| `products` | Récupération des images, noms et prix pour l'affichage visuel. |

---

## ⚙️ Technologies utilisées

- **PHP 8** (Sessions, Algorithmes de calcul financier, MySQLi)
- **MySQL / MariaDB** (Requêtes complexes : `JOIN`, `LEFT JOIN`, Sous-requêtes)
- **HTML5** (Balises sémantiques et interactives comme `<details>` et `<summary>`)
- **CSS3** (Flexbox, Grid, mise en page adaptative)

---

## 🚀 Comment tester ma partie

1. Lancer le serveur local (XAMPP).
2. Se connecter avec un compte **Client** existant.
3. Ajouter un ou plusieurs articles au panier depuis la boutique.
4. Accéder à `/pages/public/cart.php` pour vérifier les calculs.
5. Cliquer sur *Checkout* pour accéder à `/pages/public/shipping.php` et tester le pré-remplissage.
6. Soumettre le formulaire de livraison pour déclencher le script de sauvegarde.
7. Constater la redirection vers `/pages/public/history.php` où la nouvelle commande apparaîtra en haut de la liste avec les images des produits.

---

## 👨‍💻 Auteur

**SAHMI Adam** — Responsable Tunnel de Conversion & Historique Client  
Projet réalisé dans le cadre du module Développement Web — S6  
Université Mundiapolis, Campus Nouaceur | 2025–2026
