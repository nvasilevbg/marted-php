<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
$s = settings(); $projs = projects();
$editing = null;

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $cover = $_POST['cover_existing'] ?? '';
    $gallery_urls = array_filter(array_map('trim', explode("\n", $_POST['gallery'] ?? '')));
    
    // Handle cover upload
    $uploaded = upload_image('cover_file', __DIR__ . '/../assets/media/projects');
    if ($uploaded) $cover = $uploaded;
    
    // Handle gallery uploads
    if (!empty($_FILES['gallery_files']['name'][0])) {
        $dir = __DIR__ . '/../assets/media/projects';
        foreach ($_FILES['gallery_files']['tmp_name'] as $i => $tmp) {
            if ($_FILES['gallery_files']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['gallery_files']['name'][$i], PATHINFO_EXTENSION));
                $name = 'p_' . time() . '_' . $i . '.' . $ext;
                if (move_uploaded_file($tmp, $dir . '/' . $name)) $gallery_urls[] = '/assets/media/projects/' . $name;
            }
        }
    }
    $gallery_json = json_encode(array_values($gallery_urls));
    
    if ($title && $cover) {
        $data = [
            'title'=>$title, 'category'=>trim($_POST['category']??'Други'), 'pdate'=>trim($_POST['pdate']??''),
            'location'=>trim($_POST['location']??''), 'description'=>trim($_POST['description']??''),
            'cover'=>$cover, 'gallery'=>$gallery_json,
        ];
        if ($id) {
            $st = db()->prepare("UPDATE projects SET title=?,category=?,pdate=?,location=?,description=?,cover=?,gallery=? WHERE id=?");
            $st->execute([$data['title'],$data['category'],$data['pdate'],$data['location'],$data['description'],$data['cover'],$data['gallery'],$id]);
        } else {
            $slug = slugify($title);
            $st = db()->prepare("INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES (?,?,?,?,?,?,?,?)");
            $st->execute([$slug,$data['title'],$data['category'],$data['pdate'],$data['location'],$data['description'],$data['cover'],$data['gallery']]);
        }
        header('Location: projects.php?ok=1'); exit;
    }
    $formErr = 'Заглавие и главна снимка са задължителни.';
}

// Handle delete
if (isset($_GET['delete'])) {
    $st = db()->prepare("DELETE FROM projects WHERE id=?");
    $st->execute([$_GET['delete']]);
    header('Location: projects.php'); exit;
}

// Handle edit (load)
if (isset($_GET['edit'])) {
    $st = db()->prepare("SELECT * FROM projects WHERE id=?");
    $st->execute([$_GET['edit']]);
    $editing = $st->fetch();
    if ($editing) $editing['gallery'] = json_decode($editing['gallery']?:'[]', true) ?: [];
}

$title = 'Проекти | Админ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
require __DIR__ . '/../inc/admin-styles.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Запазено.</p><?php endif; ?>

<?php if ($editing): ?>
<div class="projForm" style="margin-top:20px">
  <div class="projFormHead"><span class="eyebrow eyebrow-line"><?= $editing['id']?'Редакция':'Нов проект' ?></span><a href="projects.php" class="projRowActions a">← Назад</a></div>
  <form method="POST" enctype="multipart/form-data" style="display:grid;gap:18px">
    <input type="hidden" name="id" value="<?= e($editing['id']??'') ?>">
    <div><label>Заглавие *</label><input name="title" value="<?= e($editing['title']??'') ?>" placeholder="Модерна кухня"></div>
    <div class="formRow"><div><label>Категория</label><input name="category" value="<?= e($editing['category']??'Кухни') ?>"></div><div><label>Дата</label><input name="pdate" value="<?= e($editing['pdate']??'') ?>" placeholder="Юли 2024"></div></div>
    <div class="formRow"><div><label>Локация</label><input name="location" value="<?= e($editing['location']??'Добрич') ?>"></div><div><label>Slug (URL)</label><input name="slug" value="<?= e($editing['slug']??'') ?>" <?= $editing?'disabled':'' ?>></div></div>
    <div><label>Описание</label><textarea name="description" rows="3" placeholder="Монтаж на модерна кухня…"><?= e($editing['description']??'') ?></textarea></div>
    <div><label>Главна снимка (cover) *</label>
      <?php if (!empty($editing['cover'])): ?><img src="<?= e($editing['cover']) ?>" style="max-height:80px;border-radius:4px;margin-bottom:8px"><?php endif; ?>
      <input type="hidden" name="cover_existing" value="<?= e($editing['cover']??'') ?>">
      <input type="file" name="cover_file" accept="image/*">
      <div class="uploadHint">или въведи URL: <input name="cover_url" value="" placeholder="/assets/media/kitchen-1.jpg" style="display:block;margin-top:4px"></div>
    </div>
    <div><label>Галерия (по една снимка на ред — URL или качи файлове)</label>
      <textarea name="gallery" rows="4" placeholder="/media/kitchen-1.jpg&#10;/media/kitchen-2.jpg"><?= e(implode("\n", $editing['gallery']??[])) ?></textarea>
      <input type="file" name="gallery_files[]" multiple accept="image/*" style="margin-top:8px">
    </div>
    <button class="btn btn-primary btn-block" type="submit"><?= $editing['id']?'Запази промените':'Добави проект' ?></button>
    <?php if (!empty($formErr)): ?><p class="formMsg err"><?= e($formErr) ?></p><?php endif; ?>
  </form>
</div>
<?php else: ?>
<div style="margin-top:20px"><a href="projects.php?edit=new" class="btn btn-primary">+ Добави проект</a></div>
<div class="projTable">
  <?php foreach ($projs as $p): ?>
  <div class="projRow">
    <img src="<?= e($p['cover']) ?>" alt="<?= e($p['title']) ?>">
    <div class="projRowInfo"><strong><?= e($p['title']) ?></strong><span><?= e($p['category']) ?> · <?= e($p['location']) ?> · <?= e($p['pdate']) ?></span><code><?= e($p['slug']) ?></code></div>
    <div class="projRowActions"><a href="projects.php?edit=<?= $p['id'] ?>">Редактирай</a><a href="projects.php?delete=<?= $p['id'] ?>" onclick="return confirm('Изтриване?')">Изтрий</a></div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>