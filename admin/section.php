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
        foreach (['phone','phoneHref','email','location','region','hours'] as $k) if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    } elseif ($section === 'about') {
        foreach (['about_heading','about_text'] as $k) if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    } elseif ($section === 'brand') {
        foreach (['name','subtitle','tagline','established'] as $k) if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
    }
    header("Location: section.php?edit=$section&ok=1"); exit;
}

$titles = ['hero'=>'Ð¥ÐµÑ€Ð¾Ð±Ð°Ð½ÐµÑ€','stats'=>'Ð¡Ñ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÐ¸','contact'=>'ÐšÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð¸','about'=>'Ð—Ð° Ð½Ð°Ñ','brand'=>'ÐÐ°ÑÑ‚Ñ€Ð¾Ð¹ÐºÐ¸'];
$title = $titles[$edit] . ' | ÐÐ´Ð¼Ð¸Ð½ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Ð—Ð°Ð¿Ð°Ð·ÐµÐ½Ð¾.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Ð¡ÐµÐºÑ†Ð¸Ñ</span><h1><?= e($titles[$edit]) ?></h1></div></div>

<?php if ($edit === 'hero'): ?>
<form method="POST" enctype="multipart/form-data" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="hero">
  <div><label>Ð—Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ</label><input name="home_hero_title" value="<?= e(content('home_hero_title')) ?>"></div>
  <div><label>ÐŸÐ¾Ð´Ð·Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ (Ñ‚ÐµÐºÑÑ‚)</label><textarea name="home_hero_lead" rows="3"><?= e(content('home_hero_lead')) ?></textarea></div>
  <div><label>Ð¡Ð½Ð¸Ð¼ÐºÐ° Ð½Ð° Ñ…ÐµÑ€Ð¾Ð±Ð°Ð½ÐµÑ€Ð°</label>
    <?php $heroImg = content('home_hero_image', '/assets/media/hero-kitchen.jpg'); ?>
    <?php if ($heroImg): ?><img src="<?= e($heroImg) ?>" style="max-height:100px;border-radius:4px;margin-bottom:8px"><?php endif; ?>
    <input type="hidden" name="hero_image_existing" value="<?= e($heroImg) ?>">
    <input type="file" name="hero_image_file" accept="image/*">
  </div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'stats'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="stats">
  <?php foreach ($stats as $st): ?>
  <input type="hidden" name="stat_ids[]" value="<?= e($st['id']) ?>">
  <div class="formRow" style="margin-bottom:12px"><div><label>Ð¡Ñ‚Ð¾Ð¹Ð½Ð¾ÑÑ‚</label><input name="stat_values[]" value="<?= e($st['svalue']) ?>"></div><div><label>Ð•Ñ‚Ð¸ÐºÐµÑ‚</label><input name="stat_labels[]" value="<?= e($st['slabel']) ?>"></div></div>
  <?php endforeach; ?>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'contact'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="contact">
  <div class="formRow"><div><label>Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½</label><input name="phone" value="<?= e($s['phone']) ?>"></div><div><label>Ð¢ÐµÐ». Ð»Ð¸Ð½Ðº</label><input name="phoneHref" value="<?= e($s['phoneHref']) ?>"></div></div>
  <div><label>Ð˜Ð¼ÐµÐ¹Ð»</label><input name="email" value="<?= e($s['email']) ?>"></div>
  <div><label>ÐÐ´Ñ€ÐµÑ</label><input name="location" value="<?= e($s['location']) ?>"></div>
  <div><label>Ð ÐµÐ³Ð¸Ð¾Ð½</label><input name="region" value="<?= e($s['region']) ?>"></div>
  <div><label>Ð Ð°Ð±Ð¾Ñ‚Ð½Ð¾ Ð²Ñ€ÐµÐ¼Ðµ</label><input name="hours" value="<?= e($s['hours']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'about'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="about">
  <div><label>Ð—Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ</label><input name="about_heading" value="<?= e(content('about_heading')) ?>"></div>
  <div><label>Ð¢ÐµÐºÑÑ‚</label><textarea name="about_text" rows="6"><?= e(content('about_text')) ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'brand'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="brand">
  <div><label>Ð˜Ð¼Ðµ Ð½Ð° Ñ„Ð¸Ñ€Ð¼Ð°Ñ‚Ð°</label><input name="name" value="<?= e($s['name']) ?>"></div>
  <div><label>ÐŸÐ¾Ð´Ð·Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ</label><input name="subtitle" value="<?= e($s['subtitle']) ?>"></div>
  <div><label>Ð¢Ð°Ð³Ð»Ð°Ð¹Ð½</label><input name="tagline" value="<?= e($s['tagline']) ?>"></div>
  <div><label>Ð“Ð¾Ð´Ð¸Ð½Ð° Ð½Ð° Ð¾ÑÐ½Ð¾Ð²Ð°Ð²Ð°Ð½Ðµ</label><input name="established" value="<?= e($s['established']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
