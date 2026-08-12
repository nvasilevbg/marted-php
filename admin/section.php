<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
$s = settings();
$edit = $_GET['edit'] ?? 'hero';
$valid = ['hero','stats','contact','about','brand','legal','security'];
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
        // Handle multiple hero images (carousel)
        if (!empty($_FILES['hero_images']['name'][0])) {
            $existing = json_decode(setting('home_hero_images', '[]'), true) ?: [];
            foreach ($_FILES['hero_images']['tmp_name'] as $i => $tmp) {
                if ($_FILES['hero_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $webp = convert_to_webp_upload('hero_images', $i, __DIR__ . '/../assets/media/projects');
                    if ($webp) $existing[] = $webp;
                }
            }
            $stHero = db()->prepare("UPDATE settings SET v=? WHERE k='home_hero_images'");
            $stHero->execute([json_encode($existing)]);
            if ($stHero->rowCount() === 0) {
                db()->prepare("INSERT INTO settings (k,v) VALUES ('home_hero_images',?)")->execute([json_encode($existing)]);
            }
        }
        // Delete a hero carousel image
        if (!empty($_POST['delete_hero_image'])) {
            $existing = json_decode(setting('home_hero_images', '[]'), true) ?: [];
            $existing = array_values(array_filter($existing, fn($img) => $img !== $_POST['delete_hero_image']));
            $stDel = db()->prepare("UPDATE settings SET v=? WHERE k='home_hero_images'");
        $stDel->execute([json_encode($existing)]);
        if ($stDel->rowCount() === 0) {
            db()->prepare("INSERT INTO settings (k,v) VALUES ('home_hero_images',?)")->execute([json_encode($existing)]);
        }
        }
    } elseif ($section === 'legal') {
        foreach (['politika_poveritelnost','usloviya_polzvane','politika_biskvitki'] as $lk) {
            if (isset($_POST[$lk])) {
                $st = db()->prepare("UPDATE settings SET v=? WHERE k=?");
                $st->execute([$_POST[$lk], $lk]);
                if ($st->rowCount() === 0) {
                    db()->prepare("INSERT INTO settings (k,v) VALUES (?,?)")->execute([$lk, $_POST[$lk]]);
                }
            }
        }
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
    if ($section === 'security' && !empty($GLOBALS['sec_error'])) {
        // Don't redirect â€” show error
    } else {
        header("Location: section.php?edit=$section&ok=1"); exit;
    }
}

$titles = ['hero'=>'Ð¥ÐµÑ€Ð¾Ð±Ð°Ð½ÐµÑ€','stats'=>'Ð¡Ñ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÐ¸','contact'=>'ÐšÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð¸','about'=>'Ð—Ð° Ð½Ð°Ñ','brand'=>'ÐÐ°ÑÑ‚Ñ€Ð¾Ð¹ÐºÐ¸','security'=>'Ð¡Ð¸Ð³ÑƒÑ€Ð½Ð¾ÑÑ‚','legal'=>'ÐŸÑ€Ð°Ð²Ð½Ð¸ ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ†Ð¸'];
$title = $titles[$edit] . ' | ÐÐ´Ð¼Ð¸Ð½ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Ð—Ð°Ð¿Ð°Ð·ÐµÐ½Ð¾.</p><?php elseif ($edit === 'legal'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="legal">
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð¿Ð¾Ð²ÐµÑ€Ð¸Ñ‚ÐµÐ»Ð½Ð¾ÑÑ‚</label><textarea name="politika_poveritelnost" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_poveritelnost','') ?></textarea></div>
  <div><label>Ð£ÑÐ»Ð¾Ð²Ð¸Ñ Ð·Ð° Ð¿Ð¾Ð»Ð·Ð²Ð°Ð½Ðµ</label><textarea name="usloviya_polzvane" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('usloviya_polzvane','') ?></textarea></div>
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð±Ð¸ÑÐºÐ²Ð¸Ñ‚ÐºÐ¸</label><textarea name="politika_biskvitki" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_biskvitki','') ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>
<?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Ð¡ÐµÐºÑ†Ð¸Ñ</span><h1><?= e($titles[$edit]) ?></h1></div></div>

<?php if ($edit === 'hero'): ?>
<form method="POST" enctype="multipart/form-data" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="hero">
  <div><label>Ð—Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ</label><input name="home_hero_title" value="<?= e(content('home_hero_title')) ?>"></div>
  <div><label>ÐŸÐ¾Ð´Ð·Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ (Ñ‚ÐµÐºÑÑ‚)</label><textarea name="home_hero_lead" rows="3"><?= e(content('home_hero_lead')) ?></textarea></div>
  <div><label>Ð¡Ð½Ð¸Ð¼ÐºÐ° Ð½Ð° Ñ…ÐµÑ€Ð¾Ð±Ð°Ð½ÐµÑ€Ð°</label>
    <?php $heroImg = content('home_hero_image', '/assets/media/hero-kitchen.jpg'); ?>
    <?php if ($heroImg): ?><img src="<?= e($heroImg) ?>" style="max-height:100px;border-radius:4px;margin-bottom:8px"><?php elseif ($edit === 'legal'): ?>
