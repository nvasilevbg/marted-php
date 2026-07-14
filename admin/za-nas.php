<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings();
$GLOBALS['admin_page'] = 'about';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['about_heading','about_text'] as $k) {
        if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    }
    header('Location: za-nas.php?ok=1'); exit;
}
$title = 'За нас | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Страници</span><h1>За нас</h1></div></div>
<form method="POST" class="adminForm">
  <div><label>Заглавие</label><input name="about_heading" value="<?= e(content('about_heading')) ?>"></div>
  <div><label>Текст</label><textarea name="about_text" rows="6"><?= e(content('about_text')) ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>