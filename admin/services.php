<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
$s = settings();
$GLOBALS['admin_page'] = 'services';
$editing = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $stext = trim($_POST['stext'] ?? '');
    $icon = trim($_POST['icon'] ?? 'shield');
    $image = $_POST['image_existing'] ?? '';
    $uploaded = upload_image('image_file', __DIR__ . '/../assets/media/projects');
    if ($uploaded) $image = $uploaded;
    $sort = (int)($_POST['sort_order'] ?? 0);
    if ($title) {
        if ($id) {
            db()->prepare("UPDATE services SET title=?,stext=?,icon=?,image=?,sort_order=? WHERE id=?")->execute([$title,$stext,$icon,$image,$sort,$id]);
        } else {
            db()->prepare("INSERT INTO services (title,stext,icon,image,sort_order) VALUES (?,?,?,?,?)")->execute([$title,$stext,$icon,$image,$sort]);
        }
        header('Location: services.php?ok=1'); exit;
    }
    $formErr = 'Ð—Ð°Ð³Ð»Ð°Ð²Ð¸ÐµÑ‚Ð¾ Ðµ Ð·Ð°Ð´ÑŠÐ»Ð¶Ð¸Ñ‚ÐµÐ»Ð½Ð¾.';
}
if (isset($_GET['delete'])) {
    db()->prepare("DELETE FROM services WHERE id=?")->execute([$_GET['delete']]);
    header('Location: services.php'); exit;
}
if (isset($_GET['edit'])) {
    if ($_GET['edit'] === 'new') $editing = ['id'=>'','title'=>'','stext'=>'','icon'=>'shield','image'=>'','sort_order'=>0];
    else { $st = db()->prepare("SELECT * FROM services WHERE id=?"); $st->execute([$_GET['edit']]); $editing = $st->fetch(); }
}
$svcList = db()->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC")->fetchAll();
$iconOptions = ['drill','demolition','truck','box','measure','shield','pin','clock','phone','calendar','check','arrow','users','mail'];
$title = 'Ð£ÑÐ»ÑƒÐ³Ð¸ | ÐÐ´Ð¼Ð¸Ð½ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Ð—Ð°Ð¿Ð°Ð·ÐµÐ½Ð¾.</p><?php endif; ?>
<?php if ($editing): ?>
<div class="adminTabs"><button class="active" onclick="void(0)">ÐžÑÐ½Ð¾Ð²Ð½Ð¾</button></div>
<form method="POST" enctype="multipart/form-data" class="adminForm">
  <?= csrf_field() ?>
  <div class="projFormHead"><span class="eyebrow eyebrow-line"><?= $editing['id']?'Ð ÐµÐ´Ð°ÐºÑ†Ð¸Ñ':'ÐÐ¾Ð²Ð° ÑƒÑÐ»ÑƒÐ³Ð°' ?></span><a href="services.php" class="linkBtn">&larr; ÐÐ°Ð·Ð°Ð´</a></div>
  <input type="hidden" name="id" value="<?= e($editing['id']) ?>">
  <div><label>Ð—Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ *</label><input name="title" value="<?= e($editing['title']) ?>" placeholder="ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸"></div>
  <div><label>ÐžÐ¿Ð¸ÑÐ°Ð½Ð¸Ðµ</label><textarea name="stext" rows="3" placeholder="ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° ÐºÑƒÑ…Ð½Ð¸..."><?= e($editing['stext']) ?></textarea></div>
  <div class="formRow">
    <div><label>Ð˜ÐºÐ¾Ð½ÐºÐ°</label><select name="icon"><?php foreach($iconOptions as $ic): ?><option value="<?= $ic ?>" <?= $editing['icon']===$ic?'selected':'' ?>><?= $ic ?></option><?php endforeach; ?></select></div>
    <div><label>Ð ÐµÐ´</label><input name="sort_order" type="number" value="<?= e($editing['sort_order']) ?>"></div>
  </div>
  <div><label>Ð¡Ð½Ð¸Ð¼ÐºÐ° (Ð¿Ð¾ Ð¶ÐµÐ»Ð°Ð½Ð¸Ðµ)</label>
    <?php if (!empty($editing['image'])): ?><img src="<?= e($editing['image']) ?>" style="max-height:80px;border-radius:4px;margin-bottom:8px"><?php endif; ?>
    <input type="hidden" name="image_existing" value="<?= e($editing['image']) ?>">
    <input type="file" name="image_file" accept="image/*">
  </div>
  <button class="btn btn-primary btn-block" type="submit"><?= $editing['id']?'Ð—Ð°Ð¿Ð°Ð·Ð¸':'Ð”Ð¾Ð±Ð°Ð²Ð¸' ?></button>
  <?php if (!empty($formErr)): ?><p class="formMsg err"><?= e($formErr) ?></p><?php endif; ?>
</form>
<?php else: ?>
<div style="margin-top:20px"><a href="services.php?edit=new" class="btn btn-primary">+ Ð”Ð¾Ð±Ð°Ð²Ð¸ ÑƒÑÐ»ÑƒÐ³Ð°</a></div>
<div class="adminList">
  <?php foreach ($svcList as $svc): ?>
  <div class="adminRow">
    <?php if ($svc['image']): ?><img src="<?= e($svc['image']) ?>" alt=""><?php else: ?><div style="width:80px;height:60px;display:grid;place-items:center;background:var(--bg-3);border-radius:var(--radius)"><?= icon($svc['icon']) ?></div><?php endif; ?>
    <div class="adminRowInfo"><strong><?= e($svc['title']) ?></strong><span><?= e($svc['icon']) ?> Â· Ñ€ÐµÐ´ <?= e($svc['sort_order']) ?></span></div>
    <div class="adminRowActions"><a href="services.php?edit=<?= $svc['id'] ?>">Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð°Ð¹</a><a href="services.php?delete=<?= $svc['id'] ?>" onclick="return confirm('Ð˜Ð·Ñ‚Ñ€Ð¸Ð²Ð°Ð½Ðµ?')" class="del">Ð˜Ð·Ñ‚Ñ€Ð¸Ð¹</a></div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
