<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($tieuDe ?? '🍰 Sweet Bakery - Tiệm Bánh Ngọt Tươi') ?></title>
    <link rel="stylesheet" href="CSS/styles.css?v=<?= @filemtime(__DIR__ . '/../CSS/styles.css') ?: time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- Top Notification Bar -->
<div class="top-bar">
    <span>🌟 Freeship đơn từ 200.000đ | Bánh ngọt nướng tươi mới mỗi ngày lúc 7:00 sáng! Hotline: 098.765.4321</span>
</div>

<header class="header-nav">
    <div class="header-container">
        <a href="san_pham.php" class="logo">
            <span class="logo-icon"><i class="fa-solid fa-cake-candles"></i></span>
            <div class="logo-text">
                <span class="brand-name">Sweet Bakery</span>
                <span class="brand-slogan">Tiệm Bánh Ngọt Pháp</span>
            </div>
        </a>

        <!-- Menu điều hướng chính của khách hàng (Đã loại bỏ tab Quản trị gây hiểu lầm) -->
        <nav class="nav-links">
            <a href="san_pham.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'san_pham.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-house"></i> Trang chủ
            </a>
            <a href="san_pham.php#thuc-don" class="nav-item">
                <i class="fa-solid fa-cookie-bite"></i> Thực đơn bánh
            </a>
            <a href="tra_cuu_don_hang.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'tra_cuu_don_hang.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-magnifying-glass"></i> Tra cứu đơn
            </a>
        </nav>

        <div class="header-right">
            <!-- Cụm đăng nhập/tài khoản viền tròn như nút giỏ hàng -->
            <?php if (!Auth::daDangNhap()): ?>
                <div class="auth-pill-group">
                    <a href="login.php" class="auth-pill-btn" title="Đăng nhập tài khoản">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        <span>Đăng nhập</span>
                    </a>
                    <a href="dang_ky.php" class="auth-pill-btn" style="background: #FFF3E0;" title="Đăng ký tài khoản khách hàng mới">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Đăng ký</span>
                    </a>
                </div>
            <?php elseif (Auth::laQuanTri()): ?>
                <!-- Tài khoản Admin / Nhân viên: hiển thị nút vào Quản trị và tên tài khoản -->
                <div class="auth-pill-group">
                    <a href="don_hang_admin.php" class="auth-pill-btn auth-pill-admin" title="Vào bảng điều khiển quản trị">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Quản trị</span>
                    </a>
                    <div class="auth-pill-btn auth-pill-user" title="Tài khoản quản trị">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span><?= e(Auth::tenNguoiDung()) ?></span>
                    </div>
                    <a href="logout.php" class="auth-pill-btn auth-pill-logout" title="Đăng xuất">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            <?php else: ?>
                <!-- Tài khoản Khách hàng: TUYỆT ĐỐI KHÔNG hiển thị tab Quản trị -->
                <div class="auth-pill-group">
                    <div class="auth-pill-btn auth-pill-user">
                        <i class="fa-solid fa-circle-user"></i>
                        <span>Chào, <b><?= e(Auth::tenNguoiDung()) ?></b></span>
                    </div>
                    <a href="logout.php" class="auth-pill-btn auth-pill-logout" title="Đăng xuất">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Thoát</span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Nút Giỏ Hàng viền tròn đồng bộ -->
            <a href="#gio-hang-box" class="cart-badge-btn" onclick="cuonToiGioHang()">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-label">Giỏ hàng</span>
                <span class="cart-count" id="header-cart-count">0</span>
            </a>
        </div>
    </div>
</header>

<main class="main-content">
