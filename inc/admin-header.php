<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/admin-functions.php';
$s = settings();
$current = $GLOBALS['admin_page'] ?? '';
$navPages = [
    'home' => ['label'=>'Начална','icon'=>'pin','href'=>'home.php'],
    'services' => ['label'=>'Услуги','icon'=>'shield','href'=>'services.php'],
    'projects' => ['label'=>'Проекти','icon'=>'drill','href'=>'projects.php'],
    'about' => ['label'=>'За нас','icon'=>'users','href'=>'za-nas.php'],
    'contact' => ['label'=>'Контакти','icon'=>'phone','href'=>'kontakti.php'],
];
$navMain = [
    'bookings' => ['label'=>'Резервации','icon'=>'calendar','href'=>'bookings.php'],
    'settings' => ['label'=>'Настройки','icon'=>'clock','href'=>'settings.php'],
];
$bCount = is_admin() ? db()->query("SELECT COUNT(*) FROM bookings")->fetchColumn() : 0;
?>
<!doctype html>
<html lang="bg"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['name']) ?></title>
<link rel="icon" href="/assets/media/icon.svg" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Spectral:ital,wght@0,400;0,500;0,600;0,700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=3">
<link rel="stylesheet" href="/assets/css/admin.css?v=2">
</head>
<body class="adminBody">
<div class="adminLayout">
  <aside class="adminSidebar">
    <div class="adminSidebarLogo"><img src="/assets/media/logo.png" alt="<?= e($s['name']) ?>" style="height:40px;width:auto"></div>
    <nav class="adminNav">
      <div class="navSectionLabel">Страници</div>
      <?php foreach ($navPages as $key=>$item): ?>
      <a href="<?= $item['href'] ?>" class="navSub <?= $current===$key?'active':'' ?>"><?= icon($item['icon'],'icon') ?><span><?= e($item['label']) ?></span></a>
      <?php endforeach; ?>
      <div class="navDivider"></div>
      <?php foreach ($navMain as $key=>$item): ?>
      <a href="<?= $item['href'] ?>" class="<?= $current===$key?'active':'' ?>"><?= icon($item['icon'],'icon') ?><span><?= e($item['label']) ?></span><?php if ($key==='bookings' && $bCount>0): ?><span class="navBadge"><?= $bCount ?></span><?php endif; ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="adminSidebarBottom">
      <a href="/" class="adminSidebarLink"><?= icon('arrow','icon') ?> Към сайта</a>
      <a href="logout.php" class="adminSidebarLink"><?= icon('arrow','icon') ?> Изход</a>
    </div>
  </aside>
  <main class="adminMain">