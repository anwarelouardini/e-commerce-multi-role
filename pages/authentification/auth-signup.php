<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php';


// ============================================================
//  auth-signup.php — Inscription utilisateur
// ============================================================
session_start();

// Rôles
define('ROLE_ADMIN',    1);
define('ROLE_SELLER',   2);
define('ROLE_CUSTOMER', 3);

// Si déjà connecté, rediriger
if (isset($_SESSION['user_id'])) {
    if ((int)$_SESSION['role'] === ROLE_SELLER) {
        header('Location: ' . BASE_URL . 'pages/vendor/seller-profile.php');
    } else {
        header('Location: ' . BASE_URL . 'pages/customer/user-profil.php');
    }
    exit;
}


$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['firstname']   ?? '');
    $lastname   = trim($_POST['lastname']    ?? '');
    $email      = trim($_POST['email']       ?? '');
    $password   = $_POST['password']          ?? '';
    $role       = (int)($_POST['role']       ?? ROLE_CUSTOMER);
    $phone      = trim($_POST['phone']       ?? '');
    $store_name = trim($_POST['store_name']  ?? '');

    // Validations
    if (empty($username))                              $error = 'Le prénom est requis.';
    elseif (empty($lastname))                          $error = 'Le nom est requis.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Email invalide.';
    elseif (strlen($password) < 6)                     $error = 'Mot de passe : 6 caractères minimum.';
    elseif (!in_array($role, [ROLE_SELLER, ROLE_CUSTOMER])) $error = 'Rôle invalide.';
    elseif ($role === ROLE_SELLER && empty($store_name))    $error = 'Le nom de la boutique est requis.';
    else {
        // Email déjà utilisé ?
        $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            try {
                $pdo->beginTransaction();

                $hash   = password_hash($password, PASSWORD_BCRYPT);
                $status = ($role === ROLE_SELLER) ? 'pending' : 'active';

                $stmt = $pdo->prepare("
                    INSERT INTO users (username, lastname, email, password, phone_number, id_role, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$username, $lastname, $email, $hash, $phone, $role, $status]);
                $newUserId = (int)$pdo->lastInsertId();

                if ($role === ROLE_CUSTOMER) {
                    $pdo->prepare("INSERT INTO customers (id_user) VALUES (?)")->execute([$newUserId]);
                }
                if ($role === ROLE_SELLER) {
                    $pdo->prepare("INSERT INTO sellers (store_name, id_user) VALUES (?, ?)")->execute([$store_name, $newUserId]);
                }

                $pdo->commit();

                if ($role === ROLE_SELLER) {
                    // Seller → attente approbation admin, pas de session
                    header('Location: login.php?msg=pending');
                } else {
                    // Customer → session + redirection directe
                    $_SESSION['user_id']  = $newUserId;
                    $_SESSION['username'] = $username;
                    $_SESSION['lastname'] = $lastname;
                    $_SESSION['email']    = $email;
                    $_SESSION['role']     = $role;
                    header('Location: ../customer/user-profil.php');
                }
                exit;

            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = 'Erreur lors de la création du compte. Réessayez.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up — GAAM</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
</head>
<body class="login">
    <header class="gaam">
        <h1 class="head">GAAM</h1>
    </header>
    <main>
        <section class="main-box container">
            <div>
                <h1 class="head head--1">Create your account</h1>
            </div>

            <?php if ($error): ?>
                <div class="msg msg--error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="auth-signup.php" method="POST" class="formel" id="signupForm" novalidate>

                <label class="labela" for="firstname">FIRST NAME</label>
                <input class="entrer" type="text" name="firstname" id="firstname" required
                       value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
                <span class="field-error" id="firstnameError"></span>

                <label class="labela" for="lastname">LAST NAME</label>
                <input class="entrer" type="text" name="lastname" id="lastname" required
                       value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                <span class="field-error" id="lastnameError"></span>

                <label class="labela" for="email">EMAIL ADDRESS</label>
                <input class="entrer" type="email" name="email" id="email"
                       placeholder="name@company.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <span class="field-error" id="emailError"></span>

                <label class="labela" for="phone">PHONE NUMBER</label>
                <input class="entrer" type="tel" name="phone" id="phone"
                       placeholder="0600000000"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

                <label class="labela" for="password">PASSWORD</label>
                <input class="entrer" type="password" name="password" id="password"
                       placeholder=".........." required>
                <span class="field-error" id="passwordError"></span>

                <!-- Sélection du rôle -->
                <label class="labela" for="role">I AM A</label>
                <select class="entrer" name="role" id="role">
                    <option value="3" <?= (($_POST['role'] ?? '3') == '3') ? 'selected' : '' ?>>Customer</option>
                    <option value="2" <?= (($_POST['role'] ?? '') == '2')  ? 'selected' : '' ?>>Seller</option>
                </select>

                <!-- Champ boutique (affiché uniquement si Seller) -->
                <div id="storeNameWrap" style="display:none;">
                    <label class="labela" for="store_name">STORE NAME</label>
                    <input class="entrer" type="text" name="store_name" id="store_name"
                           value="<?= htmlspecialchars($_POST['store_name'] ?? '') ?>">
                    <span class="field-error" id="storeNameError"></span>
                </div>

            </form>

            <div>
                <button class="boutton" id="signupBtn">Create Account</button>
            </div>

            <div class="do">
                <p>Already have an account? <a href="login.php" class="linki">Sign in</a></p>
            </div>
        </section>
    </main>

    <script src="<?= BASE_URL ?>assets/js/pages/authentification/auth.js"></script>
</body>
</html>