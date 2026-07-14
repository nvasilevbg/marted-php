<?php $s = settings(); $hideBar = !empty($GLOBALS['hide_mobile_bar']); ?>
</main>
<footer class="footer">
  <div class="container footerTop">
    <div>
      <img class="footerLogo" src="/assets/media/logo.png" alt="<?= e($s['name']) ?>">
      <div class="footerTag"><?= e($s['tagline']) ?></div>
    </div>
    <div class="footerCol">
      <h3>Навигация</h3>
      <a href="/uslugi">Услуги</a><a href="/proekti">Проекти</a><a href="/za-nas">За нас</a><a href="/kontakti">Контакти</a>
    </div>
    <div class="footerCol">
      <h3>Услуги</h3>
      <a href="/uslugi">Монтаж на мебели</a><a href="/uslugi">Демонтаж на мебели</a><a href="/uslugi">Разнос и изнасяне</a><a href="/uslugi">Замерване и консултация</a>
    </div>
    <div class="footerCol">
      <h3>Контакти</h3>
      <a href="<?= e($s['phoneHref']) ?>"><?= icon('phone','icon') ?> <?= e($s['phone']) ?></a>
      <a href="mailto:<?= e($s['email']) ?>"><?= icon('mail','icon') ?> <?= e($s['email']) ?></a>
      <span><?= icon('pin','icon') ?> <?= e($s['location']) ?></span>
      <span><?= icon('clock','icon') ?> <?= e($s['hours']) ?></span>
    </div>
  </div>
  <div class="container footerBottom">
    <span>© <?= date('Y') ?> <?= e($s['name']) ?> — монтаж и демонтаж на мебели</span>
    <a href="https://blackswan.social/izrabotka-na-saitove" target="_blank" rel="noopener noreferrer">Уеб сайтът е изработен от Black Swan Social</a>
  </div>
</footer>
<?php if (!$hideBar): ?>
<div class="mobileBar" aria-label="Бърз контакт">
  <a class="mbBtn mbPhone" href="<?= e($s['phoneHref']) ?>"><?= icon('phone') ?> Позвъни</a>
  <a class="mbBtn mbBook" href="/zapazi">Запази час</a>
</div>
<?php endif; ?>
<script src="/assets/js/main.js?v=2"></script>
</body>
</html>