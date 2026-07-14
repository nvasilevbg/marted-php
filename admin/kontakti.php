<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings();
$GLOBALS['admin_page'] = 'contact';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['phone','phoneHref','email','location','region','hours'] as $k) {
        if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    }
    header('Location: kontakti.php?ok=1'); exit;
}
$title = 'Контакти | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Страници</span><h1>Контакти</h1></div></div>
<form method="POST" class="adminForm">
  <div class="formRow"><div><label>Телефон</label><input name="phone" value="<?= e($s['phone']) ?>"></div><div><label>Тел. линк (tel:)</label><input name="phoneHref" value="<?= e($s['phoneHref']) ?>"></div></div>
  <div><label>Имейл</label><input name="email" value="<?= e($s['email']) ?>"></div>
  <div><label>Адрес</label><input name="location" value="<?= e($s['location']) ?>"></div>
  <div><label>Регион</label><input name="region" value="<?= e($s['region']) ?>"></div>
  <div><label>Работно време</label><input name="hours" value="<?= e($s['hours']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>