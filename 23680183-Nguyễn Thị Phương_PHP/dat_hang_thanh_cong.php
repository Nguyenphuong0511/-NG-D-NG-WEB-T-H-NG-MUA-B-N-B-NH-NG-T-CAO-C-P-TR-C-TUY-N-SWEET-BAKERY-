<?php
// ===== TRANG ĐẶT HÀNG THÀNH CÔNG =====
require 'init.php';

$donHang = DonHang::find((int) ($_SESSION['don_hang_vua_dat'] ?? 0));
if ($donHang === null) {
    header('Location: san_pham.php');
    exit;
}
$canhBao = $_SESSION['canh_bao_gio_hang'] ?? [];
unset($_SESSION['canh_bao_gio_hang']);   // cảnh báo chỉ hiện một lần

$tieuDe = 'Đặt hàng thành công';
require 'layouts/header_guest.php';
?>
<div class="khung-giua">
    <h1 class="tieu-de-xanh">BẠN ĐÃ ĐẶT HÀNG THÀNH CÔNG</h1>
    <p class="giua">Mã đơn hàng của bạn là <b class="ma-don"><?= e($donHang->ma_don_hang) ?></b>.
        Hãy ghi lại mã này để <a href="tra_cuu_don_hang.php">tra cứu đơn hàng</a>.</p>

    <?php foreach ($canhBao as $thongBao): ?>
        <p class="thong-bao-loi"><?= e($thongBao) ?></p>
    <?php endforeach; ?>

    <?php require 'layouts/don_hang_chi_tiet.php'; ?>

    <p class="giua">Chúng tôi sẽ liên hệ với bạn sớm nhất qua số điện thoại <?= e($donHang->so_dien_thoai) ?>.</p>
    <p class="giua"><a href="san_pham.php" class="nut nut-chinh">VỀ TRANG CHỦ</a></p>
</div>

<!-- Đặt hàng xong thì xoá giỏ hàng đang lưu trong trình duyệt -->
<script>try { localStorage.removeItem('gio_hang'); } catch (e) {}</script>
<?php require 'layouts/footer.php'; ?>
