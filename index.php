<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings(); $svcs = services(); $projs = projects(); $sts = stats(); $tests = testimonials();
$GLOBALS['path'] = '/'; $title = $s['name'] . ' | ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸';
require __DIR__ . '/inc/header.php';
?>
<section class="hero" id="nachalo">
  <div class="container heroGrid">
    <div class="heroCopy">
      <p class="eyebrow">Ð¤Ð¸Ñ€Ð¼Ð° Ð·Ð° Ð¼Ð¾Ð½Ñ‚Ð°Ð¶ Â· Ð³Ñ€. Ð”Ð¾Ð±Ñ€Ð¸Ñ‡</p>
      <h1><?php echo e(content("home_hero_title","ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸")); ?></h1>
      <p class="heroLead" ><?php echo e(content("home_hero_lead")); ?></p>
      <div class="heroActions" >
        <a href="<?= e($s['phoneHref']) ?>" class="btn btn-primary"><?= icon('phone') ?> ÐŸÐ¾Ð·Ð²ÑŠÐ½Ð¸ ÑÐµÐ³Ð°</a>
        <a href="/zapazi" class="btn btn-ghost">Ð—Ð°Ð¿Ð°Ð·Ð¸ Ñ‡Ð°Ñ</a>
      </div>
      <div class="heroSpec" >
        <div><?= icon('shield') ?><span><strong>Ð“Ð°Ñ€Ð°Ð½Ñ†Ð¸Ñ</strong></span></div>
        <div><?= icon('clock') ?><span><strong>ÐŸÐ¾Ð½â€“ÐÐµÐ´</strong> 08â€“20</span></div>
        <div><?= icon('pin') ?><span><strong>Ð”Ð¾Ð±Ñ€Ð¸Ñ‡</strong></span></div>
      </div>
    </div>
    <div class="heroVisual">
      <?php $heroImages = json_decode(setting('home_hero_images', '[]'), true) ?: []; ?>
      <?php if (empty($heroImages)): $heroImages = [content('home_hero_image', '/assets/media/hero-kitchen.jpg')]; endif; ?>
      <?php if (count($heroImages) > 1): ?>
      <div class="heroCarousel" id="heroCarousel">
        <?php foreach ($heroImages as $i => $hi): ?>
        <div class="heroCarouselSlide<?= $i === 0 ? ' active' : '' ?>"><img src="<?= e($hi) ?>" alt="MarTed Ð¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸" loading="lazy"></div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="heroPhoto"><img src="<?= e($heroImages[0]) ?>" alt="ÐœÐ¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼Ð¾Ð´ÐµÑ€Ð½Ð° ÐºÑƒÑ…Ð½Ñ"></div>
      <?php endif; ?>
      
    </div>
  </div>
</section>

