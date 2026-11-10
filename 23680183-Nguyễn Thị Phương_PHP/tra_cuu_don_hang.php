<?php
// ===== KHÁCH TRA CỨU ĐƠN HÀNG =====
// Phải nhập đúng CẢ mã đơn và số điện thoại đặt hàng, để người khác không xem được đơn của mình.
require 'init.php';

// Hỗ trợ cả GET (tra cứu) và POST (hủy đơn)
$maDonHang = strtoupper(trim($_GET['ma_don_hang'] ?? $_POST['ma_don_hang'] ?? ''));
$soDienThoai = preg_replace('/[\s.\-]/', '', trim($_GET['so_dien_thoai'] ?? $_POST['so_dien_thoai'] ?? ''));

$donHang = null;
$thongBao = '';
if ($maDonHang !== '' || $soDienThoai !== '') {
    // TODO 23: Tìm đơn theo mã đơn VÀ số điện thoại
    $donHang = DonHang::timTheoMaVaSoDienThoai($maDonHang, $soDienThoai);
    if ($donHang === null) {
        $thongBao = 'Không tìm thấy đơn hàng. Hãy kiểm tra lại mã đơn và số điện thoại.';
    }
}

// Khách tự hủy đơn khi còn ở trạng thái Chờ xác nhận (Bài tập nâng cao 2)
$thongBaoHuy = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['huy_don_hang']) && $donHang !== null) {
    if ($donHang->trang_thai === TrangThaiDonHang::CHO_XAC_NHAN) {
        try {
            $donHang->chuyenTrangThai(TrangThaiDonHang::DA_HUY, 'Khách tự hủy đơn', 'Khách hàng');
            $thongBaoHuy = 'ok';
        } catch (Exception $e) {
            $thongBaoHuy = $e->getMessage();
        }
    } else {
        $thongBaoHuy = 'Chỉ có thể hủy đơn khi đơn đang ở trạng thái Chờ xác nhận.';
    }
}

$tieuDe = 'Tra cứu đơn hàng';
require 'layouts/header_guest.php';
?>
<div class="khung-giua">
    <h1>Tra cứu đơn hàng</h1>
    <form method="get" class="form-ngang">
        <label>Mã đơn hàng <input type="text" name="ma_don_hang" value="<?= e($maDonHang) ?>" placeholder="VD: DH2610100002" required></label>
        <label>Số điện thoại <input type="tel" name="so_dien_thoai" value="<?= e($soDienThoai) ?>" required></label>
        <button type="submit" class="nut nut-chinh">Tra cứu</button>
    </form>

    <?php if ($thongBao !== ''): ?>
        <p class="thong-bao-loi"><?= e($thongBao) ?></p>
    <?php endif; ?>

    <?php if ($donHang !== null): ?>
        <?php require 'layouts/don_hang_chi_tiet.php'; ?>

        <?php if ($thongBaoHuy === 'ok'): ?>
            <p class="thong-bao-ok">✔ Đơn hàng đã được hủy thành công.</p>
        <?php elseif ($thongBaoHuy !== ''): ?>
            <p class="thong-bao-loi"><?= e($thongBaoHuy) ?></p>
        <?php endif; ?>

        <?php if ($donHang->trang_thai === TrangThaiDonHang::CHO_XAC_NHAN && $thongBaoHuy !== 'ok'): ?>
            <form method="post" style="margin-top:1em">
                <input type="hidden" name="huy_don_hang" value="1">
                <input type="hidden" name="ma_don_hang" value="<?= e($maDonHang) ?>">
                <input type="hidden" name="so_dien_thoai" value="<?= e($soDienThoai) ?>">
                <button type="submit" class="nut nut-nguy-hiem"
                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">✕ Hủy đơn hàng</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php require 'layouts/footer.php'; ?>
