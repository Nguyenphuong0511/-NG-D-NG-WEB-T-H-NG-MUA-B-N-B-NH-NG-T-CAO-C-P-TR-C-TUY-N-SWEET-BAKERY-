<?php
// ===== QUẢN TRỊ: THÊM SẢN PHẨM (có tải ảnh) =====
require 'init.php';
Auth::yeuCauAdmin();

$sanPham = new SanPham();   // đối tượng rỗng, chưa có id
$loi = [];

if (TaiAnhSanPham::vuotQuaGioiHanPost()) {
    $loi[] = 'File gửi lên quá lớn, máy chủ không nhận được dữ liệu. Hãy chọn ảnh nhỏ hơn.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO 26: Thêm sản phẩm có ảnh
    // 1. Kiểm tra các ô chữ
    $sanPham->ganDuLieu($_POST);
    $loi = $sanPham->kiemTraHopLe();

    // 2. CHỈ khi $loi rỗng và người dùng có chọn ảnh thì mới lưu ảnh
    $file = $_FILES['anh_san_pham'] ?? null;
    if (empty($loi) && TaiAnhSanPham::coChonFile($file)) {
        $taiAnh = new TaiAnhSanPham();
        $tenFile = $taiAnh->luu($file);
        if ($tenFile === null) {
            $loi[] = $taiAnh->loi();
        } else {
            $sanPham->anh_san_pham = $tenFile;
        }
    }

    // 3. Nếu $loi vẫn rỗng: lưu và chuyển trang
    if (empty($loi)) {
        $sanPham->save();
        header('Location: san_pham_admin.php');
        exit;
    }
}

$tieuDe = 'Thêm sản phẩm';
$nutBam = 'Thêm mới';
require 'layouts/header_admin.php';
?>
<h1>Thêm một sản phẩm mới</h1>
<?php require 'layouts/form_san_pham.php'; ?>
<?php require 'layouts/footer.php'; ?>
