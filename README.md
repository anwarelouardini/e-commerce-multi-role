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
