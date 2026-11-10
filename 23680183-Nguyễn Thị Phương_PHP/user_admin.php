<?php
// ===== QUẢN TRỊ: QUẢN LÝ TÀI KHOẢN ADMIN =====
// Yêu cầu nâng cao 2.2 trong kế hoạch & hướng dẫn đồ án
require 'init.php';
Auth::yeuCauAdmin();

$loi = '';
$thongBao = '';

// Xử lý thêm tài khoản mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['them_user'])) {
    $tenMoi = trim($_POST['ten_dang_nhap'] ?? '');
    $mkMoi = trim($_POST['mat_khau'] ?? '');
    $vaiTroMoi = trim($_POST['vai_tro'] ?? 'nhanvien');

    if ($tenMoi === '' || $mkMoi === '') {
        $loi = 'Vui lòng điền đầy đủ tên đăng nhập và mật khẩu.';
    } elseif (User::timTheoTenDangNhap($tenMoi) !== null) {
        $loi = 'Tên đăng nhập đã tồn tại trên hệ thống.';
    } else {
        $u = new User();
        $u->ten_dang_nhap = $tenMoi;
        $u->mat_khau = password_hash($mkMoi, PASSWORD_DEFAULT);
        $u->vai_tro = in_array($vaiTroMoi, ['admin', 'nhanvien', 'khach_hang'], true) ? $vaiTroMoi : 'nhanvien';
        $u->save();
        $thongBao = 'Đã tạo tài khoản mới thành công!';
    }
}

// Xử lý xóa tài khoản
if (isset($_GET['xoa'])) {
    $xoaId = (int) $_GET['xoa'];
    $uXoa = User::find($xoaId);
    if ($uXoa !== null) {
        // Không cho phép tự xóa tài khoản đang đăng nhập
        if ($uXoa->ten_dang_nhap === Auth::tenNguoiDung()) {
            $loi = 'Không thể xóa tài khoản bạn đang sử dụng để đăng nhập!';
        } else {
            $uXoa->delete();
            $thongBao = 'Đã xóa tài khoản quản trị thành công.';
        }
    }
}

$dsUser = User::all();

$tieuDe = 'Quản lý tài khoản quản trị viên';
require 'layouts/header_admin.php';
?>
<h1>👤 Quản lý tài khoản quản trị viên</h1>

<?php if ($thongBao !== ''): ?>
    <p class="thong-bao-ok">✔ <?= e($thongBao) ?></p>
<?php endif; ?>
<?php if ($loi !== ''): ?>
    <p class="thong-bao-loi">✘ <?= e($loi) ?></p>
<?php endif; ?>

<div style="display: flex; gap: 2rem; flex-wrap: wrap;">
    <!-- Form tạo tài khoản -->
    <div style="flex: 1; min-width: 300px;">
        <form method="post" class="form" style="margin: 0;">
            <h3>+ Thêm tài khoản người dùng</h3>
            <label>Tên đăng nhập
                <input type="text" name="ten_dang_nhap" required placeholder="VD: quanly_banh">
            </label>
            <label>Vai trò tài khoản
                <select name="vai_tro" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #CBD5E1; margin-top: 4px;">
                    <option value="nhanvien">Nhân viên (Xem & xử lý đơn hàng)</option>
                    <option value="admin">Quản trị viên (Toàn quyền hệ thống)</option>
                    <option value="khach_hang">Khách hàng (Đặt mua bánh)</option>
                </select>
            </label>
            <label>Mật khẩu (tự động mã hóa BCrypt)
                <input type="password" name="mat_khau" required placeholder="Tối thiểu 6 ký tự">
            </label>
            <button type="submit" name="them_user" value="1" class="nut nut-chinh">Tạo tài khoản</button>
        </form>
    </div>

    <!-- Danh sách tài khoản -->
    <div style="flex: 2; min-width: 320px;">
        <h3>Danh sách tài khoản hiện tại</h3>
        <table class="bang">
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Vai trò</th>
                <th>Bảo mật</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($dsUser as $u): ?>
                <tr>
                    <td><?= $u->id ?></td>
                    <td><b><?= e($u->ten_dang_nhap) ?></b> <?= ($u->ten_dang_nhap === Auth::tenNguoiDung()) ? '<span style="color:#2e7d32">(Đang đăng nhập)</span>' : '' ?></td>
                    <td>
                        <?php if (($u->vai_tro ?? '') === 'admin'): ?>
                            <span style="background: #FEE2E2; color: #991B1B; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 700;">Admin</span>
                        <?php elseif (($u->vai_tro ?? '') === 'nhanvien'): ?>
                            <span style="background: #E0E7FF; color: #3730A3; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 700;">Nhân viên</span>
                        <?php else: ?>
                            <span style="background: #FEF3C7; color: #92400E; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 700;">Khách hàng</span>
                        <?php endif; ?>
                    </td>
                    <td><?= strpos($u->mat_khau, '$2y$') === 0 ? 'BCrypt' : 'Plain' ?></td>
                    <td>
                        <?php if ($u->ten_dang_nhap !== Auth::tenNguoiDung()): ?>
                            <a href="user_admin.php?xoa=<?= $u->id ?>" class="nut nut-nguy-hiem"
                               onclick="return confirm('Bạn có chắc muốn xóa tài khoản này không?')">Xóa</a>
                        <?php else: ?>
                            <em>Không được xóa</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php require 'layouts/footer.php'; ?>

