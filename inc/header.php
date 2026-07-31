<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
$s = settings();
$nav = [
    ['href'=>'/','label'=>'Начало'],
    ['href'=>'/za-nas','label'=>'За нас'],
    ['href'=>'/uslugi','label'=>'Услуги'],
    ['href'=>'/proekti','label'=>'Проекти'],
    ['href'=>'/#otzivi','label'=>'Отзиви'],
    ['href'=>'/kontakti','label'=>'Контакти'],
];
$current = $GLOBALS['path'] ?? '';
?><!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['name']) ?></title>
<meta name="description" content="<?= e($desc ?? 'Монтаж и демонтаж на мебели в Добрич, Балчик, Варна и околността.') ?>">
<link rel="icon" href="/assets/media/icon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/fonts/fonts.css?v=1">
<link rel="preload" href="/assets/css/style.css?v=7" as="style">
<link rel="stylesheet" href="/assets/css/style.css?v=7">
</head>
<body>
<header class="siteHeader">
  <div class="specbar">
    <div class="container specbarInner">
      <span><?= icon('pin','icon') ?> <?= e($s['region']) ?></span>
      <span><?= icon('clock','icon') ?> <?= e($s['hours']) ?></span>
      <a href="<?= e($s['phoneHref']) ?>"><?= icon('phone','icon') ?> <?= e($s['phone']) ?></a>
      <span class="specbarSocials" aria-label="Социални мрежи">
        <a href="<?= e($s['facebook'] ?: '#') ?>" aria-label="Facebook" <?php if($s['facebook']): ?>target="_blank" rel="noopener"<?php endif; ?>><?= icon('facebook','icon') ?></a>
        <a href="<?= e($s['instagram'] ?: '#') ?>" aria-label="Instagram" <?php if($s['instagram']): ?>target="_blank" rel="noopener"<?php endif; ?>><?= icon('instagram','icon') ?></a>
      </span>
    </div>
  </div>
  <div class="navWrap">
    <div class="container navInner">
      <a href="/" class="logo" aria-label="MarTed — начало"><img class="logoImg" src="/assets/media/logo.png" alt="<?= e($s['name']) ?> — монтаж на мебели"></a>
      <input id="nav-toggle" class="navToggle" type="checkbox" aria-label="Отвори меню">
      <label class="burger" for="nav-toggle"><?= icon('menu','icon') ?></label>
      <nav class="mainNav" aria-label="Основна навигация">
        <?php foreach ($nav as $item): ?>
          <a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
        <?php endforeach; ?>
      </nav>
      <a href="/zapazi" class="btn btn-primary navCta">Запази час</a>
    </div>
  </div>
</header>
<main>
