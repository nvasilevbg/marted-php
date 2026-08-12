<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
$s = settings();
$GLOBALS['admin_page'] = 'testimonials';
$editing = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = trim($_POST['name'] ?? ''); $text = trim($_POST['text'] ?? '');
    $stars = (int)($_POST['stars'] ?? 5); $sort = (int)($_POST['sort_order'] ?? 0);
    if ($name && $text) {
        if ($id) db()->prepare("UPDATE testimonials SET tname=?,ttext=?,stars=?,sort_order=? WHERE id=?")->execute([$name,$text,$stars,$sort,$id]);
        else db()->prepare("INSERT INTO testimonials (tname,ttext,stars,sort_order) VALUES (?,?,?,?)")->execute([$name,$text,$stars,$sort]);
    }
    header('Location: testimonials.php?ok=1'); exit;
}
if (isset($_GET['delete'])) { db()->prepare("DELETE FROM testimonials WHERE id=?")->execute([$_GET['delete']]); header('Location: testimonials.php'); exit; }
if (isset($_GET['edit'])) {
    if ($_GET['edit'] === 'new') $editing = ['id'=>'','tname'=>'','ttext'=>'','stars'=>5,'sort_order'=>0];
    else { $st = db()->prepare("SELECT * FROM testimonials WHERE id=?"); $st->execute([$_GET['edit']]); $editing = $st->fetch(); }
}
$list = db()->query("SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC")->fetchAll();
$title = 'Отзиви | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Съдържание</span><h1>Отзиви</h1></div></div>
<?php if ($editing): ?>
<form method="POST" class="adminForm">
  <?= csrf_field() ?>
  <div class="projFormHead"><span class="eyebrow eyebrow-line"><?= $editing['id']?'Редакция':'Нов отзив' ?></span><a href="testimonials.php" class="linkBtn">&larr; Назад</a></div>
  <input type="hidden" name="id" value="<?= e($editing['id']) ?>">
  <div><label>Име *</label><input name="name" value="<?= e($editing['tname']) ?>"></div>
  <div><label>Текст *</label><textarea name="text" rows="3"><?= e($editing['ttext']) ?></textarea></div>
  <div class="formRow"><div><label>Звезди</label><select name="stars"><?php for($i=1;$i<=5;$i++): ?><option value="<?= $i ?>" <?= $editing['stars']==$i?'selected':'' ?>><?= $i ?></option><?php endfor; ?></select></div><div><label>Ред</label><input name="sort_order" type="number" value="<?= e($editing['sort_order']) ?>"></div></div>
  <button class="btn btn-primary btn-block" type="submit"><?= $editing['id']?'Запази':'Добави' ?></button>
</form>
<?php else: ?>
<div style="margin-bottom:16px"><a href="testimonials.php?edit=new" class="btn btn-primary">+ Добави отзив</a></div>
<div class="adminList">
  <?php foreach ($list as $t): ?>
  <div class="adminRow"><div style="width:80px;height:60px;display:grid;place-items:center;background:var(--bg-3);border-radius:var(--radius);color:var(--accent-2);font-size:18px"><?= str_repeat('â˜…',$t['stars']) ?></div><div class="adminRowInfo"><strong><?= e($t['tname']) ?></strong><span><?= e(mb_substr($t['ttext'],0,60)) ?>...</span></div><div class="adminRowActions"><a href="testimonials.php?edit=<?= $t['id'] ?>">Редактирай</a><a href="testimonials.php?delete=<?= $t['id'] ?>" onclick="return confirm('Изтриване?')" class="del">Изтрий</a></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
