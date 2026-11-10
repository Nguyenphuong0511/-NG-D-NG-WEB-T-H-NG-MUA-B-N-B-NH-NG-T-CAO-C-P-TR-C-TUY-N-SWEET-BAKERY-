</main>

<footer class="footer-bakery">
    <div class="footer-container">
        <div class="footer-col">
            <h3><i class="fa-solid fa-cake-candles"></i> Sweet Bakery</h3>
            <p>Hương vị ngọt ngào từ đôi bàn tay nghệ nhân bánh Pháp. Nguyên liệu tự nhiên cao cấp, nướng mới mỗi sáng.</p>
        </div>
        <div class="footer-col">
            <h4>Giờ mở cửa</h4>
            <p>Thứ 2 - Chủ Nhật: 07:00 - 22:00</p>
            <p>Giao hàng tận nơi trong 60 phút.</p>
        </div>
        <div class="footer-col">
            <h4>Liên hệ & Địa chỉ</h4>
            <p><i class="fa-solid fa-location-dot"></i> 123 Phố Tràng Thi, Hoàn Kiếm, Hà Nội</p>
            <p><i class="fa-solid fa-phone"></i> Hotline: 098.765.4321</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 Sweet Bakery - Đồ Án Chuyên Đề PHP OOP. All rights reserved.</p>
    </div>
</footer>

<script src="JS/project_demo.js"></script>
<script>
// Cập nhật số lượng giỏ hàng trên thanh header
function capNhatBadgeHeader() {
    try {
        const gio = JSON.parse(localStorage.getItem('gio_hang') || '[]');
        const tongSo = gio.reduce((sum, item) => sum + (parseInt(item.qty) || 0), 0);
        const badge = document.getElementById('header-cart-count');
        if (badge) badge.innerText = tongSo;
    } catch(e) {}
}
setInterval(capNhatBadgeHeader, 500);
document.addEventListener('DOMContentLoaded', capNhatBadgeHeader);

function cuonToiGioHang() {
    const el = document.querySelector('.gio-hang');
    if (el) el.scrollIntoView({ behavior: 'smooth' });
}
</script>
</body>
</html>
