<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings();
$GLOBALS['path']='/usloviya-polzvane';
$GLOBALS['hide_mobile_bar']=true;
$title='Условия за ползване | '.$s['name'];
$desc='Условия за ползване — '.$s['name'].', монтаж и демонтаж на мебели в Добрич.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow">Правна информация</span>
    <h1>Условия за ползване</h1>
    <p><?= e($s['name']) ?> — монтаж и демонтаж на мебели в Добрич и околността.</p>
  </div>
</section>
<section class="section">
  <div class="container" style="max-width:760px">
    <div class="legalContent"><?= content_with_html('usloviya_polzvane','') ?></div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
