<?php $s = settings(); ?>
<section class="section" id="zapazi">
  <div class="container">
    <div class="sectionHead reveal">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">Резервация</span><h2>Запазете <em>час</em></h2></div>
      <p>Изберете удобна дата и час за монтаж, демонтаж или консултация. Потвърждаваме по телефон.</p>
    </div>
    <div class="reveal" style="--d:80ms">
      <div id="booking"></div>
    </div>
  </div>
</section>
<script>window.SITE_HOURS=<?= json_encode($s['hours']) ?>;window.SITE_PHONE=<?= json_encode($s['phone']) ?>;window.SERVICES=<?= json_encode(array_column(services(),'title')) ?>;</script>
<script src="/assets/js/booking.js?v=2"></script>