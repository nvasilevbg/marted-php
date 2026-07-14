<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings();
$GLOBALS['admin_page'] = 'home';

// Handle saves
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab = $_POST['tab'] ?? 'hero';
    if ($tab === 'hero') {
        foreach (['home_hero_title','home_hero_lead'] as $k) {
            if (isset($_POST[$k])) db()->prepare("UPDATE settings SET v=? WHERE k=?")->execute([trim($_POST[$k]), $k]);
        }
        header('Location: home.php?ok=1&tab=hero'); exit;
    }
    if ($tab === 'testimonials') {
        $action = $_POST['action'] ?? '';
        if ($action === 'save') {
            $id = $_POST['id'] ?? '';
            $name = trim($_POST['name'] ?? ''); $text = trim($_POST['text'] ?? '');
            $stars = (int)($_POST['stars'] ?? 5); $sort = (int)($_POST['sort_order'] ?? 0);
            if ($name && $text) {
                if ($id) db()->prepare("UPDATE testimonials SET tname=?,ttext=?,stars=?,sort_order=? WHERE id=?")->execute([$name,$text,$stars,$sort,$id]);
                else db()->prepare("INSERT INTO testimonials (tname,ttext,stars,sort_order) VALUES (?,?,?,?)")->execute([$name,$text,$stars,$sort]);
            }
        } elseif ($action === 'delete') { db()->prepare("DELETE FROM testimonials WHERE id=?")->execute([$_POST['id']]); }
        header('Location: home.php?ok=1&tab=testimonials'); exit;
    }
    if ($tab === 'stats') {
        $action = $_POST['action'] ?? '';
        if ($action === 'save') {
            $id = $_POST['id'] ?? '';
            $val = trim($_POST['value'] ?? ''); $lbl = trim($_POST['label'] ?? '');
            $sort = (int)($_POST['sort_order'] ?? 0);
            if ($val) {
                if ($id) db()->prepare("UPDATE stats SET svalue=?,slabel=?,sort_order=? WHERE id=?")->execute([$val,$lbl,$sort,$id]);
                else db()->prepare("INSERT INTO stats (svalue,slabel,sort_order) VALUES (?,?,?)")->execute([$val,$lbl,$sort]);
            }
        } elseif ($action === 'delete') { db()->prepare("DELETE FROM stats WHERE id=?")->execute([$_POST['id']]); }
        header('Location: home.php?ok=1&tab=stats'); exit;
    }
}

$activeTab = $_GET['tab'] ?? 'hero';
$editingT = null; $editingS = null;
if ($activeTab === 'testimonials' && isset($_GET['edit'])) {
    if ($_GET['edit'] === 'new') $editingT = ['id'=>'','tname'=>'','ttext'=>'','stars'=>5,'sort_order'=>0];
    else { $st = db()->prepare("SELECT * FROM testimonials WHERE id=?"); $st->execute([$_GET['edit']]); $editingT = $st->fetch(); }
}
if ($activeTab === 'stats' && isset($_GET['edit'])) {
    if ($_GET['edit'] === 'new') $editingS = ['id'=>'','svalue'=>'','slabel'=>'','sort_order'=>0];
    else { $st = db()->prepare("SELECT * FROM stats WHERE id=?"); $st->execute([$_GET['edit']]); $editingS = $st->fetch(); }
}
$testimonials = db()->query("SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC")->fetchAll();
$stats = db()->query("SELECT * FROM stats ORDER BY sort_order ASC, id ASC")->fetchAll();
$title = 'Начална | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">Страници</span><h1>Начална страница</h1></div></div>
<div class="adminTabs">
  <button type="button" class="<?= $activeTab==='hero'?'active':'' ?>" onclick="location.href='home.php?tab=hero'">Херобанер</button>
  <button type="button" class="<?= $activeTab==='testimonials'?'active':'' ?>" onclick="location.href='home.php?tab=testimonials'">Отзиви</button>
  <button type="button" class="<?= $activeTab==='stats'?'active':'' ?>" onclick="location.href='home.php?tab=stats'">Статистики</button>
</div>

<?php if ($activeTab === 'hero'): ?>
<form method="POST" class="adminForm">
  <input type="hidden" name="tab" value="hero">
  <div><label>Заглавие на херобанера</label><input name="home_hero_title" value="<?= e(content('home_hero_title')) ?>"></div>
  <div><label>Подзаглавие (текст)</label><textarea name="home_hero_lead" rows="3"><?= e(content('home_hero_lead')) ?></textarea></div>
  <button class="btn btn-primary btn-block" type="submit">Запази</button>
