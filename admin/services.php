<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
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
    $formErr = 'Заглавието е задължително.';
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
$title = 'Услуги | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<?php if ($editing): ?>
<div class="adminTabs"><button class="active" onclick="void(0)">Основно</button></div>
<form method="POST" enctype="multipart/form-data" class="adminForm">
  <div class="projFormHead"><span class="eyebrow eyebrow-line"><?= $editing['id']?'Редакция':'Нова услуга' ?></span><a href="services.php" class="linkBtn">&larr; Назад</a></div>
  <input type="hidden" name="id" value="<?= e($editing['id']) ?>">
  <div><label>Заглавие *</label><input name="title" value="<?= e($editing['title']) ?>" placeholder="Монтаж на мебели"></div>
  <div><label>Описание</label><textarea name="stext" rows="3" placeholder="Монтаж на кухни..."><?= e($editing['stext']) ?></textarea></div>
  <div class="formRow">
    <div><label>Иконка</label><select name="icon"><?php foreach($iconOptions as $ic): ?><option value="<?= $ic ?>" <?= $editing['icon']===$ic?'selected':'' ?>><?= $ic ?></option><?php endforeach; ?></select></div>
    <div><label>Ред</label><input name="sort_order" type="number" value="<?= e($editing['sort_order']) ?>"></div>
  </div>
  <div><label>Снимка (по желание)</label>
    <?php if (!empty($editing['image'])): ?><img src="<?= e($editing['image']) ?>" style="max-height:80px;border-radius:4px;margin-bottom:8px"><?php endif; ?>
    <input type="hidden" name="image_existing" value="<?= e($editing['image']) ?>">
    <input type="file" name="image_file" accept="image/*">
  </div>
  <button class="btn btn-primary btn-block" type="submit"><?= $editing['id']?'Запази':'Добави' ?></button>
  <?php if (!empty($formErr)): ?><p class="formMsg err"><?= e($formErr) ?></p><?php endif; ?>
</form>
<?php else: ?>
<div style="margin-top:20px"><a href="services.php?edit=new" class="btn btn-primary">+ Добави услуга</a></div>
<div class="adminList">
  <?php foreach ($svcList as $svc): ?>
  <div class="adminRow">
    <?php if ($svc['image']): ?><img src="<?= e($svc['image']) ?>" alt=""><?php else: ?><div style="width:80px;height:60px;display:grid;place-items:center;background:var(--bg-3);border-radius:var(--radius)"><?= icon($svc['icon']) ?></div><?php endif; ?>
    <div class="adminRowInfo"><strong><?= e($svc['title']) ?></strong><span><?= e($svc['icon']) ?> · ред <?= e($svc['sort_order']) ?></span></div>
    <div class="adminRowActions"><a href="services.php?edit=<?= $svc['id'] ?>">Редактирай</a><a href="services.php?delete=<?= $svc['id'] ?>" onclick="return confirm('Изтриване?')" class="del">Изтрий</a></div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>