<form method="POST" class="adminForm">
  
  <?= csrf_field() ?><input type="hidden" name="section" value="legal">
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð¿Ð¾Ð²ÐµÑ€Ð¸Ñ‚ÐµÐ»Ð½Ð¾ÑÑ‚</label><textarea name="politika_poveritelnost" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_poveritelnost','') ?></textarea></div>
  <div><label>Ð£ÑÐ»Ð¾Ð²Ð¸Ñ Ð·Ð° Ð¿Ð¾Ð»Ð·Ð²Ð°Ð½Ðµ</label><textarea name="usloviya_polzvane" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('usloviya_polzvane','') ?></textarea></div>
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð±Ð¸ÑÐºÐ²Ð¸Ñ‚ÐºÐ¸</label><textarea name="politika_biskvitki" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_biskvitki','') ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>
<?php endif; ?>
    <input type="hidden" name="hero_image_existing" value="<?= e($heroImg) ?>">
    <input type="file" name="hero_image_file" accept="image/*">
  </div>
  <div style="margin-top:20px;border-top:1px solid var(--line);padding-top:20px">
    <label>Ð¡Ð½Ð¸Ð¼ÐºÐ¸ Ð·Ð° ÐºÐ°Ñ€ÑƒÑÐµÐ»</label>
    <p style="font-size:13px;color:var(--muted);margin-bottom:12px">ÐšÐ°Ñ‡ÐµÑ‚Ðµ Ð½ÑÐºÐ¾Ð»ÐºÐ¾ ÑÐ½Ð¸Ð¼ÐºÐ¸. ÐÐ²Ñ‚Ð¾Ð¼Ð°Ñ‚Ð¸Ñ‡Ð½Ð¾ ÑÐµ ÐºÐ¾Ð½Ð²ÐµÑ€Ñ‚Ð¸Ñ€Ð°Ñ‚ Ð½Ð° WebP.</p>
    <?php $heroImages = json_decode(setting('home_hero_images', '[]'), true) ?: []; ?>
    <?php if (!empty($heroImages)): ?>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px">
      <?php foreach ($heroImages as $img): ?>
      <div style="position:relative">
        <img src="<?= e($img) ?>" style="width:100%;height:80px;object-fit:cover;border-radius:4px;border:1px solid var(--line)">
        <button type="submit" name="delete_hero_image" value="<?= e($img) ?>" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.7);color:#fff;border:none;border-radius:4px;width:24px;height:24px;cursor:pointer;font-size:14px">Ã—</button>
      </div>
      <?php endforeach; ?>
    </div>
    <?php elseif ($edit === 'legal'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="legal">
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð¿Ð¾Ð²ÐµÑ€Ð¸Ñ‚ÐµÐ»Ð½Ð¾ÑÑ‚</label><textarea name="politika_poveritelnost" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_poveritelnost','') ?></textarea></div>
  <div><label>Ð£ÑÐ»Ð¾Ð²Ð¸Ñ Ð·Ð° Ð¿Ð¾Ð»Ð·Ð²Ð°Ð½Ðµ</label><textarea name="usloviya_polzvane" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('usloviya_polzvane','') ?></textarea></div>
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð±Ð¸ÑÐºÐ²Ð¸Ñ‚ÐºÐ¸</label><textarea name="politika_biskvitki" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_biskvitki','') ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>
<?php endif; ?>
    <input type="file" name="hero_images[]" multiple accept="image/*" style="margin-bottom:8px">
  </div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'stats'): ?>
<form method="POST" class="adminForm">
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
  <input type="hidden" name="section" value="contact">
  <div class="formRow"><div><label>Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½</label><input name="phone" value="<?= e($s['phone']) ?>"></div><div><label>Ð¢ÐµÐ». Ð»Ð¸Ð½Ðº</label><input name="phoneHref" value="<?= e($s['phoneHref']) ?>"></div></div>
  <div><label>Ð˜Ð¼ÐµÐ¹Ð»</label><input name="email" value="<?= e($s['email']) ?>"></div>
  <div><label>ÐÐ´Ñ€ÐµÑ</label><input name="location" value="<?= e($s['location']) ?>"></div>
  <div><label>Ð ÐµÐ³Ð¸Ð¾Ð½</label><input name="region" value="<?= e($s['region']) ?>"></div>
  <div><label>Ð Ð°Ð±Ð¾Ñ‚Ð½Ð¾ Ð²Ñ€ÐµÐ¼Ðµ</label><input name="hours" value="<?= e($s['hours']) ?>"></div>
    <div class="formRow"><div><label>Facebook</label><input name="facebook" value="<?= e($s['facebook']) ?>" placeholder="https://facebook.com/..."></div><div><label>Instagram</label><input name="instagram" value="<?= e($s['instagram']) ?>" placeholder="https://instagram.com/..."></div></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'about'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="about">
  <div><label>Ð—Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ</label><input name="about_heading" value="<?= e(content('about_heading')) ?>"></div>
  <div><label>Ð¢ÐµÐºÑÑ‚</label><textarea name="about_text" rows="6"><?= e(content('about_text')) ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'brand'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="brand">
  <div><label>Ð˜Ð¼Ðµ Ð½Ð° Ñ„Ð¸Ñ€Ð¼Ð°Ñ‚Ð°</label><input name="name" value="<?= e($s['name']) ?>"></div>
  <div><label>ÐŸÐ¾Ð´Ð·Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ</label><input name="subtitle" value="<?= e($s['subtitle']) ?>"></div>
  <div><label>Ð¢Ð°Ð³Ð»Ð°Ð¹Ð½</label><input name="tagline" value="<?= e($s['tagline']) ?>"></div>
  <div><label>Ð“Ð¾Ð´Ð¸Ð½Ð° Ð½Ð° Ð¾ÑÐ½Ð¾Ð²Ð°Ð²Ð°Ð½Ðµ</label><input name="established" value="<?= e($s['established']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>

