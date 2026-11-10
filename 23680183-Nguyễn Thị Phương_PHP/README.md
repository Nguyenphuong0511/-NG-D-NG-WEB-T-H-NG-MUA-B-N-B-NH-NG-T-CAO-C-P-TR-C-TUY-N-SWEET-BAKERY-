# Project_demo_OOP — Khung mẫu thực hành (bản có ảnh, giỏ hàng, quản lý đơn hàng)

Đọc **Đề bài thực hành** và **Hướng dẫn thực hành Project_demo_OOP** do giáo viên phát.

## Bắt đầu

1. Chép thư mục này vào `C:\xampp\htdocs\`.
2. Import `database/project_demo.sql` trong phpMyAdmin. File tạo 5 bảng và có sẵn dữ liệu mẫu.
3. Chép ảnh `1.jpg … 9.jpg` vào thư mục `Images/`.
4. Mở `http://localhost/Project_demo_OOP_KhungMau/kiem_tra.php`. Lúc đầu trang báo **Đạt 0 / 28**.

## Cách làm

- Có **31 TODO**, làm theo thứ tự từ TODO 1 đến TODO 31. Mỗi TODO có ghi chú các bước và gợi ý code ngay trong file.
- TODO 1–20 nằm trong thư mục `classes/`. Làm xong mỗi TODO, tải lại `kiem_tra.php` để xem ✔ / ✘.
  Riêng TODO 12 (tải ảnh), dùng thêm ô "Thử tải ảnh thật" ở cuối trang `kiem_tra.php`.
- TODO 21–31 là các trang web, kiểm tra bằng cách dùng thử trên trình duyệt.

| Phần | TODO | Nội dung |
|---|---|---|
| A | 1 | Kết nối CSDL (`Database`) |
| B | 2–5 | Lớp cha `Model` |
| C | 6–9 | Lớp `SanPham` |
| D | 10–11 | `User`, `Auth` |
| E | 12–13 | Ảnh sản phẩm (`TaiAnhSanPham`) |
| F | 14–16 | Giỏ hàng (`MucGioHang`, `GioHang`) |
| G | 17–20 | Đơn hàng (`TrangThaiDonHang`, `ChiTietDonHang`, `DonHang`) |
| H | 21–23 | Trang khách: cửa hàng, đặt hàng, tra cứu đơn |
| I | 24–31 | Trang quản trị: đăng nhập, sản phẩm, đơn hàng |

Không sửa `kiem_tra.php` và các phần ghi "ĐÃ VIẾT SẴN".
