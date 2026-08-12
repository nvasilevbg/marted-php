<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
$s = settings();
$GLOBALS['admin_page'] = 'projects';
$editing = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $cover = $_POST['cover_existing'] ?? '';
    $gallery_urls = array_filter(array_map('trim', explode("\n", $_POST['gallery'] ?? '')));
    $uploaded = upload_image('cover_file', __DIR__ . '/../assets/media/projects');
    if ($uploaded) $cover = $uploaded;
    if (!empty($_FILES['gallery_files']['name'][0])) {
        $dir = __DIR__ . '/../assets/media/projects';
        foreach ($_FILES['gallery_files']['tmp_name'] as $i => $tmp) {
            if ($_FILES['gallery_files']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['gallery_files']['name'][$i], PATHINFO_EXTENSION));
                $tmpName = 'p_' . time() . '_' . $i . '.' . $ext;
                $tmpPath = $dir . '/' . $tmpName;
                if (move_uploaded_file($tmp, $tmpPath)) {
                    $webpName = 'p_' . time() . '_' . rand(100,999) . '.webp';
                    $webpPath = $dir . '/' . $webpName;
                    $videoExts = ['mp4','webm','mov'];
                    if (in_array($ext, $videoExts)) {
                        $gallery_urls[] = '/assets/media/projects/' . $tmpName;
                    } elseif (convert_to_webp($tmpPath, $webpPath, 85)) {
                        @unlink($tmpPath);
                        $gallery_urls[] = '/assets/media/projects/' . $webpName;
                    } else {
                        $gallery_urls[] = '/assets/media/projects/' . $tmpName;
                    }
                }
            }
        }
    }
    $gallery_json = json_encode(array_values($gallery_urls));
    if ($title && $cover) {
        $data = ['title'=>$title,'category'=>trim($_POST['category']??'Ð”Ñ€ÑƒÐ³Ð¸'),'pdate'=>trim($_POST['pdate']??''),'location'=>trim($_POST['location']??''),'description'=>trim($_POST['description']??''),'cover'=>$cover,'gallery'=>$gallery_json];
        if ($id) { db()->prepare("UPDATE projects SET title=?,category=?,pdate=?,location=?,description=?,cover=?,gallery=? WHERE id=?")->execute([$data['title'],$data['category'],$data['pdate'],$data['location'],$data['description'],$data['cover'],$data['gallery'],$id]); }
        else { $slug = slugify($title); db()->prepare("INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES (?,?,?,?,?,?,?,?)")->execute([$slug,$data['title'],$data['category'],$data['pdate'],$data['location'],$data['description'],$data['cover'],$data['gallery']]); }
        header('Location: projects.php?ok=1'); exit;
    }
    $formErr = 'Ð—Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ Ð¸ Ð³Ð»Ð°Ð²Ð½Ð° ÑÐ½Ð¸Ð¼ÐºÐ° ÑÐ° Ð·Ð°Ð´ÑŠÐ»Ð¶Ð¸Ñ‚ÐµÐ»Ð½Ð¸.';
}
if (isset($_GET['delete'])) { db()->prepare("DELETE FROM projects WHERE id=?")->execute([$_GET['delete']]); header('Location: projects.php'); exit; }
if (isset($_GET['edit'])) {
    if ($_GET['edit'] === 'new') $editing = ['id'=>'','slug'=>'','title'=>'','category'=>'ÐšÑƒÑ…Ð½Ð¸','pdate'=>'','location'=>'Ð”Ð¾Ð±Ñ€Ð¸Ñ‡','description'=>'','cover'=>'','gallery'=>[]];
    else { $st = db()->prepare("SELECT * FROM projects WHERE id=?"); $st->execute([$_GET['edit']]); $editing = $st->fetch(); if ($editing) $editing['gallery'] = json_decode($editing['gallery']?:'[]', true) ?: []; }
}
$projs = db()->query("SELECT * FROM projects ORDER BY id ASC")->fetchAll();
$title = 'ÐŸÑ€Ð¾ÐµÐºÑ‚Ð¸ | ÐÐ´Ð¼Ð¸Ð½ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<?php if (isset($_GET['ok'])): ?><p class="formMsg ok" style="margin-bottom:16px">Ð—Ð°Ð¿Ð°Ð·ÐµÐ½Ð¾.</p><?php endif; ?>
<?php if ($editing): ?>
<form method="POST" enctype="multipart/form-data" class="adminForm" style="max-width:none">
  <?= csrf_field() ?>
  <div class="projFormHead"><span class="eyebrow eyebrow-line"><?= $editing['id']?'Ð ÐµÐ´Ð°ÐºÑ†Ð¸Ñ':'ÐÐ¾Ð² Ð¿Ñ€Ð¾ÐµÐºÑ‚' ?></span><a href="projects.php" class="linkBtn">&larr; ÐÐ°Ð·Ð°Ð´</a></div>
  <div class="adminTabs">
    <button type="button" class="active" onclick="switchTab(event,'tab-main')">ÐžÑÐ½Ð¾Ð²Ð½Ð¾</button>
    <button type="button" onclick="switchTab(event,'tab-images')">Ð¡Ð½Ð¸Ð¼ÐºÐ¸</button>
  </div>
  <input type="hidden" name="id" value="<?= e($editing['id']) ?>">
  <div id="tab-main" class="tabContent active">
    <div><label>Ð—Ð°Ð³Ð»Ð°Ð²Ð¸Ðµ *</label><input name="title" value="<?= e($editing['title']) ?>" placeholder="ÐœÐ¾Ð´ÐµÑ€Ð½Ð° ÐºÑƒÑ…Ð½Ñ"></div>
    <div class="formRow"><div><label>ÐšÐ°Ñ‚ÐµÐ³Ð¾Ñ€Ð¸Ñ</label><select name="category"><?php
    $cats = ['ÐšÑƒÑ…Ð½Ð¸','Ð¡Ð¿Ð°Ð»Ð½Ð¸','Ð“Ð°Ñ€Ð´ÐµÑ€Ð¾Ð±Ð¸','Ð”Ñ€ÑƒÐ³Ð¸'];
    if (!in_array($editing['category'], $cats) && $editing['category']) $cats[] = $editing['category'];
    foreach ($cats as $cat): ?><option value="<?= e($cat) ?>" <?= $editing['category']===$cat?'selected':'' ?>><?= e($cat) ?></option><?php endforeach; ?></select>></div><div><label>Ð”Ð°Ñ‚Ð°</label><input name="pdate" value="<?= e($editing['pdate']) ?>" placeholder="Ð®Ð»Ð¸ 2024"></div></div>
    <div class="formRow"><div><label>Ð›Ð¾ÐºÐ°Ñ†Ð¸Ñ</label><input name="location" value="<?= e($editing['location']) ?>"></div><div><label>Slug</label><input value="<?= e($editing['slug']) ?>" disabled></div></div>
    <div><label>ÐžÐ¿Ð¸ÑÐ°Ð½Ð¸Ðµ</label><textarea name="description" rows="3" placeholder="ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼Ð¾Ð´ÐµÑ€Ð½Ð° ÐºÑƒÑ…Ð½Ñ..."><?= e($editing['description']) ?></textarea></div>
  </div>
  <div id="tab-images" class="tabContent">
    <div><label>Ð“Ð»Ð°Ð²Ð½Ð° ÑÐ½Ð¸Ð¼ÐºÐ° (cover) *</label>
      <?php if (!empty($editing['cover'])): ?><img src="<?= e($editing['cover']) ?>" style="max-height:80px;border-radius:4px;margin-bottom:8px"><?php endif; ?>
      <input type="hidden" name="cover_existing" value="<?= e($editing['cover']) ?>">
      <input type="file" name="cover_file" accept="image/*">
    </div>
    <div><label>Ð“Ð°Ð»ÐµÑ€Ð¸Ñ (URL Ð½Ð° Ñ€ÐµÐ´ Ð¸Ð»Ð¸ ÐºÐ°Ñ‡Ð¸ Ñ„Ð°Ð¹Ð»Ð¾Ð²Ðµ)</label>
      <textarea name="gallery" rows="4" placeholder="/assets/media/kitchen-1.webp Ð¸Ð»Ð¸ /assets/media/video.mp4"><?= e(implode("\n", $editing['gallery'])) ?></textarea>
      <input type="file" name="gallery_files[]" multiple accept="image/*,video/*" style="margin-top:8px">
    </div>
  </div>
  <button class="btn btn-primary btn-block" type="submit"><?= $editing['id']?'Ð—Ð°Ð¿Ð°Ð·Ð¸ Ð¿Ñ€Ð¾Ð¼ÐµÐ½Ð¸Ñ‚Ðµ':'Ð”Ð¾Ð±Ð°Ð²Ð¸ Ð¿Ñ€Ð¾ÐµÐºÑ‚' ?></button>
  <?php if (!empty($formErr)): ?><p class="formMsg err"><?= e($formErr) ?></p><?php endif; ?>
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
<?php else: ?>
<div style="margin-top:20px"><a href="projects.php?edit=new" class="btn btn-primary">+ Ð”Ð¾Ð±Ð°Ð²Ð¸ Ð¿Ñ€Ð¾ÐµÐºÑ‚</a></div>
<div class="adminList">
  <?php foreach ($projs as $p): ?>
  <div class="adminRow"><img src="<?= e($p['cover']) ?>" alt="<?= e($p['title']) ?>"><div class="adminRowInfo"><strong><?= e($p['title']) ?></strong><span><?= e($p['category']) ?> Â· <?= e($p['location']) ?> Â· <?= e($p['pdate']) ?></span><code><?= e($p['slug']) ?></code></div><div class="adminRowActions"><a href="projects.php?edit=<?= $p['id'] ?>">Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð°Ð¹</a><a href="projects.php?delete=<?= $p['id'] ?>" onclick="return confirm('Ð˜Ð·Ñ‚Ñ€Ð¸Ð²Ð°Ð½Ðµ?')" class="del">Ð˜Ð·Ñ‚Ñ€Ð¸Ð¹</a></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