<?php elseif ($edit === 'security'): ?>
<?php if (!empty($GLOBALS['sec_ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">ÐŸÐ°Ñ€Ð¾Ð»Ð°Ñ‚Ð° Ðµ ÑÐ¼ÐµÐ½ÐµÐ½Ð° ÑƒÑÐ¿ÐµÑˆÐ½Ð¾.</p><?php elseif ($edit === 'legal'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="legal">
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð¿Ð¾Ð²ÐµÑ€Ð¸Ñ‚ÐµÐ»Ð½Ð¾ÑÑ‚</label><textarea name="politika_poveritelnost" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_poveritelnost','') ?></textarea></div>
  <div><label>Ð£ÑÐ»Ð¾Ð²Ð¸Ñ Ð·Ð° Ð¿Ð¾Ð»Ð·Ð²Ð°Ð½Ðµ</label><textarea name="usloviya_polzvane" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('usloviya_polzvane','') ?></textarea></div>
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð±Ð¸ÑÐºÐ²Ð¸Ñ‚ÐºÐ¸</label><textarea name="politika_biskvitki" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_biskvitki','') ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>
<?php endif; ?>
<?php if (!empty($GLOBALS['sec_error'])): ?><p class="formMsg err" style="margin-bottom:16px"><?= e($GLOBALS['sec_error']) ?></p><?php elseif ($edit === 'legal'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="legal">
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð¿Ð¾Ð²ÐµÑ€Ð¸Ñ‚ÐµÐ»Ð½Ð¾ÑÑ‚</label><textarea name="politika_poveritelnost" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_poveritelnost','') ?></textarea></div>
  <div><label>Ð£ÑÐ»Ð¾Ð²Ð¸Ñ Ð·Ð° Ð¿Ð¾Ð»Ð·Ð²Ð°Ð½Ðµ</label><textarea name="usloviya_polzvane" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('usloviya_polzvane','') ?></textarea></div>
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð±Ð¸ÑÐºÐ²Ð¸Ñ‚ÐºÐ¸</label><textarea name="politika_biskvitki" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_biskvitki','') ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>
<?php endif; ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="security">
  <div><label>ÐŸÐ¾Ñ‚Ñ€ÐµÐ±Ð¸Ñ‚ÐµÐ» Ð¸Ð¼Ðµ</label><input name="new_user" value="<?= e(setting('admin_user', config()['admin_user'] ?? 'admin')) ?>"></div>
  <div><label>Ð¢ÐµÐºÑƒÑ‰Ð° Ð¿Ð°Ñ€Ð¾Ð»Ð°</label><input type="password" name="current_pass" required></div>
  <div><label>ÐÐ¾Ð²Ð° Ð¿Ð°Ñ€Ð¾Ð»Ð° (min 6 ÑÐ¸Ð¼Ð²Ð¾Ð»Ð°)</label><input type="password" name="new_pass" required></div>
  <div><label>ÐŸÐ¾Ð²Ñ‚Ð¾Ñ€Ð¸ Ð½Ð¾Ð²Ð°Ñ‚Ð°</label><input type="password" name="confirm_pass" required></div>
  <button class="btn btn-primary btn-block" type="submit">Ð¡Ð¼ÐµÐ½Ð¸ Ð¿Ð°Ñ€Ð¾Ð»Ð°Ñ‚Ð°</button>
</form>
<?php elseif ($edit === 'legal'): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <input type="hidden" name="section" value="legal">
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð¿Ð¾Ð²ÐµÑ€Ð¸Ñ‚ÐµÐ»Ð½Ð¾ÑÑ‚</label><textarea name="politika_poveritelnost" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_poveritelnost','') ?></textarea></div>
  <div><label>Ð£ÑÐ»Ð¾Ð²Ð¸Ñ Ð·Ð° Ð¿Ð¾Ð»Ð·Ð²Ð°Ð½Ðµ</label><textarea name="usloviya_polzvane" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('usloviya_polzvane','') ?></textarea></div>
  <div><label>ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° Ð·Ð° Ð±Ð¸ÑÐºÐ²Ð¸Ñ‚ÐºÐ¸</label><textarea name="politika_biskvitki" rows="12" style="font-size:14px;line-height:1.6"><?= content_with_html('politika_biskvitki','') ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Ð—Ð°Ð¿Ð°Ð·Ð¸</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
