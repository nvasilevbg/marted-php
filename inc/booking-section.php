<?php $s = settings(); ?>
<section class="section" id="zapazi">
  <div class="container">
    <div class="sectionHead">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">Ð ÐµÐ·ÐµÑ€Ð²Ð°Ñ†Ð¸Ñ</span><h2>Ð—Ð°Ð¿Ð°Ð·ÐµÑ‚Ðµ <em>Ñ‡Ð°Ñ</em></h2></div>
      <p>Ð˜Ð·Ð±ÐµÑ€ÐµÑ‚Ðµ ÑƒÐ´Ð¾Ð±Ð½Ð° Ð´Ð°Ñ‚Ð° Ð¸ Ñ‡Ð°Ñ Ð·Ð° Ð¼Ð¾Ð½Ñ‚Ð°Ð¶, Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð¸Ð»Ð¸ ÐºÐ¾Ð½ÑÑƒÐ»Ñ‚Ð°Ñ†Ð¸Ñ. ÐŸÐ¾Ñ‚Ð²ÑŠÑ€Ð¶Ð´Ð°Ð²Ð°Ð¼Ðµ Ð¿Ð¾ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½.</p>
    </div>
    <div>
      <div id="booking"></div>
    </div>
  </div>
</section>
<script>window.SITE_HOURS=<?= json_encode($s['hours']) ?>;window.SITE_PHONE=<?= json_encode($s['phone']) ?>;window.SERVICES=<?= json_encode(array_column(services(),'title')) ?>;</script>
<script src="/assets/js/booking.js?v=4" defer></script>
