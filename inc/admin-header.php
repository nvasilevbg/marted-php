<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/admin-functions.php';
$s = settings();
$current_admin = $GLOBALS['admin_page'] ?? 'dashboard';
$nav = [
    'dashboard' => ['label'=>'Dashboard','icon'=>'box','href'=>'index.php'],
    'projects' => ['label'=>'Проекти','icon'=>'drill','href'=>'projects.php'],
    'bookings' => ['label'=>'Резервации','icon'=>'calendar','href'=>'bookings.php'],
    'services' => ['label'=>'Услуги','icon'=>'shield','href'=>'services.php'],
    'testimonials' => ['label'=>'Отзиви','icon'=>'users','href'=>'testimonials.php'],
    'pages' => ['label'=>'Страници','icon'=>'pin','href'=>'pages.php'],
    'settings' => ['label'=>'Настройки','icon'=>'clock','href'=>'settings.php'],
];
$bCount = is_admin() ? db()->query("SELECT COUNT(*) FROM bookings")->fetchColumn() : 0;
$pCount = is_admin() ? db()->query("SELECT COUNT(*) FROM projects")->fetchColumn() : 0;
?>
<!doctype html>
<html lang="bg"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['name']) ?></title>
<link rel="icon" href="/assets/media/icon.svg" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Spectral:ital,wght@0,400;0,500;0,600;0,700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=3">
<link rel="stylesheet" href="/assets/css/admin.css?v=1">
</head>
<body class="adminBody">
<div class="adminLayout">
  <aside class="adminSidebar">
    <div class="adminSidebarLogo">
      <img src="/assets/media/logo.png" alt="<?= e($s['name']) ?>" style="height:40px;width:auto">
    </div>
    <nav class="adminNav">
      <?php foreach ($nav as $key=>$item): ?>
      <a href="<?= $item['href'] ?>" class="<?= $current_admin===$key?'active':'' ?>">
        <?= icon($item['icon'],'icon') ?>
        <span><?= e($item['label']) ?></span>
        <?php if ($key==='bookings' && $bCount>0): ?><span class="navBadge"><?= $bCount ?></span><?php endif; ?>
        <?php if ($key==='projects' && $pCount>0): ?><span class="navBadge"><?= $pCount ?></span><?php endif; ?>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="adminSidebarBottom">
      <a href="/" class="adminSidebarLink"><?= icon('arrow','icon') ?> Към сайта</a>
      <a href="logout.php" class="adminSidebarLink"><?= icon('arrow','icon') ?> Изход</a>
    </div>
  </aside>
  <main class="adminMain">