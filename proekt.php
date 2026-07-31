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
    <img class="projectCover" src="<?= e($p['cover']) ?>" alt="<?= e($p['title']) ?>">
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
      <img src="<?= e($media) ?>" alt="<?= e($p['title']) ?> — изглед <?= $i+1 ?>">
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="projectCta">
      <a href="/zapazi" class="btn btn-primary"><?= icon('calendar') ?> Запази подобен монтаж</a>
    </div>
  </div>
</section>
<!-- Lightbox -->
<div class="lightbox" id="lightbox">
  <span class="lightboxClose" id="lbClose">&times;</span>
  <button class="lightboxNav lightboxPrev" id="lbPrev">&lsaquo;</button>
  <button class="lightboxNav lightboxNext" id="lbNext">&rsaquo;</button>
  <div class="lightboxMedia" id="lbMedia"></div>
  <span class="lightboxCounter" id="lbCounter"></span>
</div>
<script>
(function(){
  var items = document.querySelectorAll('.projectGallery img, .projectGallery video');
  if (!items.length) return;
  var cur = 0;
  var lb = document.getElementById('lightbox');
  var lbMedia = document.getElementById('lbMedia');
  var lbCounter = document.getElementById('lbCounter');

  items.forEach(function(item, i){
    item.addEventListener('click', function(e){
      if (item.tagName === 'VIDEO') { e.preventDefault(); e.stopPropagation(); }
      cur = i; show();
    });
  });

  function show(){
    var item = items[cur];
    if (item.tagName === 'VIDEO') {
      var src = item.querySelector('source') ? item.querySelector('source').src : item.src;
      lbMedia.innerHTML = '<video controls autoplay src="'+src+'" style="max-width:90vw;max-height:85vh;border-radius:8px"></video>';
    } else {
      lbMedia.innerHTML = '<img src="'+item.src+'" style="max-width:90vw;max-height:85vh;border-radius:8px;object-fit:contain">';
    }
    lbCounter.textContent = (cur+1)+' / '+items.length;
    lb.style.display = 'flex';
  }

  function close(){ lb.style.display='none'; lbMedia.innerHTML=''; }
  function prev(){ cur=(cur-1+items.length)%items.length; show(); }
  function next(){ cur=(cur+1)%items.length; show(); }

  document.getElementById('lbClose').onclick = close;
  document.getElementById('lbPrev').onclick = function(e){ e.stopPropagation(); prev(); };
  document.getElementById('lbNext').onclick = function(e){ e.stopPropagation(); next(); };
  lb.addEventListener('click', function(e){ if(e.target===lb) close(); });
  document.addEventListener('keydown', function(e){
    if(lb.style.display==='flex'){
      if(e.key==='Escape') close();
      if(e.key==='ArrowLeft') prev();
      if(e.key==='ArrowRight') next();
    }
  });
})();
</script>
<?php include __DIR__ . '/inc/booking-section.php'; ?>
<?php require __DIR__ . '/inc/footer.php'; ?>