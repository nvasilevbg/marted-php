<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
$s = settings();

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['pass'] ?? '';
    if (admin_login($pass)) { header('Location: index.php'); exit; }
    $authErr = 'Грешна парола.';
}

if (is_admin()) {
    $bCount = db()->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    $pCount = db()->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $pending = db()->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetchColumn();
}
$title = 'Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (is_admin()): ?>
<div class="adminTop">
  <div><span class="eyebrow eyebrow-line">Админ панел</span><h1>MarTed · управление</h1></div>
</div>
<div class="adminCards">
  <a href="projects.php" class="adminCard"><div class="acNum"><?= $pCount ?></div><div class="acLabel">Проекти</div></a>
  <a href="bookings.php" class="adminCard"><div class="acNum"><?= $bCount ?></div><div class="acLabel">Резервации</div></a>
  <a href="bookings.php?filter=pending" class="adminCard"><div class="acNum"><?= $pending ?></div><div class="acLabel">Чакат потвърждение</div></a>
  <a href="logout.php" class="adminCard small">Изход</a>
</div>
<?php else: ?>
<section class="section">
  <div class="container adminGate" style="max-width:440px">
    <span class="eyebrow eyebrow-line">Админ</span>
    <h1>Вход за администратор</h1>
    <form method="POST" style="margin-top:20px;display:grid;gap:18px">
      <div class="field"><label for="ad-pass">Парола</label><input id="ad-pass" type="password" name="pass" placeholder="Админ парола"></div>
      <button class="btn btn-primary btn-block" type="submit">Вход</button>
      <?php if (!empty($authErr)): ?><p class="formMsg err"><?= e($authErr) ?></p><?php endif; ?>
    </form>
  </div>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>