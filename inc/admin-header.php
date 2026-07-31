<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/admin-functions.php';
$s = settings();
$current = $GLOBALS['admin_page'] ?? '';
$navGroups = [
    'НАЧАЛНА' => [
        'hero' => ['label'=>'Херобанер','icon'=>'pin','href'=>'section.php?edit=hero'],
        'stats' => ['label'=>'Статистики','icon'=>'users','href'=>'section.php?edit=stats'],
        'services' => ['label'=>'Услуги','icon'=>'shield','href'=>'services.php'],
        'projects' => ['label'=>'Проекти','icon'=>'drill','href'=>'projects.php'],
        'testimonials' => ['label'=>'Отзиви','icon'=>'users','href'=>'testimonials.php'],
    ],
    'ДРУГИ СТРАНИЦИ' => [
        'contact' => ['label'=>'Контакти','icon'=>'phone','href'=>'section.php?edit=contact'],
        'about' => ['label'=>'За нас','icon'=>'users','href'=>'section.php?edit=about'],
    ],
    'ОПЕРАЦИИ' => [
        'bookings' => ['label'=>'Резервации','icon'=>'calendar','href'=>'bookings.php'],
    ],
    'ОБЩИ' => [
        'brand' => ['label'=>'Настройки','icon'=>'clock','href'=>'section.php?edit=brand'],
        'security' => ['label'=>'Сигурност','icon'=>'shield','href'=>'section.php?edit=security'],
    ],
];
$bCount = is_admin() ? db()->query("SELECT COUNT(*) FROM bookings")->fetchColumn() : 0;
?>
<!doctype html>
<html lang="bg"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['name']) ?></title>
<link rel="icon" href="/assets/media/icon.svg" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Spectral:wght@400;600;700&family=Manrope:wght@400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=7">
<link rel="stylesheet" href="/assets/css/admin.css?v=5">
</head>
<body class="adminBody">
<div class="adminLayout">
  <?php if (is_admin()): ?>`n  <?php if (is_admin()): ?>
  <aside class="adminSidebar">
    <div class="adminSidebarLogo"><img src="/assets/media/logo.png" alt="<?= e($s['name']) ?>" style="height:40px;width:auto"></div>
    <nav class="adminNav">
      <?php foreach ($navGroups as $groupLabel => $items): ?>
      <div class="navGroupLabel"><?= $groupLabel ?></div>
      <?php foreach ($items as $key => $item): ?>
      <a href="<?= $item['href'] ?>" class="navItem <?= $current===$key?'active':'' ?>"><?= icon($item['icon'],'icon') ?><span><?= e($item['label']) ?></span><?php if ($key==='bookings' && $bCount>0): ?><span class="navBadge"><?= $bCount ?></span><?php endif; ?></a>
      <?php endforeach; ?>
      <?php endforeach; ?>
    </nav>
    <div class="adminSidebarBottom">
      <a href="/" class="adminSidebarLink"><?= icon('arrow','icon') ?> Към сайта</a>
      <a href="logout.php" class="adminSidebarLink"><?= icon('arrow','icon') ?> Изход</a>
    </div>
  </aside>
  <?php else: ?>
  <aside class="adminSidebar" style="display:none"></aside>
  <?php endif; ?>
  <?php else: ?>
  <aside class="adminSidebar" style="display:none"></aside>
  <?php endif; ?>
  <main class="adminMain">