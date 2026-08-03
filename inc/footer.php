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
  <div class="container footerBottom" style="flex-wrap:wrap;gap:10px"><span><a href="/politika-poveritelnost" style="color:var(--muted);text-decoration:none">Политика за поверителност</a> · <a href="/usloviya-polzvane" style="color:var(--muted);text-decoration:none">Условия за ползване</a> · <a href="/politika-biskvitki" style="color:var(--muted);text-decoration:none">Политика за бисквитки</a></span>
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
<script src="/assets/js/main.js?v=4"></script>
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
<!-- Cookie Banner -->
<div class="cookieBanner" id="cookieBanner">
  <div class="cookieBannerInner">
    <div class="cookieBannerText">Този сайт използва бисквитки за анализ на посещенията (Umami Analytics) — без проследяване на лични данни. Продължавайки, вие се съгласявате с това. Вижте <a href="/politika-poveritelnost">Политика за поверителност</a>.</div>
    <div class="cookieBannerBtns"><button class="btn btn-primary" id="cookieAccept" type="button">Приемам</button></div>
  </div>
</div>
<script>
(function(){if(localStorage.getItem("cookieAccepted")){document.getElementById("cookieBanner").style.display="none";return;}document.getElementById("cookieBanner").classList.add("show");document.getElementById("cookieAccept").onclick=function(){localStorage.setItem("cookieAccepted","1");document.getElementById("cookieBanner").classList.remove("show");};})();
</script>
</body>
</html>