<?php
session_start();
require_once '../../includes/db.php';

define('ROLE_ADMIN',    1);
define('ROLE_SELLER',   2);
define('ROLE_CUSTOMER', 3);

// Si déjà connecté, rediriger
if (isset($_SESSION['user_id'])) {
    if ((int)$_SESSION['role'] === ROLE_SELLER) {
        header('Location: ../vendor/seller-profile.php');
    } elseif ((int)$_SESSION['role'] === ROLE_ADMIN) {
        header('Location: ../admin/dashboard.php');
    } else {
        header('Location: ../customer/user-profil.php');
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']       ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email et mot de passe requis.';
    } else {
        $stmt = $pdo->prepare("
            SELECT u.*, r.role_name
            FROM users u
            JOIN roles r ON u.id_role = r.id_role
            WHERE u.email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = 'Email ou mot de passe incorrect.';
        } else {
            $valid = password_verify($password, $user['password'])
                  || $password === $user['password'];

            if (!$valid) {
                $error = 'Email ou mot de passe incorrect.';
            } elseif ($user['status'] !== 'active') {
                $error = 'Compte inactif. Contactez l\'administrateur.';
            } else {
                $_SESSION['user_id']       = $user['id_user'];
                $_SESSION['username']      = $user['username'];
                $_SESSION['lastname']      = $user['lastname'];
                $_SESSION['email']         = $user['email'];
                $_SESSION['role']          = $user['id_role'];
                $_SESSION['role_name']     = $user['role_name'];
                $_SESSION['profile_image'] = $user['profile_image'];

                if ((int)$user['id_role'] === ROLE_SELLER) {
                    $stmt2 = $pdo->prepare("SELECT id_seller, store_name FROM sellers WHERE id_user = ?");
                    $stmt2->execute([$user['id_user']]);
                    $seller = $stmt2->fetch();
                    if ($seller) {
                        $_SESSION['id_seller']  = $seller['id_seller'];
                        $_SESSION['store_name'] = $seller['store_name'];
                    }
                    header('Location: ../vendor/seller-profile.php');
                } elseif ((int)$user['id_role'] === ROLE_ADMIN) {
                    header('Location: ../admin/dashboard.php');
                } else {
                    if ((int)$user['id_role'] === ROLE_CUSTOMER) {
                        $stmt3 = $pdo->prepare("SELECT id_customer FROM customers WHERE id_user = ?");
                        $stmt3->execute([$user['id_user']]);
                        $customer = $stmt3->fetch();
                        if ($customer) {
                            $_SESSION['id_customer'] = $customer['id_customer'];
                        }
                    }
                    header('Location: ../customer/user-profil.php');
                }
                exit;
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
    <title>Login — GAAM</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body class="login">
    <header class="gaam">
        <h1 class="head">GAAM</h1>
    </header>
    <main>
        <section class="main-box container">
            <div class="icon">
                <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                </svg>
            </div>
            <div>
                <h1 class="head">Welcome Back</h1>
                <p class="comment">Please enter your credentials to access your account.</p>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'pending'): ?>
            <div class="msg msg--success">
                Compte créé ! En attente d'approbation par l'administrateur.
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="msg msg--error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="formel" id="loginForm" novalidate>
                <label class="labela" for="email">EMAIL ADDRESS</label>
                <input class="entrer" type="email" name="email" id="email"
                       placeholder="name@company.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <span class="field-error" id="emailError"></span>

                <div class="password">
                    <label class="labela" for="password">PASSWORD</label>
                    <a href="#">Forget Password?</a>
                </div>
                <input class="entrer" type="password" name="password" id="password"
                       placeholder=".........." required>
                <span class="field-error" id="passwordError"></span>

                <button class="boutton" type="submit" id="submitBtn">Sign In</button>
            </form>

            <div class="do">
                <p>Don't have an account? <a href="auth-signup.php" class="linki">Sign up</a></p>
            </div>
        </section>
    </main>
    <script src="auth.js"></script>
</body>
</html>