<?php
// ===== QUẢN TRỊ: DANH SÁCH ĐƠN HÀNG =====
require 'init.php';
Auth::yeuCauQuanTri();

// Bộ lọc trên địa chỉ: ?trang_thai=dang_giao&tu_khoa=0912
$trangThai = $_GET['trang_thai'] ?? '';
if (!TrangThaiDonHang::hopLe($trangThai)) {
    $trangThai = '';   // giá trị lạ -> xem tất cả
}
$tuKhoa = trim($_GET['tu_khoa'] ?? '');

// TODO 29: Lấy dữ liệu cho trang danh sách đơn hàng
$dsDonHang          = DonHang::timKiem($trangThai, $tuKhoa);
$soDonTheoTrangThai = DonHang::demTheoTrangThai();
$tongSoDon = array_sum($soDonTheoTrangThai);

$tieuDe = 'Danh sách đơn hàng';
require 'layouts/header_admin.php';
?>
<h1>Quản lý đơn hàng</h1>

<div class="the-thong-ke">
    <div><span>Tổng số đơn</span><b><?= $tongSoDon ?></b></div>
    <div><span>Chờ xác nhận</span><b><?= $soDonTheoTrangThai[TrangThaiDonHang::CHO_XAC_NHAN] ?></b></div>
    <div><span>Đang giao</span><b><?= $soDonTheoTrangThai[TrangThaiDonHang::DANG_GIAO] ?></b></div>
    <div><span>Doanh thu (đơn đã giao)</span><b><?= number_format(DonHang::doanhThu()) ?> đ</b></div>
</div>

<!-- Lọc theo trạng thái -->
<nav class="tab-loc">
    <a href="don_hang_admin.php?tu_khoa=<?= urlencode($tuKhoa) ?>"
       class="<?= $trangThai === '' ? 'dang-chon' : '' ?>">Tất cả (<?= $tongSoDon ?>)</a>
    <?php foreach (TrangThaiDonHang::tatCa() as $ma => $nhan): ?>
        <a href="don_hang_admin.php?trang_thai=<?= $ma ?>&tu_khoa=<?= urlencode($tuKhoa) ?>"
           class="<?= $trangThai === $ma ? 'dang-chon' : '' ?>"><?= e($nhan) ?> (<?= $soDonTheoTrangThai[$ma] ?>)</a>
    <?php endforeach; ?>
</nav>

<!-- Tìm kiếm -->
<form method="get" class="form-ngang">
    <input type="hidden" name="trang_thai" value="<?= e($trangThai) ?>">
    <input type="text" name="tu_khoa" value="<?= e($tuKhoa) ?>" placeholder="Tìm theo mã đơn, họ tên, số điện thoại">
    <button type="submit" class="nut nut-chinh">Tìm</button>
    <?php if ($tuKhoa !== ''): ?><a href="don_hang_admin.php?trang_thai=<?= e($trangThai) ?>" class="nut">Bỏ tìm</a><?php endif; ?>
</form>

<?php if (empty($dsDonHang)): ?>
    <p class="giua">Không có đơn hàng nào.</p>
<?php else: ?>
    <table class="bang">
        <tr>
            <th>Mã đơn</th>
            <th>Ngày đặt</th>
            <th>Khách hàng</th>
            <th>Điện thoại</th>
            <th class="so">Số SP</th>
            <th class="so">Tổng tiền</th>
            <th>Trạng thái</th>
            <th></th>
        </tr>
        <?php foreach ($dsDonHang as $don): ?>
            <tr>
                <td><a href="chi_tiet_don_hang.php?id=<?= $don->id ?>"><b><?= e($don->ma_don_hang) ?></b></a></td>
                <td><?= date('d/m/Y H:i', strtotime($don->ngay_dat)) ?></td>
                <td><?= e($don->ho_ten) ?></td>
                <td><?= e($don->so_dien_thoai) ?></td>
                <td class="so"><?= $don->tong_so_luong ?></td>
                <td class="so"><?= number_format($don->tong_tien) ?> đ</td>
                <td><span class="<?= $don->lopCssTrangThai() ?>"><?= e($don->nhanTrangThai()) ?></span></td>
                <td><a href="chi_tiet_don_hang.php?id=<?= $don->id ?>">Xem chi tiết</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<?php require 'layouts/footer.php'; ?>
