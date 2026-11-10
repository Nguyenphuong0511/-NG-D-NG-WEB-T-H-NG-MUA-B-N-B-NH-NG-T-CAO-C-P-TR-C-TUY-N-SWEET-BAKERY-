<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($tieuDe ?? 'Quản Trị Sweet Bakery') ?></title>
    <link rel="stylesheet" href="CSS/styles.css?v=<?= @filemtime(__DIR__ . '/../CSS/styles.css') ?: time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <!-- Sidebar Admin Trái Nền Tối Hiện Đại -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-cake-candles"></i>
            <div>
                <span class="brand-title">Sweet Bakery</span>
                <span class="brand-subtitle">Quản Trị Hệ Thống</span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-heading">QUẢN LÝ CỬA HÀNG</div>
            <?php if (Auth::laAdmin()): ?>
            <a href="san_pham_admin.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'san_pham_admin.php' || basename($_SERVER['PHP_SELF']) == 'create_san_pham.php' || basename($_SERVER['PHP_SELF']) == 'edit_san_pham.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-cookie-bite"></i> Quản lý Bánh Ngọt
            </a>
            <?php endif; ?>
            <a href="don_hang_admin.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'don_hang_admin.php' || basename($_SERVER['PHP_SELF']) == 'chi_tiet_don_hang.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-receipt"></i> Quản lý Đơn Hàng
            </a>
            <?php if (Auth::laAdmin()): ?>
            <a href="user_admin.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'user_admin.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear"></i> Tài khoản Admin
            </a>
            <?php endif; ?>

            <div class="menu-heading">HỆ THỐNG</div>
            <a href="san_pham.php" target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Tiệm Bánh
            </a>
            <?php if (Auth::laAdmin()): ?>
            <a href="kiem_tra.php" target="_blank">
                <i class="fa-solid fa-circle-check"></i> Kiểm tra 28 Test OOP
            </a>
            <?php endif; ?>
            <a href="logout.php" class="menu-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
            </a>
        </nav>

        <div class="sidebar-user">
            <i class="fa-solid fa-circle-user avatar-icon"></i>
            <div class="user-info">
                <span class="user-name"><?= e(Auth::tenNguoiDung()) ?></span>
                <span class="user-role"><?= Auth::laAdmin() ? 'Quản trị viên (Admin)' : 'Nhân viên xử lý đơn' ?></span>
            </div>
        </div>
    </aside>

    <!-- Khu vực nội dung bên phải -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-left">
                <h2><?= e($tieuDe ?? 'Bảng Điều Khiển') ?></h2>
            </div>
            <div class="topbar-right">
                <span class="time-badge"><i class="fa-regular fa-clock"></i> <?= date('d/m/Y H:i') ?></span>
                <a href="san_pham.php" class="nut nut-phu" target="_blank"><i class="fa-solid fa-store"></i> Đến cửa hàng</a>
            </div>
        </header>

        <div class="admin-content-area">
