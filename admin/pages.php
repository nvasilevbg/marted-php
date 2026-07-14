<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings();
$GLOBALS['admin_page'] = 'pages';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['content'] as $k => $v) {
        $st = db()->prepare("UPDATE settings SET v=? WHERE k=?");
        $st->execute([trim($v), $k]);
    }
    header('Location: pages.php?ok=1'); exit;
}
$title = 'Страници | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTabs">
  <button class="active" onclick="switchTab(event,'tab-home')">Начална</button>
  <button onclick="switchTab(event,'tab-about')">За нас</button>
</div>
<form method="POST" class="adminForm" style="max-width:none">
  <div id="tab-home" class="tabContent active">
    <div><label>Заглавие на херобанера</label><input name="content[home_hero_title]" value="<?= e(content('home_hero_title')) ?>"></div>
    <div><label>Подзаглавие на херобанера</label><textarea name="content[home_hero_lead]" rows="3"><?= e(content('home_hero_lead')) ?></textarea></div>
  </div>
  <div id="tab-about" class="tabContent">
    <div><label>Заглавие (За нас)</label><input name="content[about_heading]" value="<?= e(content('about_heading')) ?>"></div>
    <div><label>Текст (За нас)</label><textarea name="content[about_text]" rows="5"><?= e(content('about_text')) ?></textarea></div>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Запази промените</button>
</form>
<script>
function switchTab(e, id) {
  e.preventDefault();
  document.querySelectorAll('.adminTabs button').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tabContent').forEach(t => t.classList.remove('active'));
  e.target.classList.add('active');
  document.getElementById(id).classList.add('active');
}
</script>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>