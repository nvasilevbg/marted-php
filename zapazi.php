<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings(); $GLOBALS['path']='/zapazi'; $GLOBALS['hide_mobile_bar']=true;
$title='Ð—Ð°Ð¿Ð°Ð·Ð¸ Ñ‡Ð°Ñ | '.$s['name']; $desc='Ð—Ð°Ð¿Ð°Ð·ÐµÑ‚Ðµ Ñ‡Ð°Ñ Ð·Ð° Ð¼Ð¾Ð½Ñ‚Ð°Ð¶, Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð¸Ð»Ð¸ ÐºÐ¾Ð½ÑÑƒÐ»Ñ‚Ð°Ñ†Ð¸Ñ Ñ MarTed.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow anim">Ð ÐµÐ·ÐµÑ€Ð²Ð°Ñ†Ð¸Ñ</span>
    <h1 class="anim">Ð—Ð°Ð¿Ð°Ð·ÐµÑ‚Ðµ <em>Ñ‡Ð°Ñ</em></h1>
    <p class="anim">Ð˜Ð·Ð±ÐµÑ€ÐµÑ‚Ðµ ÑƒÐ´Ð¾Ð±Ð½Ð° Ð´Ð°Ñ‚Ð° Ð¸ Ñ‡Ð°Ñ Ð·Ð° Ð¼Ð¾Ð½Ñ‚Ð°Ð¶, Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð¸Ð»Ð¸ ÐºÐ¾Ð½ÑÑƒÐ»Ñ‚Ð°Ñ†Ð¸Ñ. ÐŸÐ¾Ñ‚Ð²ÑŠÑ€Ð¶Ð´Ð°Ð²Ð°Ð¼Ðµ Ð¿Ð¾ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½.</p>
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
