# PHP-Lab02 - Workshop Booking Portal
PHP thuần, không database/framework/login. Chạy lại các chức năng Instruction và thêm View Booking Detail, Edit Booking theo Requirement.


- Repository: https://github.com/ThienPhung04/Workshop-Booking-Portal .
- Chạy `powershell -NoProfile -ExecutionPolicy Bypass -File tools/publish-corrected-history.ps1` trên bản project này bằng tài khoản có quyền. Không pull lịch sử cũ vào nhánh đã sửa.

PHP thuần, không database/framework/login. Chạy lại các chức năng Instruction và thêm View Booking Detail, Edit Booking theo Requirement.

## Chạy bằng XAMPP
1. Chép nguyên folder `PHPLab02` vào `C:/xampp/htdocs/PHPLab02`.
2. Start Apache trong XAMPP Control Panel. Không cần MySQL.
3. Mở `http://localhost/PHPLab02/`.


## Chạy không cần Apache
Máy cần PHP 8.1 trở lên. Mở terminal tại folder này:
```powershell
C:\xampp\php\php.exe -S localhost:8002
```
Mở `http://localhost:8002/`. Nếu `php` đã có trong PATH có thể chạy `php -S localhost:8002`.
Hoặc nhấp đúp `run-local.cmd`. Giữ terminal mở, Ctrl+C để dừng. Script tìm PHP của XAMPP, trong PATH hoặc runtime local đã có trên máy.

## Luồng sử dụng
1. Catalog có 3 workshop; tìm tên bằng q, lọc category bằng GET.
2. Nhấn Đăng ký workshop, nhập họ tên/email/số vé rồi Confirm Booking.
3. My Bookings hiển thị đăng ký trong Session hiện tại.
4. View Details mở đúng booking theo ref; Edit điền sẵn thông tin.
5. Chỉ sửa họ tên, email, số vé. Giá/workshop/ref không nhận từ POST.
6. Nhập sai: hiện các lỗi cạnh field, giữ cả dữ liệu sai đang nhập, không cập nhật record trong Session.
7. Nhập đúng: lấy giá catalog, tính lại amount, cập nhật đúng record và chuyển hướng 303 đến Detail.
8. Refresh sau thành công không POST lại. Clear Session dùng POST+CSRF để xóa dữ liệu phiên.
9. Chọn light/dark ở Catalog; cookie theme giữ 30 ngày, không bị xóa cùng booking Session.
10. Request Demo minh họa GET/POST/REQUEST/REQUEST_METHOD, không thay đổi booking.

## Quy tắc và giới hạn
- Tên sau trim/strip_tags/gộp khoảng trắng phải dài 2–120 **bytes** (`strlen`), không phải số ký tự Unicode.
- Email trim rồi kiểm tra FILTER_VALIDATE_EMAIL; không tự sửa nội dung email.
- Số vé kiểm tra FILTER_VALIDATE_INT, từ 1 đến 4 và không vượt seats trong catalog.
- ID workshop phải tồn tại; mọi output text escape bằng e()/htmlspecialchars.
- Mọi POST thay đổi trạng thái có CSRF token dùng hash_equals. GET không cập nhật record booking.
- Missing/unknown ref trả HTTP 404; sai CSRF 403; sai dữ liệu 422; sai method 405; success 303+exit.
- POST lỗi render form trực tiếp với HTTP 422 để giữ raw values; POST thành công dùng POST/Redirect/GET.
- Session cookie riêng `PHPLAB02SESSID`. Các browser/cookie jar độc lập không dùng chung booking.
- Seats chỉ là giới hạn cho một booking demo, không giảm tồn kho toàn cục. Không bảo đảm chống đặt trùng nhiều thiết bị.
- Hai tab cùng sửa một booking: lần lưu sau ghi đè lần trước; không có version/conflict resolution trong phạm vi hiện tại.
- Ref được tạo ngẫu nhiên, tra theo thuộc tính ref; không dùng index mảng làm mã booking.
- Cookie theme là preference, không lưu mật khẩu hoặc credential.

## Cấu trúc
```text
PHPLab02/
  index.php                GET catalog/search/filter
  booking.php              GET form / POST tạo booking
  dashboard.php            danh sách + nút View Details / Edit
  booking_detail.php       GET chi tiết theo ref
  booking_edit.php         GET prefill / POST cập nhật
  preference.php           POST lưu theme cookie
  logout.php               POST clear Session
  request_demo.php         quan sát superglobals
  data/workshops.php       catalog đáng tin cậy phía server
  lib/bootstrap.php        Session, CSRF, theme, catalog
  lib/functions.php        validate, normalize, escape, lookup, redirect
  views/                   header/footer/form/error dùng chung
  assets/style.css         responsive, light/dark, focus và lỗi form
  tests/http_checks.py      kiểm tra HTTP, Python standard library
  tests/ui_checks.js        kiểm tra giao diện qua playwright-cli
  docs/                    kết quả kiểm thử và ảnh thật
```

## Tái kiểm tra
Không cần cài thư viện Python bên ngoài:
```powershell
python tests/http_checks.py http://localhost/PHPLab02/
```
Nếu chạy built-in server: `python tests/http_checks.py http://localhost:8002/`.
Script dùng Session riêng, không xóa booking của browser người dùng. Kết quả Expected/Actual ở docs/test-results.json; tổng hợp ở docs/TEST-RESULTS.md. Tất cả file PHP đã lint bằng PHP của XAMPP.

Kiểm tra UI cần playwright-cli; script tests/ui_checks.js sử dụng URL XAMPP mặc định. Có thể chạy trong browser test riêng: `playwright-cli -s=phplab02 open http://localhost/PHPLab02/` rồi `playwright-cli -s=phplab02 run-code --filename=tests/ui_checks.js`.

## Git local
main lưu baseline từ source Instruction; feature/booking-detail-edit chứa chức năng và hồ sơ hoàn thiện. Origin đã trỏ tới repository ThienPhung04/Workshop-Booking-Portal. 
