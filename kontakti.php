<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings();
$GLOBALS['path']='/kontakti'; $title='Контакти | '.$s['name']; $desc='Свържете се с MarTed — телефон, имейл, адрес и карта. Работим в Добрич, Балчик, Варна и околността.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow anim">Контакти</span>
    <h1 class="anim">Свържете се <em>с нас</em></h1>
    <p class="anim">Телефон, имейл и адрес. Работим в Добрич, Балчик, Варна и околността — или запазете час директно от календара.</p>
  </div>
</section>
<section class="section contactPage">
  <div class="container contactPageGrid">
    <div class="contactCard">
      <span class="eyebrow eyebrow-line"><?= e($s['name']) ?></span>
      <h2>Монтаж и демонтаж на мебели</h2>
      <ul class="contactRows">
        <li><?= icon('phone') ?><span class="cr-key">Телефон</span><a href="<?= e($s['phoneHref']) ?>"><?= e($s['phone']) ?></a></li>
        <li><?= icon('mail') ?><span class="cr-key">Имейл</span><a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></li>
        <li><?= icon('pin') ?><span class="cr-key">Адрес</span><b><?= e($s['location']) ?></b></li>
        <li><?= icon('clock') ?><span class="cr-key">Работно време</span><b><?= e($s['hours']) ?></b></li>
      </ul>
      <p class="contactRegions"><?= e($s['region']) ?></p>
      <a href="/zapazi" class="btn btn-primary"><?= icon('calendar') ?> Запази час</a>
    </div>
    <div class="mapWrap"><iframe title="Карта — Добрич" src="https://maps.google.com/maps?q=%D0%94%D0%BE%D0%B1%D1%80%D0%B8%D1%87%2C%20%D0%91%D1%8A%D0%BB%D0%B3%D0%B0%D1%80%D0%B8%D1%8F&t=&z=11&ie=UTF8&iwloc=&output=embed" loading="lazy" referrerPolicy="no-referrer-when-downgrade" allowFullScreen></iframe></div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>