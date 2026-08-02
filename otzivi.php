<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/icons.php';
$s = settings(); $sts = stats(); $tests = testimonials();
$GLOBALS['path']='/otzivi';
$GLOBALS['hide_mobile_bar']=true;
$title='Отзиви | '.$s['name'];
$desc='Дайте отзив за MarTed — монтаж и демонтаж на мебели в Добрич.';
require __DIR__ . '/inc/header.php';
?>
<section class="pageHero">
  <div class="container pageHeroInner">
    <span class="eyebrow">Отзиви</span>
    <div class="reviewStars" aria-hidden="true"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
    <h1>Помогнахме ли ви? <em>Кажете го.</em></h1>
    <p>Един отзив отнема минута, а на нас помага повече от вячка реклама. Благодарим ви предварително.</p>
  </div>
</section>

<?php if (!empty($sts)): ?>
<section class="stats">
  <div class="container">
    <div class="statsGrid">
      <?php foreach ($sts as $st): ?>
      <div class="stat"><span class="num"><em><?= e($st['value']) ?></em></span><span class="label"><?= e($st['label']) ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($tests)): $t = $tests[0]; ?>
<section class="section">
  <div class="container" style="max-width:640px;text-align:center">
    <article class="quote reviewQuote">
      <span class="mark">"</span>
      <div class="stars"><?= str_repeat('★', $t['stars']) ?></div>
      <blockquote><?= e($t['text']) ?></blockquote>
      <span class="who"><?= e($t['name']) ?></span>
    </article>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container" style="max-width:640px;text-align:center">
    <a class="reviewCTA" href="https://g.page/r/Cah0utLY4-B-EAE/review" target="_blank" rel="noopener noreferrer">
      <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
      Оставете отзив в Google
    </a>
    <?php if (!empty($s['facebook'])): ?>
    <a class="btn btn-ghost reviewFbBtn" href="<?= e($s['facebook']) ?>" target="_blank" rel="noopener noreferrer">
      <?= icon('facebook') ?> Намерете ни във Facebook
    </a>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width:640px">
    <div class="reviewTips">
      <h2>Ако не знаете какво да напишете:</h2>
      <ol>
        <li><strong>Откъде беше</strong> — гр. Добрич, Балчик, Варна или околността</li>
        <li><strong>Каква беше услугата</strong> — монтаж на кухня, гардероб, спалня или демонтаж при пренасяне</li>
        <li><strong>Как мина</strong> — бързи ли бяхме, чисто и прецизно ли работихме, как се държахме с вас</li>
      </ol>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
