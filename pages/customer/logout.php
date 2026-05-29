<?php
// ============================================================
//  logout.php — Destruction de la session
// ============================================================
session_start();
session_unset();
session_destroy();
header('Location: ../authentification/login.php');

exit;