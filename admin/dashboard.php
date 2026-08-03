<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$GLOBALS['admin_page'] = 'dashboard';
$s = settings();
$title = 'Dashboard | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<div class="adminTop">
  <div>
    <span class="eyebrow eyebrow-line">Анализи</span>
    <h1>Dashboard</h1>
  </div>
</div>
<iframe
  src="https://cloud.umami.is/share/QtEQAzlrIEkyt1Kr"
  style="width:100%;height:calc(100vh - 160px);border:1px solid var(--line);border-radius:var(--radius);background:var(--bg-2)"
  loading="lazy"
  title="Umami Analytics"
></iframe>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
