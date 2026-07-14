<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings();
$GLOBALS['admin_page'] = 'settings';
$stats = db()->query("SELECT * FROM stats ORDER BY sort_order ASC, id ASC")->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab = $_POST['tab'] ?? 'contact';
    if ($tab === 'contact') {
        foreach (['phone','phoneHref','email','location','region','hours'] as $k) {
            if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
        }
    } elseif ($tab === 'home') {
        foreach (['home_hero_title','home_hero_lead'] as $k) {
            if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
        }
        // update stats
        if (isset($_POST['stat_ids'])) {
            foreach ($_POST['stat_ids'] as $i => $id) {
                $val = trim($_POST['stat_values'][$i] ?? '');
                $lbl = trim($_POST['stat_labels'][$i] ?? '');
                if ($id && $val) db()->prepare("UPDATE stats SET svalue=?,slabel=? WHERE id=?")->execute([$val,$lbl,$id]);
            }
        }
    } elseif ($tab === 'about') {
        foreach (['about_heading','about_text'] as $k) {
            if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
        }
    } elseif ($tab === 'brand') {
        foreach (['name','subtitle','tagline','established'] as $k) {
            if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
        }
    }
    header('Location: settings.php?ok=1&tab=' . $tab); exit;
}
$activeTab = $_GET['tab'] ?? 'contact';
$title = 'Настройки | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Админ</span><h1>Настройки</h1></div></div>
<div class="adminTabs">
  <button type="button" class="<?= $activeTab==='contact'?'active':'' ?>" onclick="location.href='settings.php?tab=contact'">Контакти</button>
  <button type="button" class="<?= $activeTab==='home'?'active':'' ?>" onclick="location.href='settings.php?tab=home'">Начална</button>
  <button type="button" class="<?= $activeTab==='about'?'active':'' ?>" onclick="location.href='settings.php?tab=about'">За нас</button>
  <button type="button" class="<?= $activeTab==='brand'?'active':'' ?>" onclick="location.href='settings.php?tab=brand'">Бранд</button>
</div>

<?php if ($activeTab === 'contact'): ?>
<form method="POST" class="adminForm">
  <input type="hidden" name="tab" value="contact">
  <div class="formRow"><div><label>Телефон</label><input name="phone" value="<?= e($s['phone']) ?>"></div><div><label>Тел. линк</label><input name="phoneHref" value="<?= e($s['phoneHref']) ?>"></div></div>
  <div><label>Имейл</label><input name="email" value="<?= e($s['email']) ?>"></div>
  <div><label>Адрес</label><input name="location" value="<?= e($s['location']) ?>"></div>
  <div><label>Регион</label><input name="region" value="<?= e($s['region']) ?>"></div>
  <div><label>Работно време</label><input name="hours" value="<?= e($s['hours']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($activeTab === 'home'): ?>
<form method="POST" class="adminForm">
  <input type="hidden" name="tab" value="home">
  <div><label>Заглавие на херобанера</label><input name="home_hero_title" value="<?= e(content('home_hero_title')) ?>"></div>
  <div><label>Подзаглавие (текст)</label><textarea name="home_hero_lead" rows="3"><?= e(content('home_hero_lead')) ?></textarea></div>
  <div style="border-top:1px solid var(--line-soft);padding-top:16px;margin-top:8px"><label style="margin-bottom:12px">Статистики</label>
    <?php foreach ($stats as $i => $st): ?>
    <input type="hidden" name="stat_ids[]" value="<?= e($st['id']) ?>">
    <div class="formRow" style="margin-bottom:10px"><div><input name="stat_values[]" value="<?= e($st['svalue']) ?>" placeholder="500+"></div><div><input name="stat_labels[]" value="<?= e($st['slabel']) ?>" placeholder="Доволни клиенти"></div></div>
    <?php endforeach; ?>
  </div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($activeTab === 'about'): ?>
<form method="POST" class="adminForm">
  <input type="hidden" name="tab" value="about">
  <div><label>Заглавие</label><input name="about_heading" value="<?= e(content('about_heading')) ?>"></div>
  <div><label>Текст</label><textarea name="about_text" rows="6"><?= e(content('about_text')) ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($activeTab === 'brand'): ?>
<form method="POST" class="adminForm">
  <input type="hidden" name="tab" value="brand">
  <div><label>Име на фирмата</label><input name="name" value="<?= e($s['name']) ?>"></div>
  <div><label>Подзаглавие</label><input name="subtitle" value="<?= e($s['subtitle']) ?>"></div>
  <div><label>Таглайн</label><input name="tagline" value="<?= e($s['tagline']) ?>"></div>
  <div><label>Година на основаване</label><input name="established" value="<?= e($s['established']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>