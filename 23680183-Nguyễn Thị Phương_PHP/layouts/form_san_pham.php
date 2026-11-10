<?php
// Form dùng chung cho trang THÊM và SỬA sản phẩm.
// Biến cần có trước khi include: $sanPham (đối tượng SanPham), $loi (mảng lỗi), $nutBam (chữ trên nút)
$laTrangSua = $sanPham->id !== null;
?>
<?php if (!empty($loi)): ?>
    <ul class="thong-bao-loi">
        <?php foreach ($loi as $thongBao): ?>
            <li><?= e($thongBao) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- enctype="multipart/form-data" BẮT BUỘC khi form có ô chọn file, thiếu nó thì $_FILES luôn rỗng -->
<form method="post" class="form" enctype="multipart/form-data">
    <label>Mã sản phẩm
        <input type="text" name="ma_san_pham" value="<?= e($sanPham->ma_san_pham) ?>" required>
    </label>
    <label>Tên sản phẩm
        <input type="text" name="ten_san_pham" value="<?= e($sanPham->ten_san_pham) ?>" required>
    </label>
    <label>Giá sản phẩm (đồng)
        <input type="number" name="gia_san_pham" min="0" value="<?= e($sanPham->gia_san_pham) ?>" required>
    </label>

    <div class="chon-anh">
        <img id="xem-truoc-anh" src="<?= e($sanPham->urlAnh()) ?>" alt="Ảnh sản phẩm">
        <div>
            <label>Ảnh sản phẩm
                <input type="file" name="anh_san_pham" accept="image/jpeg,image/png,image/gif,image/webp"
                       onchange="xemTruocAnh(this)">
            </label>
            <small class="muted">
                JPG, PNG, GIF hoặc WEBP, tối đa <?= TaiAnhSanPham::dungLuongToiDaMB() ?> MB.
                <?php if ($laTrangSua): ?>
                    <br>Bỏ trống nếu muốn giữ ảnh hiện tại. Chọn ảnh mới thì ảnh cũ sẽ được thay thế.
                <?php endif; ?>
            </small>
        </div>
    </div>

    <button type="submit" class="nut nut-chinh"><?= e($nutBam) ?></button>
    <a href="san_pham_admin.php" class="nut">Quay lại</a>
</form>
