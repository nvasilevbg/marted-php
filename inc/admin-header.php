<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/admin-functions.php';
$s = settings();
$current = $GLOBALS['admin_page'] ?? '';
$navGroups = [
    'ÐÐÐ§ÐÐâ€ºÐÐ' => [
        'dashboard' => ['label'=>'Dashboard','icon'=>'pin','href'=>'dashboard.php'],
        'hero' => ['label'=>'Ð¥ÐµÑâ‚¬Ð¾Ð±Ð°Ð½ÐµÑâ‚¬','icon'=>'pin','href'=>'section.php?edit=hero'],
        'stats' => ['label'=>'Ð¡Ñâ€šÐ°Ñâ€šÐ¸ÑÑâ€šÐ¸ÐºÐ¸','icon'=>'users','href'=>'section.php?edit=stats'],
        'services' => ['label'=>'Ð£ÑÐ»ÑƒÐ³Ð¸','icon'=>'shield','href'=>'services.php'],
        'projects' => ['label'=>'ÐŸÑâ‚¬Ð¾ÐµÐºÑâ€šÐ¸','icon'=>'drill','href'=>'projects.php'],
        'testimonials' => ['label'=>'ÐžÑâ€šÐ·Ð¸Ð²Ð¸','icon'=>'users','href'=>'testimonials.php'],
    ],
    'Ðâ€Ð Ð£Ðâ€œÐ˜ Ð¡Ð¢Ð ÐÐÐ˜Ð¦Ð˜' => [
        'contact' => ['label'=>'ÐšÐ¾Ð½Ñâ€šÐ°ÐºÑâ€šÐ¸','icon'=>'phone','href'=>'section.php?edit=contact'],
        'about' => ['label'=>'Ð—Ð° Ð½Ð°Ñ','icon'=>'users','href'=>'section.php?edit=about'],
    ],
    'ÐžÐŸÐâ€¢Ð ÐÐ¦Ð˜Ð˜' => [
        'bookings' => ['label'=>'Ð ÐµÐ·ÐµÑâ‚¬Ð²Ð°Ñâ€ Ð¸Ð¸','icon'=>'calendar','href'=>'bookings.php'],
    ],
    'ÐžÐâ€˜Ð©Ð˜' => [
        'brand' => ['label'=>'ÐÐ°ÑÑâ€šÑâ‚¬Ð¾Ð¹ÐºÐ¸','icon'=>'clock','href'=>'section.php?edit=brand'],
        'security' => ['label'=>'Ð¡Ð¸Ð³ÑƒÑâ‚¬Ð½Ð¾ÑÑâ€š','icon'=>'shield','href'=>'section.php?edit=security'],
    ],
];
$bCount = is_admin() ? db()->query("SELECT COUNT(*) FROM bookings")->fetchColumn() : 0;
?>
<!doctype html>
<html lang="bg"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['name']) ?></title>
<link rel="icon" href="/assets/media/icon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/fonts/fonts.css?v=1">
<link rel="stylesheet" href="/assets/css/style.css?v=15">
<link rel="stylesheet" href="/assets/css/admin.css?v=6">
</head>
<body class="adminBody">
<div class="adminLayout">
  <?php if (is_admin()): ?>
  <button class="adminMenuToggle" id="adminMenuToggle" aria-label="Menu">â˜°</button>
  <aside class="adminSidebar" id="adminSidebar">
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
      <a href="/" class="adminSidebarLink"><?= icon('arrow','icon') ?> ÐšÑŠÐ¼ ÑÐ°Ð¹Ñâ€šÐ°</a>
      <a href="logout.php" class="adminSidebarLink"><?= icon('arrow','icon') ?> Ð˜Ð·Ñâ€¦Ð¾Ð´</a>
    </div>
  </aside>
  <?php else: ?>
  <aside class="adminSidebar" style="display:none"></aside>
  <?php endif; ?>
  <main class="adminMain">
<script>
document.getElementById('adminMenuToggle')?.addEventListener('click',function(e){
e.stopPropagation();
var s=document.getElementById('adminSidebar');
s.style.left = s.style.left==='0px' ? '-240px' : '0px';
});
document.addEventListener('click',function(e){
var s=document.getElementById('adminSidebar');
var t=document.getElementById('adminMenuToggle');
if(s && t && s.style.left==='0px' && !s.contains(e.target) && !t.contains(e.target)){
s.style.left='-240px';
}
});
document.querySelectorAll('.navItem a,.adminSidebarLink').forEach(function(a){
a.addEventListener('click',function(){if(window.innerWidth<=760){document.getElementById('adminSidebar').style.left='-240px';}});
});
</script>
