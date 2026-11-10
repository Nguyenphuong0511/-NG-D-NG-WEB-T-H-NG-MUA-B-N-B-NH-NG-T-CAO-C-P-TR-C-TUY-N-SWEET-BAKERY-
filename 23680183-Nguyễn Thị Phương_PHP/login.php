<?php
// ===== ĐĂNG NHẬP =====
require 'init.php';

// Nếu đã đăng nhập rồi thì chuyển hướng đến trang phù hợp
if (Auth::daDangNhap()) {
    if (Auth::laAdmin()) {
        header('Location: san_pham_admin.php');
    } elseif (Auth::laQuanTri()) {
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

    if (Auth::dangNhap($tenDangNhap, $matKhau)) {
        if (Auth::laAdmin()) {
            header('Location: san_pham_admin.php');
        } elseif (Auth::laQuanTri()) {
            header('Location: don_hang_admin.php');
        } else {
            header('Location: san_pham.php');
        }
        exit;
    } else {
        $thongBaoLoi = 'Tên đăng nhập hoặc mật khẩu không chính xác, vui lòng kiểm tra lại!';
    }
}

$tieuDe = 'Đăng nhập tài khoản - Sweet Bakery';
require 'layouts/header_guest.php';
?>

<div class="auth-page-container">
    <div class="auth-modern-card">
        <!-- Logo / Icon biểu tượng -->
        <div class="auth-header-top">
            <span class="auth-icon-badge">
                <i class="fa-solid fa-cake-candles"></i>
            </span>
            <h1 class="auth-title">Chào Mừng Trở Lại!</h1>
            <p class="auth-subtitle">Đăng nhập tài khoản để đặt bánh và trải nghiệm mua sắm</p>
        </div>

        <!-- 2 Tab chuyển đổi Đăng nhập / Đăng ký trực quan -->
        <div class="auth-tab-switch">
            <a href="login.php" class="auth-tab-item active">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng nhập
            </a>
            <a href="dang_ky.php" class="auth-tab-item">
                <i class="fa-solid fa-user-plus"></i> Đăng ký
            </a>
        </div>

        <?php if (isset($_GET['yeu_cau_dang_nhap'])): ?>
            <div style="margin-bottom: 20px; padding: 12px 16px; background: #FFFBEB; border: 1.5px solid #FDE68A; color: #92400E; border-radius: 12px; font-size: 13.5px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i class="fa-solid fa-lock" style="font-size: 16px; color: #D97706;"></i>
                <span>Bạn cần đăng nhập tài khoản để tiến hành đặt mua bánh!</span>
            </div>
        <?php endif; ?>

        <?php if ($thongBaoLoi !== ''): ?>
            <div class="thong-bao-loi" style="margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= e($thongBaoLoi) ?>
            </div>
        <?php endif; ?>

        <form method="post" autocomplete="on">
            <!-- Ô nhập Tài khoản -->
            <div class="auth-form-group">
                <label for="tai_khoan">
                    Tên đăng nhập <span class="bat-buoc">*</span>
                </label>
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-user field-icon"></i>
                    <input type="text" id="tai_khoan" name="tai_khoan" value="<?= e($tenDangNhap) ?>" 
                           placeholder="Ví dụ: admin hoặc tên của bạn" required autofocus autocomplete="username">
                </div>
            </div>

            <!-- Ô nhập Mật khẩu -->
            <div class="auth-form-group">
                <label for="mat_khau">
                    Mật khẩu <span class="bat-buoc">*</span>
                </label>
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-lock field-icon"></i>
                    <input type="password" id="mat_khau" name="mat_khau" 
                           placeholder="Nhập mật khẩu của bạn" required autocomplete="current-password">
                </div>
            </div>

            <button type="submit" class="auth-submit-btn">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng Nhập Ngay
            </button>
        </form>
    </div>
</div>

<?php require 'layouts/footer.php'; ?>
