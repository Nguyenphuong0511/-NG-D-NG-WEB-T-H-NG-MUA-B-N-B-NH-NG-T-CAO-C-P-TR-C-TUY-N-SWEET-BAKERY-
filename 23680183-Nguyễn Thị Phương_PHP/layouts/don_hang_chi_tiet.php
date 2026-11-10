<?php
// Phần giao diện DÙNG CHUNG: thông tin một đơn hàng + bảng sản phẩm + lịch sử trạng thái.
// Dùng ở 3 trang: dat_hang_thanh_cong.php, tra_cuu_don_hang.php, chi_tiet_don_hang.php (quản trị).
// Biến cần có trước khi include: $donHang (DonHang). Tuỳ chọn: $hienNguoiThucHien (true ở trang quản trị).
$hienNguoiThucHien = $hienNguoiThucHien ?? false;
$dsChiTiet = $donHang->chiTiet();
$dsLichSu = $donHang->lichSu();
?>
<div class="luoi-thong-tin">
    <div>
        <h3>Đơn hàng</h3>
        <p>Mã đơn: <b><?= e($donHang->ma_don_hang) ?></b></p>
        <p>Ngày đặt: <?= date('d/m/Y H:i', strtotime($donHang->ngay_dat)) ?></p>
        <p>Trạng thái: <span class="<?= $donHang->lopCssTrangThai() ?>"><?= e($donHang->nhanTrangThai()) ?></span></p>
    </div>
    <div>
        <h3>Người nhận</h3>
        <p><b><?= e($donHang->ho_ten) ?></b></p>
        <p>Điện thoại: <?= e($donHang->so_dien_thoai) ?></p>
        <p>Địa chỉ: <?= e($donHang->dia_chi) ?></p>
        <?php if ($donHang->ghi_chu): ?>
            <p>Ghi chú: <i><?= e($donHang->ghi_chu) ?></i></p>
        <?php endif; ?>
    </div>
</div>

<h3>Sản phẩm</h3>
<table class="bang">
    <tr>
        <th>#</th>
        <th>Mã SP</th>
        <th>Tên sản phẩm</th>
        <th class="so">Đơn giá</th>
        <th class="so">Số lượng</th>
        <th class="so">Thành tiền</th>
    </tr>
    <?php foreach ($dsChiTiet as $stt => $ct): ?>
        <tr>
            <td><?= $stt + 1 ?></td>
            <td><?= e($ct->ma_san_pham) ?></td>
            <td><?= e($ct->ten_san_pham) ?></td>
            <td class="so"><?= number_format($ct->don_gia) ?> đ</td>
            <td class="so"><?= $ct->so_luong ?></td>
            <td class="so"><?= number_format($ct->thanh_tien) ?> đ</td>
        </tr>
    <?php endforeach; ?>
    <tr class="dong-tong">
        <td colspan="5">Tổng cộng</td>
        <td class="so"><?= number_format($donHang->tong_tien) ?> đ</td>
    </tr>
</table>

<h3>Lịch sử trạng thái</h3>
<ol class="lich-su">
    <?php foreach ($dsLichSu as $ls): ?>
        <li>
            <span class="thoi-gian"><?= date('d/m/Y H:i', strtotime($ls->thoi_gian)) ?></span>
            <span class="<?= TrangThaiDonHang::lopCss($ls->trang_thai_moi) ?>"><?= e(TrangThaiDonHang::nhan($ls->trang_thai_moi)) ?></span>
            <?php if ($ls->ghi_chu): ?> – <?= e($ls->ghi_chu) ?><?php endif; ?>
            <?php if ($hienNguoiThucHien): ?><span class="muted">(<?= e($ls->nguoi_thuc_hien) ?>)</span><?php endif; ?>
        </li>
    <?php endforeach; ?>
</ol>
