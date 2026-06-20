<?php
// ─── includes/header-public.php ─────────────────────────────────────────────
// Wrapper autour de includes/header.php du projet principal.
// Injecte le CSS public.css SANS modifier header.php existant.
// Usage dans les pages publiques : include __DIR__ . '/../../includes/header-public.php';

// On utilise output buffering pour capturer le output de header.php
// et injecter notre <link> CSS juste avant le </head>

ob_start();
include __DIR__ . '/header.php';
$html = ob_get_clean();

// Injection du CSS des pages publiques
$pub_css_link = '<link rel="stylesheet" href="' . BASE_URL . 'assets/css/pages/public.css" />';
$html = str_replace('</head>', $pub_css_link . "\n  </head>", $html);

echo $html;
