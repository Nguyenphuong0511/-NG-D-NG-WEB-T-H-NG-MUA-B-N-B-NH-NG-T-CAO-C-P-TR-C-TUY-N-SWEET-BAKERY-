<?php
// ===== TRANG CỬA HÀNG BÁNH NGỌT (dành cho khách) - có giỏ hàng =====
require 'init.php';

// TODO 21: Lấy danh sách sản phẩm
$dsSanPham = SanPham::all();

$tieuDe = 'Sweet Bakery - Menu Bánh Ngọt Tươi Cao Cấp';
require 'layouts/header_guest.php';
?>

<?php if (isset($_GET['chao_mung'])): ?>
    <div style="max-width: 1240px; margin: 15px auto -10px; padding: 12px 20px; background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; border-radius: 8px; display: flex; align-items: center; gap: 10px; font-weight: 500;">
        <i class="fa-solid fa-circle-check" style="font-size: 18px; color: #10B981;"></i>
        <span>Chúc mừng <b><?= e(Auth::tenNguoiDung()) ?></b> đã đăng ký tài khoản khách hàng thành công! Chúc bạn có trải nghiệm mua sắm ngọt ngào tại Sweet Bakery.</span>
    </div>
<?php endif; ?>

<!-- BANNER QUẢNG CÁO (HERO SECTION) -->
<section class="hero-banner">
    <div class="hero-content">
        <span class="hero-tag"><i class="fa-solid fa-sparkles"></i> Nướng Mới Mỗi Sáng</span>
        <h1 class="hero-title">Bánh Ngọt Tươi Mỗi Ngày <br><span class="hero-highlight">Ngọt Ngào Trọn Yêu Thương</span></h1>
        <p class="hero-desc">Được chế biến thủ công từ bơ sữa nguyên chất và nguyên liệu nhập khẩu Pháp thượng hạng. Khám phá hương vị mềm mịn, ngọt ngào khó cưỡng.</p>
        <div class="hero-actions">
            <a href="#thuc-don" class="nut nut-hero"><i class="fa-solid fa-cake-candles"></i> Xem Thực Đơn Bánh</a>
            <a href="#thuc-don" class="nut nut-hero-outline"><i class="fa-solid fa-fire"></i> Món Bán Chạy Nhất</a>
        </div>
    </div>
    <div class="hero-decor">
        <div class="hero-badge-float">
            <i class="fa-solid fa-award"></i>
            <div>
                <b>100% Tự Nhiên</b>
                <span>Không chất bảo quản</span>
            </div>
        </div>
    </div>
</section>

<!-- KHU VỰC CỬA HÀNG -->
<div class="cua-hang-container" id="thuc-don">
    <div class="cua-hang">
        <!-- Vùng danh sách sản phẩm Grid -->
        <section class="vung-san-pham">
            <div class="section-header">
                <div>
                    <h2 class="section-title"><i class="fa-solid fa-cookie-bite"></i> Menu Bánh Ngọt Hôm Nay</h2>
                    <p class="section-sub">Đa dạng từ bánh mì mềm hoa cúc, croissant giòn rụm đến mousse lạnh và tiramisu</p>
                </div>
                <div class="menu-filter-tags">
                    <span class="tag-pill active">Tất cả (<?= count($dsSanPham) ?>)</span>
                </div>
            </div>

            <div class="danh-sach">
                <?php foreach ($dsSanPham as $sp): ?>
                    <?php $dsAnh = $sp->tatCaAnh(); ?>
                    <div class="the-san-pham"
                         data-id="<?= $sp->id ?>"
                         data-name="<?= e($sp->ten_san_pham) ?>"
                         data-price="<?= (int) $sp->gia_san_pham ?>">
                        
                        <!-- Khung hình ảnh có zoom hover -->
                        <div class="khung-anh-sp">
                            <img src="<?= e($sp->urlAnh()) ?>" alt="<?= e($sp->ten_san_pham) ?>" class="anh-chinh" id="anh-chinh-<?= $sp->id ?>">
                            <span class="badge-tag">Tươi mới</span>
                        </div>

                        <!-- Danh sách ảnh phụ thu nhỏ để khách xem chi tiết (Mỗi sản phẩm có nhiều ảnh) -->
                        <?php if (count($dsAnh) > 1): ?>
                            <div class="album-anh-phu" title="Bấm vào để xem thêm góc chụp khác">
                                <?php foreach ($dsAnh as $idx => $anhItem): ?>
                                    <img src="<?= e(TaiAnhSanPham::url($anhItem)) ?>" 
                                         alt="Ảnh <?= $idx + 1 ?>" 
                                         class="thumb-anh <?= $idx === 0 ? 'active' : '' ?>"
                                         onclick="doiAnhChinh(<?= $sp->id ?>, '<?= e(TaiAnhSanPham::url($anhItem)) ?>', this)">
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="album-anh-phu empty"></div>
                        <?php endif; ?>
                        <div class="thong-tin-sp">
                            <span class="ma-sp"><i class="fa-solid fa-tag"></i> <?= e($sp->ma_san_pham) ?></span>
                            <h3 class="ten" title="<?= e($sp->ten_san_pham) ?>"><?= e($sp->ten_san_pham) ?></h3>
                            <div class="gia-sp"><?= number_format($sp->gia_san_pham) ?> <span class="don-vi">đ / bánh</span></div>
                        </div>
                        <div class="thao-tac-sp">
                            <div class="chon-so-luong">
                                <button type="button" onclick="doiSoLuong(this, -1)">-</button>
                                <span class="so-luong">1</span>
                                <button type="button" onclick="doiSoLuong(this, 1)">+</button>
                            </div>
                            <button type="button" class="nut nut-chinh nut-them" onclick="themVaoGio(this)">
                                <i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Giỏ hàng bên phải (Sticky) -->
        <aside class="gio-hang" id="gio-hang-box">
            <div class="gio-hang-header">
                <h2><i class="fa-solid fa-basket-shopping"></i> Giỏ Hàng</h2>
                <span class="gio-hang-subtitle">Đơn đặt của bạn</span>
            </div>
            
            <ul id="ds-gio-hang"></ul>
            
            <div id="gio-hang-trong" class="gio-hang-trong">
                <i class="fa-solid fa-cookie icon-trong"></i>
                <p>Giỏ hàng đang trống.<br>Hãy chọn chiếc bánh thơm ngon bạn thích nhé!</p>
            </div>

            <div class="gio-hang-footer">
                <div class="dong-tong-tien">
                    <span>Tổng tạm tính:</span>
                    <span class="tong-tien"><b id="tong-tien">0</b> đ</span>
                </div>
                <button type="button" class="nut nut-chinh nut-rong nut-mua" onclick="kiemTraVaMoXacNhan()">
                    <i class="fa-solid fa-check"></i> TIẾN HÀNH ĐẶT HÀNG
                </button>
                <?php if (!Auth::daDangNhap()): ?>
                    <p style="text-align: center; margin: 8px 0 0; font-size: 12px; color: #B45309; font-weight: 500;">
                        <i class="fa-solid fa-lock"></i> Đăng nhập để hoàn tất đặt hàng
                    </p>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>

