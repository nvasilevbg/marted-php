<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings(); $projs = projects(); $filters = filters();
$GLOBALS['path']='/proekti'; $title='Проекти | '.$s['name']; $desc='Реализирани проекти за монтаж на кухни, спални, гардероби и други мебели.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow anim">Проекти</span>
    <h1 class="anim">Нашите <em>проекти</em></h1>
    <p class="anim">Галерия с примерни реализации и информация за всеки обект. Филтрирайте по категория.</p>
  </div>
</section>
<section class="section projectsPage">
  <div class="container">
    <div class="tabs" aria-label="Филтри">
      <?php foreach ($filters as $i=>$f): ?>
      <button class="<?= $i===0?'active':'' ?>" data-filter="<?= e($f) ?>" type="button"><?= e($f) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="projectMosaic reveal">
      <?php $mcls=['pc-feat','pc-sq','pc-tall','pc-wide','pc-sq','pc-wide']; foreach ($projs as $i=>$p): ?>
      <a class="projectCard <?= $mcls[$i%6] ?>" data-cat="<?= e($p['category']) ?>" href="/proekti/<?= e($p['slug']) ?>">
        <img src="<?= e($p['cover']) ? loading="lazy">" alt="<?= e($p['title']) ?>">
        <span class="arrowChip"><?= icon('arrow') ?></span>
        <span class="cap"><span class="cat"><?= e($p['category']) ?> · <?= e($p['location']) ?></span><h3><?= e($p['title']) ?></h3><p class="desc"><?= e($p['description']) ?></p></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/inc/booking-section.php'; ?>
<?php require __DIR__ . '/inc/footer.php'; ?>