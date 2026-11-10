<?php
// ===== QUẢN TRỊ: CHI TIẾT ĐƠN HÀNG + ĐỔI TRẠNG THÁI =====
require 'init.php';
Auth::yeuCauQuanTri();

$donHang = DonHang::find((int) ($_GET['id'] ?? 0));
if ($donHang === null) {
    die('Không tìm thấy đơn hàng. <a href="don_hang_admin.php">Quay lại</a>');
}

// Xử lý form đổi trạng thái
$loi = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trangThaiMoi = $_POST['trang_thai_moi'] ?? '';
    $ghiChu = trim($_POST['ghi_chu'] ?? '');

    if ($trangThaiMoi === TrangThaiDonHang::DA_HUY && $ghiChu === '') {
        $loi = 'Vui lòng ghi rõ lý do huỷ đơn.';
    } else {
        // TODO 30: Đổi trạng thái và bắt ngoại lệ
        try {
            $donHang->chuyenTrangThai($trangThaiMoi, mb_substr($ghiChu, 0, 255), Auth::tenNguoiDung());
            header('Location: chi_tiet_don_hang.php?id=' . $donHang->id . '&da_cap_nhat=1');
            exit;
        } catch (InvalidArgumentException $e) {
            $loi = $e->getMessage();
        }
    }
}

$hienNguoiThucHien = true;
$tieuDe = 'Đơn hàng ' . $donHang->ma_don_hang;
require 'layouts/header_admin.php';
?>
<div class="khong-in">
    <a href="don_hang_admin.php">← Danh sách đơn hàng</a>
</div>

<h1>Đơn hàng <?= e($donHang->ma_don_hang) ?></h1>

<?php if (isset($_GET['da_cap_nhat'])): ?>
    <p class="thong-bao-ok khong-in">Đã cập nhật trạng thái đơn hàng.</p>
<?php endif; ?>
<?php if ($loi !== ''): ?>
    <p class="thong-bao-loi khong-in"><?= e($loi) ?></p>
<?php endif; ?>

<?php require 'layouts/don_hang_chi_tiet.php'; ?>

<div class="khung-thao-tac khong-in">
    <?php if (TrangThaiDonHang::laKetThuc($donHang->trang_thai)): ?>
        <p>Đơn hàng ở trạng thái <span class="<?= $donHang->lopCssTrangThai() ?>"><?= e($donHang->nhanTrangThai()) ?></span>, không đổi trạng thái được nữa.</p>
    <?php else: ?>
        <h3>Cập nhật trạng thái</h3>

        <!-- Thanh tiến trình: các bước đã qua được tô màu -->
        <?php $daQua = true; ?>
        <ol class="tien-trinh">
            <?php foreach (TrangThaiDonHang::tatCa() as $ma => $nhan): ?>
                <?php if ($ma === TrangThaiDonHang::DA_HUY) continue; ?>
                <li class="<?= $ma === $donHang->trang_thai ? 'hien-tai' : ($daQua ? 'da-qua' : '') ?>"><?= e($nhan) ?></li>
                <?php if ($ma === $donHang->trang_thai) $daQua = false; ?>
            <?php endforeach; ?>
        </ol>

        <!-- Mỗi trạng thái được phép chuyển tới là MỘT nút; nút bấm gửi giá trị của nó qua trang_thai_moi -->
        <form method="post" class="form">
            <label>Ghi chú (bắt buộc khi huỷ đơn)
                <input type="text" name="ghi_chu" maxlength="255" placeholder="VD: Đã gọi xác nhận với khách">
            </label>
            <div class="nhom-nut">
                <?php foreach ($donHang->cacTrangThaiTiepTheo() as $ma): ?>
                    <?php if ($ma === TrangThaiDonHang::DA_HUY): ?>
                        <button type="submit" name="trang_thai_moi" value="<?= $ma ?>" class="nut nut-nguy-hiem"
                                onclick="return confirm('Huỷ đơn hàng này?')">✕ Huỷ đơn</button>
                    <?php else: ?>
                        <button type="submit" name="trang_thai_moi" value="<?= $ma ?>" class="nut nut-chinh">→ <?= e(TrangThaiDonHang::nhan($ma)) ?></button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </form>
    <?php endif; ?>

    <p>
        <button type="button" class="nut" onclick="window.print()">🖨 In đơn hàng</button>
        <?php if ($donHang->trang_thai === TrangThaiDonHang::DA_HUY): ?>
            <a href="delete_don_hang.php?id=<?= $donHang->id ?>" class="nut nut-nguy-hiem"
               onclick="return confirm('Xoá hẳn đơn hàng đã huỷ này? Không thể khôi phục.')">Xoá đơn đã huỷ</a>
        <?php endif; ?>
    </p>
</div>
<?php require 'layouts/footer.php'; ?>
