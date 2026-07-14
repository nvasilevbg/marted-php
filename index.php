<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings(); $svcs = services(); $projs = projects(); $sts = stats(); $tests = testimonials();
$GLOBALS['path'] = '/'; $title = $s['name'] . ' | Монтаж и демонтаж на мебели';
require __DIR__ . '/inc/header.php';
?>
<section class="hero" id="nachalo">
  <div class="container heroGrid">
    <div class="heroCopy">
      <p class="eyebrow anim" style="--d:0ms">Фирма за монтаж · гр. Добрич</p>
      <h1 class="anim" style="--d:120ms"><?php echo e(content("home_hero_title","Монтаж и демонтаж на мебели")); ?></h1>
      <p class="heroLead anim" style="--d:260ms"><?php echo e(content("home_hero_lead")); ?></p>
      <div class="heroActions anim" style="--d:380ms">
        <a href="<?= e($s['phoneHref']) ?>" class="btn btn-primary"><?= icon('phone') ?> Позвъни сега</a>
        <a href="/zapazi" class="btn btn-ghost">Запази час</a>
      </div>
      <div class="heroSpec anim" style="--d:520ms">
        <div><?= icon('shield') ?><span><strong>Гаранция</strong></span></div>
        <div><?= icon('clock') ?><span><strong>Пон–Нед</strong> 08–20</span></div>
        <div><?= icon('pin') ?><span><strong>Добрич</strong></span></div>
      </div>
    </div>
    <div class="heroVisual anim" style="--d:300ms">
      <div class="heroPhoto"><img src="<?php echo e(content("home_hero_image","/assets/media/hero-kitchen.jpg")); ?>" alt="Монтаж на модерна кухня"></div>
      <p class="heroCaption">Кухня по поръчка · гр. Добрич</p>
    </div>
  </div>
</section>

<section class="stats">
  <div class="container">
    <div class="statsGrid reveal">
      <?php foreach ($sts as $st): ?>
      <div class="stat"><span class="num"><em><?= e($st['value']) ?></em></span><span class="label"><?= e($st['label']) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="uslugi">
  <div class="container">
    <div class="sectionHead reveal">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">Нашите услуги</span><h2>Какво <em>предлагаме</em></h2></div>
      <p>Ясен процес, правилни инструменти, чист обект и коректно изпълнение при всеки монтаж.</p>
    </div>
    <div class="serviceIconGrid reveal" style="--d:80ms">
      <?php foreach ($svcs as $svc): ?>
      <article class="serviceIconCard"><?= icon($svc['icon']) ?><h3><?= e($svc['title']) ?></h3><p><?= e($svc['text']) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="proekti">
  <div class="container">
    <div class="sectionHead reveal">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">Проекти</span><h2>Нашите <em>проекти</em></h2></div>
      <p>Изпълнени обекти — кухни, спални, гардероби, секции и офис обзавеждане.</p>
      <div class="headActions"><a href="/proekti" class="btn btn-ghost">Виж всички проекти</a></div>
    </div>
    <div class="projectMosaic reveal" style="--d:80ms">
      <?php $mcls=['pc-feat','pc-sq','pc-tall','pc-wide','pc-sq','pc-wide']; foreach (array_slice($projs,0,6) as $i=>$p): ?>
      <a class="projectCard <?= $mcls[$i%6] ?>" href="/proekti/<?= e($p['slug']) ?>">
        <img src="<?= e($p['cover']) ?>" alt="<?= e($p['title']) ?>">
        <span class="arrowChip"><?= icon('arrow') ?></span>
        <span class="cap"><span class="cat"><?= e($p['category']) ?> · <?= e($p['location']) ?></span><h3><?= e($p['title']) ?></h3><p class="desc"><?= e($p['description']) ?></p></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section testimonials" id="otzivi">
  <div class="container">
    <div class="sectionHead reveal">
      <span class="sectionIndex" aria-hidden="true"></span>
      <div class="sectionTitle"><span class="eyebrow eyebrow-line">Отзиви</span><h2>Какво казват <em>нашите клиенти</em></h2></div>
    </div>
    <div class="quoteGrid reveal" style="--d:80ms">
      <?php foreach ($tests as $t): ?>
      <article class="quote"><span class="mark">"</span><div class="stars">★★★★★</div><blockquote><?= e($t['text']) ?></blockquote><span class="who"><?= e($t['name']) ?></span></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/booking-section.php'; ?>
<?php require __DIR__ . '/inc/footer.php'; ?>