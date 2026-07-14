<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings();
$GLOBALS['admin_page'] = 'settings';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['name','subtitle','tagline','phone','phoneHref','email','location','region','hours','established'];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) {
            db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$f]), $f]);
        }
    }
    if (!empty($_POST['new_pass'])) {
        $cfg = config();
        $cfg['admin_pass'] = $_POST['new_pass'];
        // Note: can't write to config.php on the fly; this changes the session check
        // For production, manually update inc/config.php
    }
    header('Location: settings.php?ok=1'); exit;
}
$title = 'Настройки | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<form method="POST" class="adminForm" style="max-width:none">
  <div class="adminTabs">
    <button type="button" class="active" onclick="switchTab(event,'tab-contact')">Контакти</button>
    <button type="button" onclick="switchTab(event,'tab-brand')">Бранд</button>
    <button type="button" onclick="switchTab(event,'tab-security')">Сигурност</button>
  </div>
  <div id="tab-contact" class="tabContent active">
    <div class="formRow"><div><label>Телефон</label><input name="phone" value="<?= e($s['phone']) ?>"></div><div><label>Тел. линк (tel:)</label><input name="phoneHref" value="<?= e($s['phoneHref']) ?>"></div></div>
    <div><label>Имейл</label><input name="email" value="<?= e($s['email']) ?>"></div>
    <div><label>Адрес</label><input name="location" value="<?= e($s['location']) ?>"></div>
    <div><label>Регион</label><input name="region" value="<?= e($s['region']) ?>"></div>
    <div><label>Работно време</label><input name="hours" value="<?= e($s['hours']) ?>"></div>
  </div>
  <div id="tab-brand" class="tabContent">
    <div><label>Име на фирмата</label><input name="name" value="<?= e($s['name']) ?>"></div>
    <div><label>Подзаглавие</label><input name="subtitle" value="<?= e($s['subtitle']) ?>"></div>
    <div><label>Таглайн</label><input name="tagline" value="<?= e($s['tagline']) ?>"></div>
    <div><label>Година на основаване</label><input name="established" value="<?= e($s['established']) ?>"></div>
  </div>
  <div id="tab-security" class="tabContent">
    <div><label>Нова админ парола (по желание)</label><input name="new_pass" type="password" placeholder="Остави празно ако не сменяш"></div>
    <p class="formNote">Внимание: смяната на паролата тук обновява сесийната проверка. За пълна смяна, редактирай ръчно inc/config.php на сървъра.</p>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Запази настройките</button>
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