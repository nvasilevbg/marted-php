<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/admin-functions.php';
$s = settings();
$current = $GLOBALS['admin_page'] ?? '';
$navGroups = [
    'ÐÐÐ§ÐÐ›ÐÐ' => [
        'hero' => ['label'=>'Ð¥ÐµÑ€Ð¾Ð±Ð°Ð½ÐµÑ€','icon'=>'pin','href'=>'section.php?edit=hero'],
        'stats' => ['label'=>'Ð¡Ñ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÐ¸','icon'=>'users','href'=>'section.php?edit=stats'],
        'services' => ['label'=>'Ð£ÑÐ»ÑƒÐ³Ð¸','icon'=>'shield','href'=>'services.php'],
        'projects' => ['label'=>'ÐŸÑ€Ð¾ÐµÐºÑ‚Ð¸','icon'=>'drill','href'=>'projects.php'],
        'testimonials' => ['label'=>'ÐžÑ‚Ð·Ð¸Ð²Ð¸','icon'=>'users','href'=>'testimonials.php'],
    ],
    'Ð”Ð Ð£Ð“Ð˜ Ð¡Ð¢Ð ÐÐÐ˜Ð¦Ð˜' => [
        'contact' => ['label'=>'ÐšÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð¸','icon'=>'phone','href'=>'section.php?edit=contact'],
        'about' => ['label'=>'Ð—Ð° Ð½Ð°Ñ','icon'=>'users','href'=>'section.php?edit=about'],
    ],
    'ÐžÐŸÐ•Ð ÐÐ¦Ð˜Ð˜' => [
        'bookings' => ['label'=>'Ð ÐµÐ·ÐµÑ€Ð²Ð°Ñ†Ð¸Ð¸','icon'=>'calendar','href'=>'bookings.php'],
    ],
    'ÐžÐ‘Ð©Ð˜' => [
        'brand' => ['label'=>'ÐÐ°ÑÑ‚Ñ€Ð¾Ð¹ÐºÐ¸','icon'=>'clock','href'=>'section.php?edit=brand'],
    ],
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
<link rel="stylesheet" href="/assets/css/admin.css?v=5">
</head>
<body class="adminBody">
<div class="adminLayout">
  <?php if (is_admin()): ?>
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
      <a href="/" class="adminSidebarLink"><?= icon('arrow','icon') ?> ÐšÑŠÐ¼ ÑÐ°Ð¹Ñ‚Ð°</a>
      <a href="logout.php" class="adminSidebarLink"><?= icon('arrow','icon') ?> Ð˜Ð·Ñ…Ð¾Ð´</a>
    </div>
  </aside>
  <?php else: ?>
  <aside class="adminSidebar" style="display:none"></aside>
  <?php endif; ?>
  <main class="adminMain">