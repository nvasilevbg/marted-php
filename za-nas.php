<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings();
$GLOBALS['path']='/za-nas'; $title='За нас | '.$s['name']; $desc='MarTed — монтаж и демонтаж на мебели с фокус върху точност и чист завършек.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow anim">За нас</span>
    <h1 class="anim">Коректен монтаж <em>с ясен резултат</em></h1>
    <p class="anim">MarTed работи с фокус върху точност, чистота, здрав монтаж и нормална комуникация.</p>
  </div>
</section>
<section class="section aboutPage">
  <div class="container aboutGrid">
    <div class="aboutCopy">
      <span class="eyebrow eyebrow-line">Подход</span>
      <h2>Първо се уточнява задачата. След това се <em>работи</em>.</h2>
      <p>Всеки монтаж започва с проверка на обекта, размерите и особеностите на мебелите. Целта е да няма изненади, крив монтаж, липсващи елементи или недовършена работа.</p>
      <ul class="checkList">
        <li><?= icon('check') ?> Преглед на проекта и размерите</li>
        <li><?= icon('check') ?> Подходящи инструменти за конкретния монтаж</li>
        <li><?= icon('check') ?> Чисто изпълнение и прибран обект</li>
        <li><?= icon('check') ?> Гаранция за извършената услуга</li>
      </ul>
      <a href="/zapazi" class="btn btn-primary">Запази час</a>
    </div>
    <div class="aboutVisual"><img src="/assets/media/workshop-1.jpg" alt="Работа по монтаж на мебели"></div>
  </div>
</section>
<?php include __DIR__ . '/inc/booking-section.php'; ?>
<?php require __DIR__ . '/inc/footer.php'; ?>