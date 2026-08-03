<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/admin-functions.php';
$s = settings();
$current = $GLOBALS['admin_page'] ?? '';
$navGroups = [
    'ÃÂÃÂÃÂ§ÃÂÃâ€ºÃÂÃÂ' => [
        'dashboard' => ['label'=>'Dashboard','icon'=>'pin','href'=>'dashboard.php'],
        'hero' => ['label'=>'ÃÂ¥ÃÂµÃ‘â‚¬ÃÂ¾ÃÂ±ÃÂ°ÃÂ½ÃÂµÃ‘â‚¬','icon'=>'pin','href'=>'section.php?edit=hero'],
        'stats' => ['label'=>'ÃÂ¡Ã‘â€šÃÂ°Ã‘â€šÃÂ¸Ã‘ÂÃ‘â€šÃÂ¸ÃÂºÃÂ¸','icon'=>'users','href'=>'section.php?edit=stats'],
        'services' => ['label'=>'ÃÂ£Ã‘ÂÃÂ»Ã‘Æ’ÃÂ³ÃÂ¸','icon'=>'shield','href'=>'services.php'],
        'projects' => ['label'=>'ÃÅ¸Ã‘â‚¬ÃÂ¾ÃÂµÃÂºÃ‘â€šÃÂ¸','icon'=>'drill','href'=>'projects.php'],
        'testimonials' => ['label'=>'ÃÅ¾Ã‘â€šÃÂ·ÃÂ¸ÃÂ²ÃÂ¸','icon'=>'users','href'=>'testimonials.php'],
    ],
    'Ãâ€ÃÂ ÃÂ£Ãâ€œÃËœ ÃÂ¡ÃÂ¢ÃÂ ÃÂÃÂÃËœÃÂ¦ÃËœ' => [
        'contact' => ['label'=>'ÃÅ¡ÃÂ¾ÃÂ½Ã‘â€šÃÂ°ÃÂºÃ‘â€šÃÂ¸','icon'=>'phone','href'=>'section.php?edit=contact'],
        'about' => ['label'=>'Ãâ€”ÃÂ° ÃÂ½ÃÂ°Ã‘Â','icon'=>'users','href'=>'section.php?edit=about'],
    ],
    'ÃÅ¾ÃÅ¸Ãâ€¢ÃÂ ÃÂÃÂ¦ÃËœÃËœ' => [
        'bookings' => ['label'=>'ÃÂ ÃÂµÃÂ·ÃÂµÃ‘â‚¬ÃÂ²ÃÂ°Ã‘â€ ÃÂ¸ÃÂ¸','icon'=>'calendar','href'=>'bookings.php'],
    ],
    'ÃÅ¾Ãâ€˜ÃÂ©ÃËœ' => [
        'brand' => ['label'=>'ÃÂÃÂ°Ã‘ÂÃ‘â€šÃ‘â‚¬ÃÂ¾ÃÂ¹ÃÂºÃÂ¸','icon'=>'clock','href'=>'section.php?edit=brand'],
        'security' => ['label'=>'ÃÂ¡ÃÂ¸ÃÂ³Ã‘Æ’Ã‘â‚¬ÃÂ½ÃÂ¾Ã‘ÂÃ‘â€š','icon'=>'shield','href'=>'section.php?edit=security'],
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
  <button class="adminMenuToggle" id="adminMenuToggle" aria-label="Menu">Ã¢ËœÂ°</button>
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
      <a href="/" class="adminSidebarLink"><?= icon('arrow','icon') ?> ÃÅ¡Ã‘Å ÃÂ¼ Ã‘ÂÃÂ°ÃÂ¹Ã‘â€šÃÂ°</a>
      <a href="logout.php" class="adminSidebarLink"><?= icon('arrow','icon') ?> ÃËœÃÂ·Ã‘â€¦ÃÂ¾ÃÂ´</a>
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
