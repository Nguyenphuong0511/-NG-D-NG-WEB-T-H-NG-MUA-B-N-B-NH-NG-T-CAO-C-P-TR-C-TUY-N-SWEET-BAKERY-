<?php
// ===== QUẢN TRỊ: XOÁ SẢN PHẨM =====
require 'init.php';
Auth::yeuCauAdmin();

// TODO 28: Xoá sản phẩm và file ảnh của nó
$sanPham = SanPham::find((int) ($_GET['id'] ?? 0));
if ($sanPham !== null) {
    $anhSanPham = $sanPham->anh_san_pham;
    $sanPham->delete();
    TaiAnhSanPham::xoa($anhSanPham);
}

header('Location: san_pham_admin.php');
exit;
