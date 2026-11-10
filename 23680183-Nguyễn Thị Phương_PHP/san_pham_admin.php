<?php
// ===== QUẢN TRỊ: DANH SÁCH SẢN PHẨM =====
require 'init.php';
// TODO 25: Bảo vệ trang và lấy dữ liệu
Auth::yeuCauAdmin();
$dsSanPham = SanPham::all();

$tieuDe = 'Danh sách sản phẩm';
require 'layouts/header_admin.php';
?>
<h1>Danh sách sản phẩm</h1>
<p><a href="create_san_pham.php" class="nut nut-chinh">+ Thêm mới</a></p>
<table class="bang">
    <tr>
        <th>Mã SP</th>
        <th>Tên sản phẩm</th>
        <th>Giá sản phẩm</th>
        <th>Ảnh</th>
        <th>Thao tác</th>
    </tr>
    <?php foreach ($dsSanPham as $sp): ?>
        <tr>
            <td><?= e($sp->ma_san_pham) ?></td>
            <td><?= e($sp->ten_san_pham) ?></td>
            <td><?= number_format($sp->gia_san_pham) ?> đ</td>
            <td><img src="<?= e($sp->urlAnh()) ?>" alt="" class="anh-nho"></td>
            <td>
                <a href="edit_san_pham.php?id=<?= $sp->id ?>">Sửa</a> |
                <a href="delete_san_pham.php?id=<?= $sp->id ?>"
                   onclick="return confirm('Bạn có thực sự muốn xoá sản phẩm này?')">Xoá</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require 'layouts/footer.php'; ?>
