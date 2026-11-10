<?php
// ===== QUẢN TRỊ: XOÁ ĐƠN HÀNG =====
// Chỉ xoá được đơn ĐÃ HUỶ. Đơn đang xử lý hoặc đã giao phải giữ lại để đối soát;
// muốn bỏ một đơn đang xử lý thì chuyển sang trạng thái "Đã huỷ".
require 'init.php';
Auth::yeuCauQuanTri();

// TODO 31: Xoá đơn hàng - CHỈ đơn ở trạng thái "Đã huỷ"
$donHang = DonHang::find((int) ($_GET['id'] ?? 0));
if ($donHang !== null && $donHang->trang_thai === TrangThaiDonHang::DA_HUY) {
    $donHang->delete();
}

header('Location: don_hang_admin.php');
exit;
