<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/admin-functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check()) { http_response_code(403); exit('Invalid CSRF token.'); }
$s = settings();
$GLOBALS['admin_page'] = 'bookings';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'] ?? '';
    if ($_POST['action'] === 'status' && in_array($_POST['status']??'', ['pending','confirmed','cancelled'])) {
        db()->prepare("UPDATE bookings SET status=? WHERE id=?")->execute([$_POST['status'], $id]);
    } elseif ($_POST['action'] === 'delete') {
        db()->prepare("DELETE FROM bookings WHERE id=?")->execute([$id]);
    }
    header('Location: bookings.php'); exit;
}
$filter = $_GET['filter'] ?? '';
$where = $filter === 'pending' ? " WHERE status='pending'" : '';
$bookings = db()->query("SELECT * FROM bookings$where ORDER BY bdate DESC, slot DESC")->fetchAll();
$labels = ['pending'=>'Ð§Ð°ÐºÐ°','confirmed'=>'ÐŸÐ¾Ñ‚Ð²ÑŠÑ€Ð´ÐµÐ½','cancelled'=>'ÐžÑ‚ÐºÐ°Ð·Ð°Ð½'];
$title = 'Ð ÐµÐ·ÐµÑ€Ð²Ð°Ñ†Ð¸Ð¸ | ÐÐ´Ð¼Ð¸Ð½ | ' . $s['name'];
require __DIR__ . '/../inc/admin-header.php';
?>
<div class="adminTop"><div><span class="eyebrow eyebrow-line">ÐÐ´Ð¼Ð¸Ð½</span><h1>Ð—Ð°Ð¿Ð°Ð·ÐµÐ½Ð¸ Ñ‡Ð°ÑÐ¾Ð²Ðµ</h1></div></div>
<?php if (empty($bookings)): ?>
<p class="formNote" style="margin-top:20px">Ð’ÑÐµ Ð¾Ñ‰Ðµ Ð½ÑÐ¼Ð° Ð·Ð°Ð¿Ð°Ð·ÐµÐ½Ð¸ Ñ‡Ð°ÑÐ¾Ð²Ðµ.</p>
<?php else: ?>
<div class="adminList">
  <?php foreach ($bookings as $b): ?>
  <div class="bookingItem">
    <div class="biMain">
      <span class="pill <?= e($b['status']) ?>"><?= e($labels[$b['status']] ?? $b['status']) ?></span>
      <strong><?= e($b['bdate']) ?> Â· <?= e($b['slot']) ?></strong>
      <span class="biService"><?= e($b['service']) ?></span>
    </div>
    <div class="biContact">
      <a href="tel:<?= e($b['phone']) ?>"><?= icon('phone') ?> <?= e($b['name']) ?></a>
      <a href="tel:<?= e($b['phone']) ?>"><?= e($b['phone']) ?></a>
    </div>
    <?php if ($b['notes']): ?><p class="biNotes"><?= e($b['notes']) ?></p><?php endif; ?>
    <div class="biActions">
      <form method="POST" style="display:inline">
  <?= csrf_field() ?>
        <input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?= e($b['id']) ?>">
        <?php if ($b['status'] !== 'confirmed'): ?><button name="status" value="confirmed" type="submit">ÐŸÐ¾Ñ‚Ð²ÑŠÑ€Ð´Ð¸</button><?php endif; ?>
        <?php if ($b['status'] !== 'cancelled'): ?><button name="status" value="cancelled" type="submit">ÐžÑ‚ÐºÐ°Ð¶Ð¸</button><?php endif; ?>
        <?php if ($b['status'] !== 'pending'): ?><button name="status" value="pending" type="submit">Ð§Ð°ÐºÐ°Ñ‰Ð¸</button><?php endif; ?>
      </form>
      <form method="POST" style="display:inline" onsubmit="return confirm('Ð˜Ð·Ñ‚Ñ€Ð¸Ð²Ð°Ð½Ðµ?')">
  <?= csrf_field() ?>
        <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= e($b['id']) ?>">
        <button class="del" type="submit">Ð˜Ð·Ñ‚Ñ€Ð¸Ð¹</button>
      </form>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../inc/admin-footer.php'; ?>
