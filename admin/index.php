<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/admin-functions.php';
$s = settings();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (admin_login($_POST['user'] ?? '', $_POST['pass'] ?? '')) { header('Location: section.php?edit=hero'); exit; }
    $authErr = 'Грешна парола.';
}
if (is_admin()) { header('Location: section.php?edit=hero'); exit; }
$GLOBALS['admin_page'] = '';
$title = 'Vhod | Admin';
require __DIR__ . '/../inc/admin-header.php';
?>
<div class="adminGate">
  <span class="eyebrow eyebrow-line">Админ</span>
  <h1>Вход за администратор</h1>
  <form method="POST" style="margin-top:20px;display:grid;gap:18px">
    <div><label for="ad-user">Потребител</label><input id="ad-user" type="text" name="user" placeholder="" style="background:var(--bg-3);border:1px solid var(--line);border-radius:var(--radius);padding:10px 14px;color:var(--ink);font-size:15px;width:100%;box-sizing:border-box"></div>
    <div><label for="ad-pass">Парола</label><input id="ad-pass" type="password" name="pass" placeholder="Парола" style="background:var(--bg-3);border:1px solid var(--line);border-radius:var(--radius);padding:10px 14px;color:var(--ink);font-size:15px;width:100%;box-sizing:border-box"></div>
    <button class="btn btn-primary btn-block" type="submit">Вход</button>
    <?php if (!empty($authErr)): ?><p class="formMsg err"><?= e($authErr) ?></p><?php endif; ?>
  </form>
</div>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>