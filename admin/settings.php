<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings();
$GLOBALS['admin_page'] = 'settings';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['name','subtitle','tagline','established'] as $f) {
        if (isset($_POST[$f])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$f]), $f]);
    }
    header('Location: settings.php?ok=1'); exit;
}
$title = 'Настройки | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Админ</span><h1>Настройки</h1></div></div>
<div class="adminTabs">
  <button type="button" class="active" onclick="switchTab(event,'tab-brand')">Бранд</button>
  <button type="button" onclick="switchTab(event,'tab-security')">Сигурност</button>
</div>
<form method="POST" class="adminForm" style="max-width:none">
  <div id="tab-brand" class="tabContent active">
    <div><label>Име на фирмата</label><input name="name" value="<?= e($s['name']) ?>"></div>
    <div><label>Подзаглавие</label><input name="subtitle" value="<?= e($s['subtitle']) ?>"></div>
    <div><label>Таглайн</label><input name="tagline" value="<?= e($s['tagline']) ?>"></div>
    <div><label>Година на основаване</label><input name="established" value="<?= e($s['established']) ?>"></div>
  </div>
  <div id="tab-security" class="tabContent">
    <p class="formNote">За смяна на админ паролата — редактирай ръчно inc/config.php на сървъра (поле 'admin_pass').</p>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
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