<?php
require __DIR__ . '/lib/bootstrap.php';
allow_methods(['GET', 'POST']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_valid($_POST)) {
    fail_request(403, 'Invalid CSRF token. Mở lại form rồi thử lại.');
}
// Ref của route xác định record. Không dùng index hay ref do POST gửi lên.
$ref = input_text($_GET, 'ref');
$index = find_booking_index($ref);
if ($index === null) fail_request(404, 'Booking not found. Không tạo booking mới khi mã không hợp lệ.');
$booking = $_SESSION['bookings'][$index];
$workshop = $workshops[$booking['workshop_id']] ?? null;
if ($workshop === null) fail_request(404, 'Workshop của booking không còn trong catalog.');
$errors = [];
$editing = true;
$old = ['full_name' => $booking['full_name'], 'email' => $booking['email'], 'quantity' => (string) $booking['quantity']];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = ['full_name' => input_text($_POST, 'full_name'), 'email' => input_text($_POST, 'email'), 'quantity' => input_text($_POST, 'quantity')];
    // Workshop/giá lấy từ catalog và booking hiện có, không tin hidden input.
    $input = $old;
    $input['workshop_id'] = $booking['workshop_id'];
    $result = validate_booking($input, $workshops);
    $errors = $result['errors'];
    if (!$errors) {
        $data = $result['data'];
        $updated = $booking;
        $updated['full_name'] = $data['full_name'];
        $updated['email'] = $data['email'];
        $updated['quantity'] = $data['quantity'];
        $updated['workshop'] = $workshop['title'];
        $updated['unit_fee'] = $workshop['fee'];
        $updated['amount'] = $workshop['fee'] * $data['quantity'];
        $_SESSION['bookings'][$index] = $updated;
        $_SESSION['flash'] = 'Cập nhật booking thành công.';
        redirect_to(booking_url('booking_detail.php', $ref));
    }
    http_response_code(422);
}
$pageTitle = 'Edit Booking';
$formAction = booking_url('booking_edit.php', $ref);
$cancelUrl = booking_url('booking_detail.php', $ref);
require __DIR__ . '/views/header.php';
require __DIR__ . '/views/booking_form.php';
require __DIR__ . '/views/footer.php';
