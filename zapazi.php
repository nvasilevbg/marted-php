<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings(); $GLOBALS['path']='/zapazi'; $GLOBALS['hide_mobile_bar']=true;
$title='Запази час | '.$s['name']; $desc='Запазете час за монтаж, демонтаж или консултация с MarTed.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow anim">Резервация</span>
    <h1 class="anim">Запазете <em>час</em></h1>
    <p class="anim">Изберете удобна дата и час за монтаж, демонтаж или консултация. Потвърждаваме по телефон.</p>
  </div>
</section>
<section class="section bookingSection">
  <div class="container">
    <div id="booking"></div>
  </div>
</section>
<script>window.SITE_HOURS=<?= json_encode($s['hours']) ?>;window.SITE_PHONE=<?= json_encode($s['phone']) ?>;window.SERVICES=<?= json_encode(array_column(services(),'title')) ?>;</script>
<script src="/assets/js/booking.js?v=3"></script>
<?php require __DIR__ . '/inc/footer.php'; ?>
