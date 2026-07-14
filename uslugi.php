<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings(); $svcs = services();
$GLOBALS['path']='/uslugi'; $title='Услуги | '.$s['name']; $desc='Монтаж, демонтаж, разнос, изнасяне и консултация за мебели.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow anim">Нашите услуги</span>
    <h1 class="anim">Монтаж и демонтаж <em>на мебели</em></h1>
    <p class="anim">Работа по кухни, спални, гардероби, секции, офис мебели и индивидуални проекти.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="sectionHead reveal">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">Услуги</span><h2>Монтаж без <em>импровизации</em></h2></div>
      <p>Ясен процес, правилни инструменти, чист обект и коректно изпълнение.</p>
    </div>
    <div class="serviceList">
      <?php foreach ($svcs as $svc): ?>
      <div class="serviceRow reveal" style="--d:0ms">
        <div class="svcTitle"><?= icon($svc['icon']) ?><h3><?= e($svc['title']) ?></h3></div>
        <p class="svcText"><?= e($svc['text']) ?></p>
        <a href="/zapazi" class="textLink">Запази час <?= icon('arrow') ?></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/inc/booking-section.php'; ?>
<?php require __DIR__ . '/inc/footer.php'; ?>