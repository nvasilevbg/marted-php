<?php $s = settings(); $hideBar = !empty($GLOBALS['hide_mobile_bar']); ?>
</main>
<!-- Lightbox -->
<div class="lightbox" id="lightbox">
  <span class="lightboxClose" id="lbClose">&times;</span>
  <button class="lightboxNav lightboxPrev" id="lbPrev">&lsaquo;</button>
  <button class="lightboxNav lightboxNext" id="lbNext">&rsaquo;</button>
  <div class="lightboxMedia" id="lbMedia"></div>
  <span class="lightboxCounter" id="lbCounter"></span>
</div>
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
<script src="/assets/js/main.js?v=3"></script>
<script>
(function(){
  var items = document.querySelectorAll('.projectGallery img, .projectGallery video');
  if (!items.length) return;
  var cur = 0;
  var lb = document.getElementById('lightbox');
  var lbMedia = document.getElementById('lbMedia');
  var lbCounter = document.getElementById('lbCounter');
  items.forEach(function(item, i){
    item.addEventListener('click', function(e){
      if (item.tagName === 'VIDEO') { e.preventDefault(); e.stopPropagation(); }
      cur = i; show();
    });
  });
  function show(){
    var item = items[cur];
    if (item.tagName === 'VIDEO') {
      var src = item.querySelector('source') ? item.querySelector('source').src : item.src;
      lbMedia.innerHTML = '<video controls autoplay src="'+src+'" style="max-width:90vw;max-height:85vh;border-radius:8px"></video>';
    } else {
      lbMedia.innerHTML = '<img src="'+item.src+'" style="max-width:90vw;max-height:85vh;border-radius:8px;object-fit:contain">';
    }
    lbCounter.textContent = (cur+1)+' / '+items.length;
    lb.style.display = 'flex';
  }
  function close(){ lb.style.display='none'; lbMedia.innerHTML=''; }
  function prev(){ cur=(cur-1+items.length)%items.length; show(); }
  function next(){ cur=(cur+1)%items.length; show(); }
  document.getElementById('lbClose').onclick = close;
  document.getElementById('lbPrev').onclick = function(e){ e.stopPropagation(); prev(); };
  document.getElementById('lbNext').onclick = function(e){ e.stopPropagation(); next(); };
  lb.addEventListener('click', function(e){ if(e.target===lb) close(); });
  document.addEventListener('keydown', function(e){
    if(lb.style.display==='flex'){
      if(e.key==='Escape') close();
      if(e.key==='ArrowLeft') prev();
      if(e.key==='ArrowRight') next();
    }
  });
})();
</script>
</body>
</html>