<!-- Hộp thoại xác nhận đơn hàng Modal -->
<div class="hop-thoai" id="hop-thoai-dat-hang">
    <form class="hop-thoai-noi-dung" action="create_don_hang.php" method="post" onsubmit="return guiGioHang();">
        <button type="button" class="nut-dong" onclick="dongXacNhan()">×</button>
        <div class="modal-header">
            <i class="fa-solid fa-receipt modal-icon"></i>
            <div>
                <h2>Xác nhận Đặt Bánh</h2>
                <p class="modal-sub">Vui lòng kiểm tra đơn hàng và điền địa chỉ giao bánh</p>
            </div>
        </div>

        <div class="tom-tat-khung">
            <h4><i class="fa-solid fa-list-check"></i> Danh sách bánh đã chọn:</h4>
            <div id="tom-tat-don-hang"></div>
        </div>

        <div class="form-giao-hang">
            <h4><i class="fa-solid fa-truck-fast"></i> Thông tin nhận bánh</h4>
            <label>Họ và tên người nhận <span class="bat-buoc">*</span>
                <input type="text" name="ho_ten" value="<?= e(Auth::tenNguoiDung()) ?>" required placeholder="Ví dụ: Nguyễn Văn An">
            </label>
            <label>Số điện thoại liên hệ <span class="bat-buoc">*</span>
                <input type="tel" name="so_dien_thoai" required pattern="0[0-9 .\-]{9,13}" placeholder="Ví dụ: 0912345678" title="Số điện thoại bắt đầu bằng 0, gồm 10-11 chữ số">
            </label>
            <label>Địa chỉ nhận bánh chi tiết <span class="bat-buoc">*</span>
                <input type="text" name="dia_chi" required placeholder="Số nhà, ngõ, tên đường, phường/xã, quận/huyện">
            </label>
            <label>Ghi chú đơn hàng (không bắt buộc)
                <textarea name="ghi_chu" rows="2" maxlength="500" placeholder="VD: Giao trước 11h trưa, kèm nến sinh nhật..."></textarea>
            </label>
        </div>

        <!-- Ô ẩn chứa giỏ hàng dạng JSON -->
        <input type="hidden" name="cart_json" id="cart_json">

        <div class="modal-actions">
            <button type="button" class="nut nut-phu" onclick="dongXacNhan()">Chọn thêm bánh</button>
            <button type="submit" class="nut nut-chinh nut-xac-nhan"><i class="fa-solid fa-paper-plane"></i> ĐẶT HÀNG NGAY</button>
        </div>
    </form>
</div>

<script>
// Trạng thái đăng nhập của khách hàng
var daDangNhap = <?= Auth::daDangNhap() ? 'true' : 'false' ?>;

function kiemTraVaMoXacNhan() {
    if (gioHang.length === 0) {
        alert('Giỏ hàng đang trống! Vui lòng chọn bánh trước khi tiến hành đặt hàng.');
        return;
    }
    if (!daDangNhap) {
        if (confirm('Bạn cần đăng nhập tài khoản để tiến hành đặt hàng bánh ngọt.\nNhấn "OK" để chuyển đến trang Đăng nhập ngay.')) {
            window.location.href = 'login.php?yeu_cau_dang_nhap=1';
        }
        return;
    }
    moXacNhan();
}

// Đổi ảnh chính khi người dùng click vào các ảnh phụ
function doiAnhChinh(spId, urlMoi, thumbEl) {
    const anhChinh = document.getElementById('anh-chinh-' + spId);
    if (anhChinh) {
        anhChinh.src = urlMoi;
    }
    // Cập nhật trạng thái active cho thumbnail
    const parent = thumbEl.parentElement;
    if (parent) {
        parent.querySelectorAll('.thumb-anh').forEach(el => el.classList.remove('active'));
        thumbEl.classList.add('active');
    }
}
</script>

<?php require 'layouts/footer.php'; ?>