</form>

<?php elseif ($activeTab === 'testimonials'): ?>
<?php if ($editingT): ?>
<form method="POST" class="adminForm">
  <input type="hidden" name="tab" value="testimonials"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= e($editingT['id']) ?>">
  <div class="projFormHead"><span class="eyebrow eyebrow-line"><?= $editingT['id']?'Редакция':'Нов отзив' ?></span><a href="home.php?tab=testimonials" class="linkBtn">&larr; Назад</a></div>
  <div><label>Име *</label><input name="name" value="<?= e($editingT['tname']) ?>"></div>
  <div><label>Текст *</label><textarea name="text" rows="3"><?= e($editingT['ttext']) ?></textarea></div>
  <div class="formRow"><div><label>Звезди</label><select name="stars"><?php for($i=1;$i<=5;$i++): ?><option value="<?= $i ?>" <?= $editingT['stars']==$i?'selected':'' ?>><?= $i ?></option><?php endfor; ?></select></div><div><label>Ред</label><input name="sort_order" type="number" value="<?= e($editingT['sort_order']) ?>"></div></div>
  <button class="btn btn-primary btn-block" type="submit"><?= $editingT['id']?'Запази':'Добави' ?></button>
</form>
<?php else: ?>
<div style="margin-bottom:16px"><a href="home.php?tab=testimonials&edit=new" class="btn btn-primary">+ Добави отзив</a></div>
<div class="adminList">
  <?php foreach ($testimonials as $t): ?>
  <div class="adminRow"><div style="width:80px;height:60px;display:grid;place-items:center;background:var(--bg-3);border-radius:var(--radius);color:var(--accent-2);font-size:18px"><?= str_repeat('★',$t['stars']) ?></div><div class="adminRowInfo"><strong><?= e($t['tname']) ?></strong><span><?= e(mb_substr($t['ttext'],0,60)) ?>...</span></div><div class="adminRowActions"><a href="home.php?tab=testimonials&edit=<?= $t['id'] ?>">Редактирай</a><form method="POST" style="display:inline" onsubmit="return confirm('Изтриване?')"><input type="hidden" name="tab" value="testimonials"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $t['id'] ?>"><button class="del" type="submit">Изтрий</button></form></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php elseif ($activeTab === 'stats'): ?>
<?php if ($editingS): ?>
<form method="POST" class="adminForm">
  <input type="hidden" name="tab" value="stats"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= e($editingS['id']) ?>">
  <div class="projFormHead"><span class="eyebrow eyebrow-line"><?= $editingS['id']?'Редакция':'Нова статистика' ?></span><a href="home.php?tab=stats" class="linkBtn">&larr; Назад</a></div>
  <div class="formRow"><div><label>Стойност *</label><input name="value" value="<?= e($editingS['svalue']) ?>" placeholder="500+"></div><div><label>Етикет</label><input name="label" value="<?= e($editingS['slabel']) ?>" placeholder="Доволни клиенти"></div></div>
  <div><label>Ред</label><input name="sort_order" type="number" value="<?= e($editingS['sort_order']) ?>"></div>
  <button class="btn btn-primary btn-block" type="submit"><?= $editingS['id']?'Запази':'Добави' ?></button>
</form>
<?php else: ?>
<div style="margin-bottom:16px"><a href="home.php?tab=stats&edit=new" class="btn btn-primary">+ Добави статистика</a></div>
<div class="adminList">
  <?php foreach ($stats as $st): ?>
  <div class="adminRow"><div style="width:80px;height:60px;display:grid;place-items:center;background:var(--bg-3);border-radius:var(--radius);font-family:var(--font-serif);font-size:18px;color:var(--accent-2)"><?= e($st['svalue']) ?></div><div class="adminRowInfo"><strong><?= e($st['slabel']) ?></strong></div><div class="adminRowActions"><a href="home.php?tab=stats&edit=<?= $st['id'] ?>">Редактирай</a><form method="POST" style="display:inline" onsubmit="return confirm('Изтриване?')"><input type="hidden" name="tab" value="stats"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $st['id'] ?>"><button class="del" type="submit">Изтрий</button></form></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>