<?php
require __DIR__ . '/lib/bootstrap.php';
allow_methods(['GET']);
$ref = input_text($_GET, 'ref');
$index = find_booking_index($ref);
if ($index === null) fail_request(404, 'Booking not found. Mã không tồn tại hoặc Session đã hết hạn.');
$booking = $_SESSION['bookings'][$index];
$pageTitle = 'View Booking Detail';
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
require __DIR__ . '/views/header.php';
?>
<?php if ($flash): ?><p class="notice" role="status"><?= e($flash) ?></p><?php endif; ?>
<section class="panel detail"><span class="tag"><?= e($booking['ref']) ?></span><h2><?= e($booking['workshop']) ?></h2>
<dl><div><dt>Reference</dt><dd><?= e($booking['ref']) ?></dd></div><div><dt>Full Name</dt><dd><?= e($booking['full_name']) ?></dd></div><div><dt>Email</dt><dd><?= e($booking['email']) ?></dd></div><div><dt>Workshop</dt><dd><?= e($booking['workshop']) ?></dd></div><div><dt>Tickets</dt><dd><?= e($booking['quantity']) ?></dd></div><div><dt>Unit Fee</dt><dd><?= money($booking['unit_fee']) ?></dd></div><div class="total"><dt>Amount</dt><dd><?= money($booking['amount']) ?></dd></div></dl>
<div class="actions"><a class="btn" href="<?= e(booking_url('booking_edit.php', $ref)) ?>">Edit This Booking</a><a class="btn secondary" href="dashboard.php">Back to My Bookings</a></div></section>
<?php require __DIR__ . '/views/footer.php'; ?>
