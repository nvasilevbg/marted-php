<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings();
$slug = $_GET['slug'] ?? '';
$p = project_by_slug($slug);
if (!$p) { http_response_code(404); $title='Проектът не е намерен | '.$s['name']; require __DIR__ . '/inc/header.php'; echo '<section class="section"><div class="container"><h1>404</h1><p>Проектът не е намерен. <a href="/proekti">Виж всички проекти</a></p></div></section>'; require __DIR__ . '/inc/footer.php'; exit; }
$GLOBALS['path']='/proekti'; $title=$p['title'].' | '.$s['name']; $desc=$p['description'];
require __DIR__ . '/inc/header.php';
?>
<section class="section projectDetailPage">
  <div class="container">
    <nav class="breadcrumb" aria-label="Навигация">
      <a href="/">Начало</a><span class="sep">/</span><a href="/proekti">Проекти</a><span class="sep">/</span><span><?= e($p['title']) ?></span>
    </nav>
    <img class="projectCover" src="<?= e($p['cover']) ?>" srcset="<?= srcset($p['cover']) ?>" sizes="(max-width:760px) 100vw, 50vw" alt="<?= e($p['title']) ?>">
    <div class="projectIntro">
      <span class="eyebrow eyebrow-line">Проект</span>
      <h1 class="projectTitle"><?= e($p['title']) ?></h1>
      <ul class="projectFacts">
        <li><?= icon('box') ?><span>Категория:</span><b><?= e($p['category']) ?></b></li>
        <li><?= icon('calendar') ?><span>Дата:</span><b><?= e($p['pdate']) ?></b></li>
        <li><?= icon('pin') ?><span>Локация:</span><b><?= e($p['location']) ?></b></li>
      </ul>
      <p class="projectDesc"><?= e($p['description']) ?></p>
    </div>
    <span class="eyebrow eyebrow-line galleryLabel">Галерия</span>
    <div class="projectGallery">
      <?php foreach ($p['gallery'] as $i=>$media): $mext = strtolower(pathinfo($media, PATHINFO_EXTENSION)); $isVideo = in_array($mext, ['mp4','webm','mov']); ?>
      <?php if ($isVideo): ?>
      <video controls preload="metadata" class="projectVideo"><source src="<?= e($media) ?>" type="video/<?= $mext === 'mov' ? 'mp4' : $mext ?>"></video>
      <?php else: ?>
      <img src="<?= e($media) ?>" srcset="<?= srcset($media) ?>" sizes="(max-width:760px) 100vw, (max-width:1024px) 50vw, 33vw" alt="<?= e($p['title']) ?> — изглед <?= $i+1 ?>">
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="projectCta">
      <a href="/zapazi" class="btn btn-primary"><?= icon('calendar') ?> Запази подобен монтаж</a>
    </div>
  </div>
</section>
<?php include __DIR__ . '/inc/booking-section.php'; ?>
<?php require __DIR__ . '/inc/footer.php'; ?>