<section class="stats">
  <div class="container">
    <div class="statsGrid">
      <?php foreach ($sts as $st): ?>
      <div class="stat"><span class="num"><em><?= e($st['value']) ?></em></span><span class="label"><?= e($st['label']) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="uslugi">
  <div class="container">
    <div class="sectionHead">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">ÐÐ°ÑˆÐ¸Ñ‚Ðµ ÑƒÑÐ»ÑƒÐ³Ð¸</span><h2>ÐšÐ°ÐºÐ²Ð¾ <em>Ð¿Ñ€ÐµÐ´Ð»Ð°Ð³Ð°Ð¼Ðµ</em></h2></div>
      <p>Ð¯ÑÐµÐ½ Ð¿Ñ€Ð¾Ñ†ÐµÑ, Ð¿Ñ€Ð°Ð²Ð¸Ð»Ð½Ð¸ Ð¸Ð½ÑÑ‚Ñ€ÑƒÐ¼ÐµÐ½Ñ‚Ð¸, Ñ‡Ð¸ÑÑ‚ Ð¾Ð±ÐµÐºÑ‚ Ð¸ ÐºÐ¾Ñ€ÐµÐºÑ‚Ð½Ð¾ Ð¸Ð·Ð¿ÑŠÐ»Ð½ÐµÐ½Ð¸Ðµ Ð¿Ñ€Ð¸ Ð²ÑÐµÐºÐ¸ Ð¼Ð¾Ð½Ñ‚Ð°Ð¶.</p>
    </div>
    <div class="serviceIconGrid" >
      <?php foreach ($svcs as $svc): ?>
      <article class="serviceIconCard"><?= icon($svc['icon']) ?><h3><?= e($svc['title']) ?></h3><p><?= e($svc['text']) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="proekti">
  <div class="container">
    <div class="sectionHead">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">ÐŸÑ€Ð¾ÐµÐºÑ‚Ð¸</span><h2>ÐÐ°ÑˆÐ¸Ñ‚Ðµ <em>Ð¿Ñ€Ð¾ÐµÐºÑ‚Ð¸</em></h2></div>
      <p>Ð˜Ð·Ð¿ÑŠÐ»Ð½ÐµÐ½Ð¸ Ð¾Ð±ÐµÐºÑ‚Ð¸ â€” ÐºÑƒÑ…Ð½Ð¸, ÑÐ¿Ð°Ð»Ð½Ð¸, Ð³Ð°Ñ€Ð´ÐµÑ€Ð¾Ð±Ð¸, ÑÐµÐºÑ†Ð¸Ð¸ Ð¸ Ð¾Ñ„Ð¸Ñ Ð¾Ð±Ð·Ð°Ð²ÐµÐ¶Ð´Ð°Ð½Ðµ.</p>
      <div class="headActions"><a href="/proekti" class="btn btn-ghost">Ð’Ð¸Ð¶ Ð²ÑÐ¸Ñ‡ÐºÐ¸ Ð¿Ñ€Ð¾ÐµÐºÑ‚Ð¸</a></div>
    </div>
    <div class="projectMosaic" >
      <?php $mcls=['pc-feat','pc-sq','pc-tall','pc-wide','pc-sq','pc-wide']; foreach (array_slice($projs,0,6) as $i=>$p): ?>
      <a class="projectCard <?= $mcls[$i%6] ?>" href="/proekti/<?= e($p['slug']) ?>">
        <img src="<?= e($p['cover']) ?>" srcset="<?= srcset($p['cover']) ?>" sizes="(max-width:760px) 100vw, 50vw" alt="<?= e($p['title']) ?>" loading="lazy">
        <span class="arrowChip"><?= icon('arrow') ?></span>
        <span class="cap"><span class="cat"><?= e($p['category']) ?> Â· <?= e($p['location']) ?></span><h3><?= e($p['title']) ?></h3><p class="desc"><?= e($p['description']) ?></p></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section testimonials" id="otzivi">
  <div class="container">
    <div class="sectionHead">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">ÐžÑ‚Ð·Ð¸Ð²Ð¸</span><h2>ÐšÐ°ÐºÐ²Ð¾ ÐºÐ°Ð·Ð²Ð°Ñ‚ <em>Ð½Ð°ÑˆÐ¸Ñ‚Ðµ ÐºÐ»Ð¸ÐµÐ½Ñ‚Ð¸</em></h2></div>
    </div>
    <div class="quoteGrid" >
      <?php foreach ($tests as $t): ?>
      <article class="quote"><span class="mark">"</span><div class="stars">â˜…â˜…â˜…â˜…â˜…</div><blockquote><?= e($t['text']) ?></blockquote><span class="who"><?= e($t['name']) ?></span></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/booking-section.php'; ?>

<script>
if (document.getElementById('heroCarousel')) {
  var slides = document.querySelectorAll('.heroCarouselSlide');
  var current = 0;
  if (slides.length > 1) {
    setInterval(function() {
      slides[current].classList.remove('active');
      current = (current + 1) % slides.length;
      slides[current].classList.add('active');
    }, 5000);
  }
}
</script>
<?php require __DIR__ . '/inc/footer.php'; ?>