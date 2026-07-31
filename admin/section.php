<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
$s = settings();
$edit = $_GET['edit'] ?? 'hero';
$valid = ['hero','stats','contact','about','brand'];
if (!in_array($edit, $valid)) $edit = 'hero';
$GLOBALS['admin_page'] = $edit;
$stats = db()->query("SELECT * FROM stats ORDER BY sort_order ASC, id ASC")->fetchAll();

// Handle saves
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? '';
    if ($section === 'hero') {
        foreach (['home_hero_title','home_hero_lead'] as $k) if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
        $heroImg = $_POST['hero_image_existing'] ?? '';
        $uploaded = upload_image('hero_image_file', __DIR__ . '/../assets/media/projects');
        if ($uploaded) $heroImg = $uploaded;
        if ($heroImg) db()->prepare("UPDATE settings SET v=? WHERE k='home_hero_image'")->execute([$heroImg]);
    } elseif ($section === 'stats') {
        if (isset($_POST['stat_ids'])) {
            foreach ($_POST['stat_ids'] as $i => $id) {
                $val = trim($_POST['stat_values'][$i] ?? ''); $lbl = trim($_POST['stat_labels'][$i] ?? '');
                if ($id && $val) db()->prepare("UPDATE stats SET svalue=?,slabel=? WHERE id=?")->execute([$val,$lbl,$id]);
            }
        }
    } elseif ($section === 'contact') {
        foreach (['phone','phoneHref','email','location','region','hours','facebook','instagram'] as $k) if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    } elseif ($section === 'about') {
        foreach (['about_heading','about_text'] as $k) if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    } elseif ($section === 'brand') {
        foreach (['name','subtitle','tagline','established'] as $k) if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    }
    header("Location: section.php?edit=$section&ok=1"); exit;
}

$titles = ['hero'=>'Херобанер','stats'=>'Статистики','contact'=>'Контакти','about'=>'За нас','brand'=>'Настройки'];
$title = $titles[$edit] . ' | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Секция</span><h1><?= e($titles[$edit]) ?></h1></div></div>

<?php if ($edit === 'hero'): ?>
<form method="POST" enctype="multipart/form-data" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="hero">
  <div><label>Заглавие</label><input name="home_hero_title" value="<?= e(content('home_hero_title')) ?>"></div>
  <div><label>Подзаглавие (текст)</label><textarea name="home_hero_lead" rows="3"><?= e(content('home_hero_lead')) ?></textarea></div>
  <div><label>Снимка на херобанера</label>
    <?php $heroImg = content('home_hero_image', '/assets/media/hero-kitchen.jpg'); ?>
    <?php if ($heroImg): ?><img src="<?= e($heroImg) ?>" style="max-height:100px;border-radius:4px;margin-bottom:8px"><?php endif; ?>
    <input type="hidden" name="hero_image_existing" value="<?= e($heroImg) ?>">
    <input type="file" name="hero_image_file" accept="image/*">
  </div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($edit === 'stats'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="stats">
  <?php foreach ($stats as $st): ?>
  <input type="hidden" name="stat_ids[]" value="<?= e($st['id']) ?>">
  <div class="formRow" style="margin-bottom:12px"><div><label>Стойност</label><input name="stat_values[]" value="<?= e($st['svalue']) ?>"></div><div><label>Етикет</label><input name="stat_labels[]" value="<?= e($st['slabel']) ?>"></div></div>
  <?php endforeach; ?>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($edit === 'contact'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="contact">
  <div class="formRow"><div><label>Телефон</label><input name="phone" value="<?= e($s['phone']) ?>"></div><div><label>Тел. линк</label><input name="phoneHref" value="<?= e($s['phoneHref']) ?>"></div></div>
  <div><label>Имейл</label><input name="email" value="<?= e($s['email']) ?>"></div>
  <div><label>Адрес</label><input name="location" value="<?= e($s['location']) ?>"></div>
  <div><label>Регион</label><input name="region" value="<?= e($s['region']) ?>"></div>
  <div><label>Работно време</label><input name="hours" value="<?= e($s['hours']) ?>"></div>
    <div class="formRow"><div><label>Facebook</label><input name="facebook" value="<?= e($s['facebook']) ?>" placeholder="https://facebook.com/..."></div><div><label>Instagram</label><input name="instagram" value="<?= e($s['instagram']) ?>" placeholder="https://instagram.com/..."></div></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($edit === 'about'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="about">
  <div><label>Заглавие</label><input name="about_heading" value="<?= e(content('about_heading')) ?>"></div>
  <div><label>Текст</label><textarea name="about_text" rows="6"><?= e(content('about_text')) ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($edit === 'brand'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="brand">
  <div><label>Име на фирмата</label><input name="name" value="<?= e($s['name']) ?>"></div>
  <div><label>Подзаглавие</label><input name="subtitle" value="<?= e($s['subtitle']) ?>"></div>
  <div><label>Таглайн</label><input name="tagline" value="<?= e($s['tagline']) ?>"></div>
  <div><label>Година на основаване</label><input name="established" value="<?= e($s['established']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
