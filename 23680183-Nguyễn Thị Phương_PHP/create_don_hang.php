<?php
// ===== XỬ LÝ ĐẶT HÀNG TỪ GIỎ HÀNG =====
// Lưu xong thì CHUYỂN sang trang dat_hang_thanh_cong.php (mẫu Post/Redirect/Get),
// nhờ vậy bấm F5 ở trang kết quả không tạo thêm đơn trùng.
require 'init.php';

// RÀNG BUỘC: Bắt buộc đăng nhập mới được đặt hàng
if (!Auth::daDangNhap()) {
    header('Location: login.php?yeu_cau_dang_nhap=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: san_pham.php');
    exit;
}

// 1. Lấy thông tin người mua
$hoTen = trim($_POST['ho_ten'] ?? '');
$soDienThoai = preg_replace('/[\s.\-]/', '', trim($_POST['so_dien_thoai'] ?? ''));   // bỏ dấu cách, chấm, gạch
$diaChi = trim($_POST['dia_chi'] ?? '');
$ghiChu = trim($_POST['ghi_chu'] ?? '');

// 2. Tạo giỏ hàng từ JSON. Lớp GioHang tự đọc lại giá từ CSDL cho từng sản phẩm.
$gioHang = GioHang::taoTuJson($_POST['cart_json'] ?? '[]');

// 3. Kiểm tra dữ liệu
$loi = [];
if ($gioHang->laRong()) {
    $loi[] = 'Giỏ hàng trống.';
}
if ($hoTen === '' || $diaChi === '') {
    $loi[] = 'Vui lòng nhập đầy đủ họ tên và địa chỉ.';
}
if (!preg_match('/^0[0-9]{9,10}$/', $soDienThoai)) {
    $loi[] = 'Số điện thoại phải gồm 10-11 chữ số và bắt đầu bằng số 0.';
}
if (mb_strlen($ghiChu) > 500) {
    $loi[] = 'Ghi chú không được dài quá 500 ký tự.';
}

// 4. Hợp lệ: lưu đơn hàng rồi chuyển sang trang thành công
if (empty($loi)) {
    $donHang = DonHang::taoTuGioHang($gioHang, $hoTen, $soDienThoai, $diaChi, $ghiChu);
    $_SESSION['don_hang_vua_dat'] = $donHang->id;
    $_SESSION['canh_bao_gio_hang'] = $gioHang->canhBao();
    header('Location: dat_hang_thanh_cong.php');
    exit;
}

// 5. Có lỗi: hiển thị lỗi
$tieuDe = 'Đặt hàng chưa thành công';
require 'layouts/header_guest.php';
?>
<div class="khung-giua">
    <h1 class="giua">Đặt hàng chưa thành công</h1>
    <ul class="thong-bao-loi">
        <?php foreach (array_merge($loi, $gioHang->canhBao()) as $thongBao): ?>
            <li><?= e($thongBao) ?></li>
        <?php endforeach; ?>
    </ul>
    <p class="giua">Giỏ hàng của bạn vẫn được giữ nguyên.
        <a href="san_pham.php" class="nut nut-chinh">QUAY LẠI CỬA HÀNG</a></p>
</div>
<?php require 'layouts/footer.php'; ?>
