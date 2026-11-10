<?php
// ===== QUẢN TRỊ: SỬA SẢN PHẨM (có thể thay ảnh) =====
require 'init.php';
Auth::yeuCauAdmin();

$sanPham = SanPham::find((int) ($_GET['id'] ?? 0));
if ($sanPham === null) {
    die('Không tìm thấy sản phẩm. <a href="san_pham_admin.php">Quay lại</a>');
}

$loi = [];
if (TaiAnhSanPham::vuotQuaGioiHanPost()) {
    $loi[] = 'File gửi lên quá lớn, máy chủ không nhận được dữ liệu. Hãy chọn ảnh nhỏ hơn.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO 27: Sửa sản phẩm, có thể thay ảnh
    // 1. Nhớ ảnh cũ trước khi gán dữ liệu mới
    $anhCu = $sanPham->anh_san_pham;

    // 2. Làm giống bước 1, 2 của TODO 26
    $sanPham->ganDuLieu($_POST);
    $loi = $sanPham->kiemTraHopLe();

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

    // 3. Nếu $loi rỗng: lưu và xóa ảnh cũ nếu đã thay
    if (empty($loi)) {
        $sanPham->save();
        if ($anhCu !== $sanPham->anh_san_pham) {
            TaiAnhSanPham::xoa($anhCu);
        }
        header('Location: san_pham_admin.php');
        exit;
    }
}

$tieuDe = 'Sửa sản phẩm';
$nutBam = 'Cập nhật';
require 'layouts/header_admin.php';
?>
<h1>Sửa thông tin sản phẩm</h1>
<?php require 'layouts/form_san_pham.php'; ?>
<?php require 'layouts/footer.php'; ?>
