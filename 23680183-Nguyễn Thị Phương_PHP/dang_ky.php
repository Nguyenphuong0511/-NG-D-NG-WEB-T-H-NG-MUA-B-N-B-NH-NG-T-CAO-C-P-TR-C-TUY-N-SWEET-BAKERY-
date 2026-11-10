<?php
// ===== ĐĂNG KÝ TÀI KHOẢN KHÁCH HÀNG =====
require 'init.php';

// Nếu đã đăng nhập rồi thì chuyển hướng
if (Auth::daDangNhap()) {
    if (Auth::laQuanTri()) {
        header('Location: don_hang_admin.php');
    } else {
        header('Location: san_pham.php');
    }
    exit;
}

$thongBaoLoi = '';
$tenDangNhap = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenDangNhap = trim($_POST['tai_khoan'] ?? '');
    $matKhau = $_POST['mat_khau'] ?? '';
    $matKhauNhapLai = $_POST['mat_khau_nhap_lai'] ?? '';

    // Kiểm tra dữ liệu hợp lệ
    if ($tenDangNhap === '' || $matKhau === '' || $matKhauNhapLai === '') {
        $thongBaoLoi = 'Vui lòng điền đầy đủ tất cả các trường bắt buộc.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $tenDangNhap)) {
        $thongBaoLoi = 'Tên đăng nhập từ 3–30 ký tự, chỉ gồm chữ cái, chữ số và dấu gạch dưới.';
    } elseif (mb_strlen($matKhau) < 6) {
        $thongBaoLoi = 'Mật khẩu phải có độ dài từ 6 ký tự trở lên.';
    } elseif ($matKhau !== $matKhauNhapLai) {
        $thongBaoLoi = 'Mật khẩu xác nhận không trùng khớp.';
    } elseif (User::timTheoTenDangNhap($tenDangNhap) !== null) {
        $thongBaoLoi = 'Tên đăng nhập này đã tồn tại trên hệ thống. Hãy chọn tên khác!';
    } else {
        // Tạo tài khoản khách hàng mới với mã hóa BCrypt
        $user = new User();
        $user->ten_dang_nhap = $tenDangNhap;
        $user->mat_khau = password_hash($matKhau, PASSWORD_DEFAULT);
        $user->vai_tro = 'khach_hang';
        $user->save();

        // Tự động đăng nhập luôn cho khách hàng
        Auth::dangNhap($tenDangNhap, $matKhau);
        header('Location: san_pham.php?chao_mung=1');
        exit;
    }
}

$tieuDe = 'Đăng ký tài khoản khách hàng - Sweet Bakery';
require 'layouts/header_guest.php';
?>

<div class="auth-page-container">
    <div class="auth-modern-card">
        <!-- Logo / Icon biểu tượng -->
        <div class="auth-header-top">
            <span class="auth-icon-badge">
                <i class="fa-solid fa-user-plus"></i>
            </span>
            <h1 class="auth-title">Tạo Tài Khoản Khách Hàng</h1>
            <p class="auth-subtitle">Đăng ký để lưu đơn hàng, nhận ưu đãi và mua bánh nhanh chóng</p>
        </div>

        <!-- 2 Tab chuyển đổi Đăng nhập / Đăng ký trực quan -->
        <div class="auth-tab-switch">
            <a href="login.php" class="auth-tab-item">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng nhập
            </a>
            <a href="dang_ky.php" class="auth-tab-item active">
                <i class="fa-solid fa-user-plus"></i> Đăng ký
            </a>
        </div>

        <?php if ($thongBaoLoi !== ''): ?>
            <div class="thong-bao-loi" style="margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= e($thongBaoLoi) ?>
            </div>
        <?php endif; ?>

        <form method="post" autocomplete="on">
            <!-- Ô nhập Tên đăng nhập -->
            <div class="auth-form-group">
                <label for="tai_khoan">
                    Tên đăng nhập <span class="bat-buoc">*</span>
                </label>
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-user field-icon"></i>
                    <input type="text" id="tai_khoan" name="tai_khoan" value="<?= e($tenDangNhap) ?>" 
                           placeholder="Ví dụ: phuong_bakery" required autofocus autocomplete="username">
                </div>
                <small class="auth-help-note">Từ 3–30 ký tự, viết liền không dấu, không khoảng trắng.</small>
            </div>

            <!-- Ô nhập Mật khẩu -->
            <div class="auth-form-group">
                <label for="mat_khau">
                    Mật khẩu <span class="bat-buoc">*</span>
                </label>
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-lock field-icon"></i>
                    <input type="password" id="mat_khau" name="mat_khau" 
                           placeholder="Tối thiểu 6 ký tự" required autocomplete="new-password">
                </div>
            </div>

            <!-- Ô nhập lại Mật khẩu -->
            <div class="auth-form-group">
                <label for="mat_khau_nhap_lai">
                    Xác nhận lại mật khẩu <span class="bat-buoc">*</span>
                </label>
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-shield-halved field-icon"></i>
                    <input type="password" id="mat_khau_nhap_lai" name="mat_khau_nhap_lai" 
                           placeholder="Nhập lại mật khẩu phía trên" required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="auth-submit-btn">
                <i class="fa-solid fa-user-check"></i> Hoàn Tất Đăng Ký
            </button>
        </form>

        <div style="margin-top: 24px; text-align: center; font-size: 13.5px; color: var(--text-muted);">
            Bạn đã có tài khoản rồi? 
            <a href="login.php" style="color: var(--accent); font-weight: 700; text-decoration: none;">
                Đăng nhập tại đây
            </a>
        </div>
    </div>
</div>

<?php require 'layouts/footer.php'; ?>
