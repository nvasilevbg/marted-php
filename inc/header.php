<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
$s = settings();
$nav = [
    ['href'=>'/','label'=>'ÐÐ°Ñ‡Ð°Ð»Ð¾'],
    ['href'=>'/za-nas','label'=>'Ð—Ð° Ð½Ð°Ñ'],
    ['href'=>'/uslugi','label'=>'Ð£ÑÐ»ÑƒÐ³Ð¸'],
    ['href'=>'/proekti','label'=>'ÐŸÑ€Ð¾ÐµÐºÑ‚Ð¸'],
    ['href'=>'/otzivi','label'=>'ÐžÑ‚Ð·Ð¸Ð²Ð¸'],
    ['href'=>'/kontakti','label'=>'ÐšÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð¸'],
];
$current = $GLOBALS['path'] ?? '';
?><!doctype html>
<html lang="bg">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['name']) ?></title>
<meta name="description" content="<?= e($desc ?? 'ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸ Ð² Ð”Ð¾Ð±Ñ€Ð¸Ñ‡, Ð‘Ð°Ð»Ñ‡Ð¸Ðº, Ð’Ð°Ñ€Ð½Ð° Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑ‚Ñ‚Ð°.') ?>">
<link rel="icon" href="/assets/media/icon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/fonts/fonts.css?v=1">
<link rel="preload" href="/assets/css/style.css?v=15" as="style">
<link rel="stylesheet" href="/assets/css/style.css?v=15">
<script defer src="https://cloud.umami.is/script.js" data-website-id="78e2dd3b-41f6-43df-bdec-dae392425d6a"></script>
</head>
<body>
<?php if (!empty($GLOBALS['minimal_header'])): ?>
<header class="siteHeader minimalHeader">
  <div class="container navInner" style="justify-content:center">
    <a href="/" class="logo" aria-label="<?= e($s['name']) ?> â€” Ð½Ð°Ñ‡Ð°Ð»Ð½Ð¾"><img class="logoImg" src="/assets/media/logo.png" alt="<?= e($s['name']) ?>"></a>
  </div>
</header>
<?php else: ?>
<header class="siteHeader">
  <div class="specbar">
    <div class="container specbarInner">
      <span><?= icon('pin','icon') ?> <?= e($s['region']) ?></span>
      <span><?= icon('clock','icon') ?> <?= e($s['hours']) ?></span>
      <a href="<?= e($s['phoneHref']) ?>"><?= icon('phone','icon') ?> <?= e($s['phone']) ?></a>
      <span class="specbarSocials" aria-label="Ð¡Ð¾Ñ†Ð¸Ð°Ð»Ð½Ð¸ Ð¼Ñ€ÐµÐ¶Ð¸">
        <a href="<?= e($s['facebook'] ?: '#') ?>" aria-label="Facebook" <?php if($s['facebook']): ?>target="_blank" rel="noopener"<?php endif; ?>><?= icon('facebook','icon') ?></a>
        <a href="<?= e($s['instagram'] ?: '#') ?>" aria-label="Instagram" <?php if($s['instagram']): ?>target="_blank" rel="noopener"<?php endif; ?>><?= icon('instagram','icon') ?></a>
      </span>
    </div>
  </div>
  <div class="navWrap">
    <div class="container navInner">
      <a href="/" class="logo" aria-label="MarTed â€” Ð½Ð°Ñ‡Ð°Ð»Ð¾"><img class="logoImg" src="/assets/media/logo.png" alt="<?= e($s['name']) ?> â€” Ð¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸"></a>
      <input id="nav-toggle" class="navToggle" type="checkbox" aria-label="ÐžÑ‚Ð²Ð¾Ñ€Ð¸ Ð¼ÐµÐ½ÑŽ">
      <label class="burger" for="nav-toggle"><?= icon('menu','icon') ?></label>
      <nav class="mainNav" aria-label="ÐžÑÐ½Ð¾Ð²Ð½Ð° Ð½Ð°Ð²Ð¸Ð³Ð°Ñ†Ð¸Ñ">
        <?php foreach ($nav as $item): ?>
          <a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
        <?php endforeach; ?>
      </nav>
      <a href="/zapazi" class="btn btn-primary navCta">Ð—Ð°Ð¿Ð°Ð·Ð¸ Ñ‡Ð°Ñ</a>
    </div>
  </div>
</header>
<?php endif; ?>
<main>
