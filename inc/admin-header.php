<?php require_once __DIR__ . '/functions.php'; require_once __DIR__ . '/icons.php'; $s = settings(); ?>
<!doctype html>
<html lang="bg"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['name']) ?></title>
<link rel="icon" href="/assets/media/icon.svg" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Spectral:ital,wght@0,400;0,500;0,600;0,700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=3">
</head><body>
<header class="siteHeader"><div class="navWrap"><div class="container navInner">
<a href="/" class="logo"><img class="logoImg" src="/assets/media/logo.png" alt="<?= e($s['name']) ?>"></a>
<a href="index.php" class="btn btn-ghost navCta">← Админ</a>
</div></div></header>
<main>