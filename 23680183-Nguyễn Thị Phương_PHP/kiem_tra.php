<?php
// ======================================================
// TRANG TỰ KIỂM TRA - mở http://localhost/Project_demo_OOP_KhungMau/kiem_tra.php
// Kiểm tra các LỚP (TODO 1 -> 20). Làm xong TODO nào thì tải lại trang này.
// Các bài kiểm tra tự tạo dữ liệu thử rồi tự xoá, không làm bẩn CSDL.
// KHÔNG cần sửa file này.
// ======================================================
require 'init.php';

$ketQua = [];

/** Chạy một bài kiểm tra. $ham trả về true nếu đúng, hoặc một câu mô tả lỗi nếu sai. */
function kiemTra($phan, $todo, $moTa, $ham)
{
    global $ketQua;
    try {
        $kq = $ham();
        $ketQua[] = [$phan, $todo, $moTa, $kq === true, $kq === true ? 'Đạt' : (string) $kq];
    } catch (Throwable $e) {
        $ketQua[] = [$phan, $todo, $moTa, false,
            'Lỗi: ' . $e->getMessage() . ' (dòng ' . $e->getLine() . ' trong ' . basename($e->getFile()) . ')'];
    }
}

/** Tạo một SanPham trong bộ nhớ (chưa lưu) */
function spThu($ma, $ten, $gia, $anh = '')
{
    $sp = new SanPham();
    $sp->ma_san_pham = $ma;
    $sp->ten_san_pham = $ten;
    $sp->gia_san_pham = $gia;
    $sp->anh_san_pham = $anh;
    return $sp;
}

// ======================= PHẦN A, B, C =======================
kiemTra('A', '1', 'Database::getConnection() trả về PDO và dùng lại một kết nối', function () {
    $pdo = Database::getConnection();
    if (!($pdo instanceof PDO)) return 'Hàm đang trả về ' . gettype($pdo) . ', cần trả về đối tượng PDO.';
    if (Database::getConnection() !== $pdo) return 'Mỗi lần gọi lại tạo kết nối mới - cần dùng lại self::$connection.';
    return true;
});

kiemTra('B', '2', 'SanPham::all() trả về mảng đối tượng SanPham', function () {
    $ds = SanPham::all();
    if (!is_array($ds) || count($ds) === 0) return 'Danh sách rỗng. Đã import database/project_demo.sql chưa?';
    if (!($ds[0] instanceof SanPham)) return 'Phần tử không phải đối tượng SanPham - kiểm tra PDO::FETCH_CLASS, static::class.';
    return true;
});

kiemTra('B', '2', 'DonHang::all() đọc đúng bảng don_hang (kế thừa + static::$table)', function () {
    $ds = DonHang::all();
    $soDong = (int) Database::getConnection()->query('SELECT COUNT(*) FROM don_hang')->fetchColumn();
    if (!is_array($ds) || count($ds) !== $soDong) return "Bảng don_hang có $soDong dòng nhưng all() trả về " . (is_array($ds) ? count($ds) : 'không phải mảng') . '.';
    return true;
});

kiemTra('B', '3', 'find($id) tìm thấy sản phẩm, find(-1) trả về null', function () {
    $ds = SanPham::all();
    if (count($ds) === 0) return 'Cần làm TODO 2 trước.';
    if (!(SanPham::find($ds[0]->id) instanceof SanPham)) return 'Không tìm thấy sản phẩm id = ' . $ds[0]->id . '.';
    return SanPham::find(-1) === null ? true : 'Không thấy thì phải trả về null.';
});

kiemTra('C', '6a', 'SanPham khai báo đủ 4 thuộc tính của bảng', function () {
    foreach (['ma_san_pham', 'ten_san_pham', 'gia_san_pham', 'anh_san_pham'] as $tt) {
        if (!property_exists('SanPham', $tt)) return "Thiếu thuộc tính \$$tt.";
    }
    return true;
});

kiemTra('C', '6b', 'tinhThanhTien(3) với giá 11000 bằng 33000', function () {
    $kq = spThu('X', 'X', '11000')->tinhThanhTien(3);
    return $kq == 33000 ? true : "Kết quả đang là $kq.";
});

kiemTra('C', '7a', 'ganDuLieu() gán mã, tên, giá và bỏ khoảng trắng thừa', function () {
    $sp = spThu('', '', '', 'anh_cu.jpg');
    $sp->ganDuLieu(['ma_san_pham' => ' HH99 ', 'ten_san_pham' => 'Hoa thử', 'gia_san_pham' => '5000']);
    if ($sp->ma_san_pham !== 'HH99') return 'ma_san_pham đang là "' . $sp->ma_san_pham . '" (cần trim).';
    if ($sp->ten_san_pham !== 'Hoa thử' || $sp->gia_san_pham !== '5000') return 'Còn trường chưa được gán.';
    return $sp->anh_san_pham === 'anh_cu.jpg' ? true : 'ganDuLieu() không được đụng tới anh_san_pham.';
});

kiemTra('C', '7b', 'kiemTraHopLe() báo 3 lỗi khi dữ liệu sai, 0 lỗi khi đúng', function () {
    if (count(spThu('', '', 'abc')->kiemTraHopLe()) !== 3) return 'Dữ liệu sai cả 3 trường nhưng không báo đủ 3 lỗi.';
    if (count(spThu('X', 'X', '-1')->kiemTraHopLe()) !== 1) return 'Giá âm phải bị báo lỗi.';
    return count(spThu('X', 'X', '1000')->kiemTraHopLe()) === 0 ? true : 'Dữ liệu đúng nhưng vẫn báo lỗi.';
});

$spThu = null;
kiemTra('C', '4 + 8', 'save() với đối tượng mới: thêm vào CSDL và có id', function () use (&$spThu) {
    $spThu = spThu('TEST', 'Hoa kiểm tra', '1000');
    $spThu->save();
    if (empty($spThu->id)) return 'Sau save() đối tượng chưa có id - kiểm tra TODO 4 và lastInsertId() ở TODO 8.';
    $lai = SanPham::find($spThu->id);
    return ($lai !== null && $lai->ten_san_pham === 'Hoa kiểm tra') ? true : 'Không đọc lại được sản phẩm vừa thêm.';
});

kiemTra('C', '4 + 9', 'save() với đối tượng đã có id: cập nhật', function () use (&$spThu) {
    if ($spThu === null || empty($spThu->id)) return 'Cần làm TODO 4 + 8 trước.';
    $spThu->ten_san_pham = 'Hoa kiểm tra đã sửa';
    $spThu->save();
    return SanPham::find($spThu->id)->ten_san_pham === 'Hoa kiểm tra đã sửa' ? true : 'Tên trong CSDL chưa đổi.';
});

kiemTra('C', '5', 'delete() xoá sản phẩm thử khỏi CSDL', function () use (&$spThu) {
    if ($spThu === null || empty($spThu->id)) return 'Cần làm TODO 4 + 8 trước.';
    $spThu->delete();
    return SanPham::find($spThu->id) === null ? true : 'Sản phẩm vẫn còn trong CSDL.';
});

// ======================= PHẦN D =======================
kiemTra('D', '10a', "User::timTheoTenDangNhap('admin') tìm thấy, tên lạ trả về null", function () {
    if (!(User::timTheoTenDangNhap('admin') instanceof User)) return 'Không tìm thấy tài khoản admin.';
    return User::timTheoTenDangNhap('khong_ton_tai') === null ? true : 'Tên không tồn tại phải trả về null.';
});

kiemTra('D', '10b', 'kiemTraMatKhau() đúng với 123456, sai với mật khẩu khác', function () {
    $u = User::timTheoTenDangNhap('admin');
    if ($u === null) return 'Cần làm TODO 10a trước.';
    if ($u->kiemTraMatKhau('123456') !== true) return 'Mật khẩu đúng nhưng trả về false.';
    return $u->kiemTraMatKhau('sai') === false ? true : 'Mật khẩu sai nhưng trả về true.';
});

kiemTra('D', '11a', 'Auth::dangNhap() đúng/sai và ghi nhận vào session', function () {
    $saoLuu = $_SESSION;     // giữ lại phiên hiện tại của bạn
    $_SESSION = [];
    $sai = Auth::dangNhap('admin', 'sai');
    $dung = Auth::dangNhap('admin', '123456');
    $daGhiNhan = Auth::daDangNhap();
    $_SESSION = $saoLuu;     // trả lại như cũ
    if ($sai !== false) return 'Sai mật khẩu nhưng vẫn đăng nhập được.';
    if ($dung !== true) return 'Đúng mật khẩu nhưng không đăng nhập được.';
    return $daGhiNhan ? true : 'Chưa lưu user_id vào $_SESSION.';
});

// ======================= PHẦN E: ẢNH =======================
kiemTra('E', '12', 'luu(): ảnh lớn hơn 2 MB bị từ chối', function () {
    $t = new TaiAnhSanPham();
    $kq = $t->luu(['error' => UPLOAD_ERR_OK, 'size' => 3 * 1024 * 1024, 'tmp_name' => __FILE__, 'name' => 'a.jpg']);
    if ($kq !== null) return 'Phải trả về null.';
    return strpos($t->loi(), 'quá lớn') !== false ? true : 'Thông báo lỗi đang là: "' . $t->loi() . '"';
});

kiemTra('E', '12', 'luu(): file KHÔNG được tải lên qua form bị từ chối (is_uploaded_file)', function () {
    $t = new TaiAnhSanPham();
    $kq = $t->luu(['error' => UPLOAD_ERR_OK, 'size' => 100, 'tmp_name' => __FILE__, 'name' => 'a.jpg']);
    if ($kq !== null) return 'Nguy hiểm: hàm đã nhận một file không được tải lên qua form.';
    return strpos($t->loi(), 'không hợp lệ') !== false ? true : 'Thông báo lỗi đang là: "' . $t->loi() . '"';
});

kiemTra('E', '13', 'xoa(): xoá file ảnh không còn sản phẩm nào dùng', function () {
    $file = TaiAnhSanPham::THU_MUC . '/sp_kiemtra_xoa.txt';
    file_put_contents($file, 'thu');
    TaiAnhSanPham::xoa('sp_kiemtra_xoa.txt');
    $conFile = is_file($file);
    @unlink($file);
    return $conFile ? 'File vẫn còn, chưa bị xoá.' : true;
});

kiemTra('E', '13', 'xoa(): KHÔNG xoá ảnh đang có sản phẩm dùng', function () {
    $file = TaiAnhSanPham::THU_MUC . '/sp_kiemtra_giu.txt';
    file_put_contents($file, 'thu');
    $sp = spThu('TEST', 'Hoa giữ ảnh', '1', 'sp_kiemtra_giu.txt');
    $sp->save();
    if (empty($sp->id)) { @unlink($file); return 'Cần làm TODO 4 + 8 trước.'; }
    TaiAnhSanPham::xoa('sp_kiemtra_giu.txt');
    $conFile = is_file($file);
    if (!empty($sp->id)) $sp->delete();
    @unlink($file);
    return $conFile ? true : 'Đã xoá nhầm ảnh của một sản phẩm đang dùng - kiểm tra demSoSanPhamDungAnh().';
});

kiemTra('E', '13', "xoa('../...'): không xoá được file nằm NGOÀI thư mục Images", function () {
    // File mồi đặt ở thư mục gốc dự án, và một file cùng tên trong Images
    $moiNhu = __DIR__ . '/kiem_tra_moi_nhu.txt';
    $trongImages = TaiAnhSanPham::THU_MUC . '/kiem_tra_moi_nhu.txt';
    file_put_contents($moiNhu, 'file mồi');
    file_put_contents($trongImages, 'file trong Images');
    TaiAnhSanPham::xoa('../kiem_tra_moi_nhu.txt');
    $moiCon = is_file($moiNhu);
    $trongImagesCon = is_file($trongImages);
    @unlink($moiNhu);
    @unlink($trongImages);
    if (!$moiCon) return 'NGUY HIỂM: đã xoá được file ngoài thư mục Images. Thiếu basename() ở TODO 13.';
    return $trongImagesCon ? 'Chưa xoá file: basename() phải biến "../kiem_tra_moi_nhu.txt" thành "kiem_tra_moi_nhu.txt" trong Images.' : true;
});

// ======================= PHẦN F: GIỎ HÀNG =======================
$ds = [];
try { $ds = SanPham::all(); } catch (Throwable $e) {}
$spA = $ds[0] ?? null;
$spB = $ds[1] ?? null;

kiemTra('F', '14', 'MucGioHang::thanhTien() = giá × số lượng', function () {
    $muc = new MucGioHang(spThu('X', 'X', '11000'), 3);
    return $muc->thanhTien() == 33000 ? true : 'Kết quả đang là ' . $muc->thanhTien() . '.';
});

kiemTra('F', '15', 'GioHang::taoTuJson() lấy giá THẬT, bỏ mục sai và ghi cảnh báo', function () use ($spA) {
    if ($spA === null) return 'Cần làm TODO 2 trước.';
    $json = json_encode([
        ['id' => $spA->id, 'name' => 'Tên giả', 'price' => 1, 'qty' => 2],
        ['id' => 999999, 'qty' => 1],
        ['id' => $spA->id, 'qty' => 0],
    ]);
    $g = GioHang::taoTuJson($json);
    $dsMuc = $g->dsMuc();
    if (count($dsMuc) !== 1) return 'Giỏ phải có đúng 1 mục, đang có ' . count($dsMuc) . '.';
    if ($dsMuc[0]->sanPham->ten_san_pham !== $spA->ten_san_pham) return 'Tên sản phẩm phải lấy từ CSDL, không lấy từ JSON.';
    if ($dsMuc[0]->soLuong !== 2) return 'Số lượng đang là ' . $dsMuc[0]->soLuong . ', cần 2.';
    return count($g->canhBao()) === 2 ? true : 'Phải có 2 cảnh báo (sản phẩm không tồn tại, số lượng 0), đang có ' . count($g->canhBao()) . '.';
});

kiemTra('F', '16a', 'them() cùng một sản phẩm 2 lần thì cộng dồn số lượng', function () use ($spA) {
    if ($spA === null) return 'Cần làm TODO 2 trước.';
    $g = new GioHang();
    $g->them($spA, 2);
    $g->them($spA, 3);
    if (count($g->dsMuc()) !== 1) return 'Phải gộp thành 1 mục, đang có ' . count($g->dsMuc()) . ' mục.';
    return $g->dsMuc()[0]->soLuong === 5 ? true : 'Số lượng đang là ' . $g->dsMuc()[0]->soLuong . ', cần 5.';
});

kiemTra('F', '16b', 'tongTien() cộng thành tiền mọi mục', function () {
    $g = new GioHang();
    $a = spThu('A', 'A', '11000'); $a->id = -1;
    $b = spThu('B', 'B', '14000'); $b->id = -2;
    $g->them($a, 3);
    $g->them($b, 2);
    return $g->tongTien() == 61000 ? true : 'Tổng đang là ' . $g->tongTien() . ', cần 61000.';
});

// ======================= PHẦN G: ĐƠN HÀNG =======================
kiemTra('G', '17a', 'coTheChuyen(): được đi tiếp, không được quay lui', function () {
    if (!TrangThaiDonHang::coTheChuyen(TrangThaiDonHang::CHO_XAC_NHAN, TrangThaiDonHang::DA_GIAO)) return 'Chờ xác nhận -> Đã giao phải được phép.';
    if (TrangThaiDonHang::coTheChuyen(TrangThaiDonHang::DA_GIAO, TrangThaiDonHang::CHO_XAC_NHAN)) return 'Đã giao -> Chờ xác nhận phải bị cấm.';
    return true;
});

kiemTra('G', '17b', 'laKetThuc(): Đã giao, Đã huỷ là kết thúc; Chờ xác nhận thì chưa', function () {
    if (!TrangThaiDonHang::laKetThuc(TrangThaiDonHang::DA_GIAO) || !TrangThaiDonHang::laKetThuc(TrangThaiDonHang::DA_HUY)) return 'Đã giao và Đã huỷ phải là trạng thái kết thúc.';
    return TrangThaiDonHang::laKetThuc(TrangThaiDonHang::CHO_XAC_NHAN) ? 'Chờ xác nhận chưa phải trạng thái kết thúc.' : true;
});

kiemTra('G', '18', 'ChiTietDonHang::taoTuMucGioHang() chép đủ mã, tên, giá, số lượng, thành tiền', function () {
    $sp = spThu('HH01', 'Hoa Hồng', '11000');
    $sp->id = 6;
    $ct = ChiTietDonHang::taoTuMucGioHang(new MucGioHang($sp, 3), 123);
    if ($ct->don_hang_id !== 123 || $ct->san_pham_id !== 6) return 'Chưa gán don_hang_id hoặc san_pham_id.';
    if ($ct->ma_san_pham !== 'HH01' || $ct->ten_san_pham !== 'Hoa Hồng') return 'Chưa chép mã hoặc tên sản phẩm.';
    if ($ct->don_gia != 11000 || $ct->so_luong != 3) return 'Chưa gán đơn giá hoặc số lượng.';
    return $ct->thanh_tien == 33000 ? true : 'Thành tiền đang là ' . $ct->thanh_tien . '.';
});

$donThu = null;
kiemTra('G', '19', 'DonHang::taoTuGioHang() lưu đơn + chi tiết + lịch sử', function () use (&$donThu, $spA, $spB) {
    if ($spA === null || $spB === null) return 'Cần làm TODO 2 trước.';
    $g = new GioHang();
    $g->them($spA, 2);
    $g->them($spB, 1);
    $donThu = DonHang::taoTuGioHang($g, 'Kiểm tra tự động', '0900000000', 'Phòng máy', '');
    if (empty($donThu->id)) return 'Đơn hàng chưa được lưu (chưa có id).';
    $lai = DonHang::find($donThu->id);
    if (strpos((string) $lai->ma_don_hang, 'DH') !== 0) return 'Chưa sinh mã đơn hàng.';
    if (count($lai->chiTiet()) !== 2) return 'Đơn phải có 2 dòng chi tiết, đang có ' . count($lai->chiTiet()) . '.';
    if ((int) $lai->tong_tien !== $g->tongTien()) return 'Tổng tiền của đơn không khớp giỏ hàng.';
    if (count($lai->lichSu()) !== 1) return 'Phải ghi 1 dòng lịch sử khi tạo đơn.';
    return $lai->trang_thai === TrangThaiDonHang::CHO_XAC_NHAN ? true : 'Trạng thái ban đầu phải là "Chờ xác nhận".';
});

kiemTra('G', '20', 'chuyenTrangThai(): đổi trạng thái, ghi lịch sử, chặn chuyển sai', function () use (&$donThu) {
    if ($donThu === null || empty($donThu->id)) return 'Cần làm TODO 19 trước.';
    $donThu->chuyenTrangThai(TrangThaiDonHang::DA_GIAO, 'Kiểm tra', 'kiem_tra');
    $lai = DonHang::find($donThu->id);
    if ($lai->trang_thai !== TrangThaiDonHang::DA_GIAO) return 'Trạng thái trong CSDL chưa đổi.';
    if (count($lai->lichSu()) !== 2) return 'Chưa ghi thêm dòng lịch sử.';
    try {
        $lai->chuyenTrangThai(TrangThaiDonHang::CHO_XAC_NHAN, '', 'kiem_tra');
        return 'Chuyển từ "Đã giao" về "Chờ xác nhận" phải ném InvalidArgumentException.';
    } catch (InvalidArgumentException $e) {
        return true;
    }
});

// Dọn đơn hàng thử
if ($donThu !== null && !empty($donThu->id)) {
    try { $donThu->delete(); } catch (Throwable $e) {}
}

// ======================= KIỂM TRA TẢI ẢNH THẬT (TODO 12) =======================
$ketQuaTaiAnh = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['anh_thu'])) {
    $taiAnh = new TaiAnhSanPham();
    $tenFile = $taiAnh->luu($_FILES['anh_thu']);
    if ($tenFile === null) {
        $ketQuaTaiAnh = ['loi', $taiAnh->loi()];
    } else {
        $duongDan = TaiAnhSanPham::THU_MUC . '/' . $tenFile;
        $ok = is_file($duongDan);
        $ketQuaTaiAnh = $ok
            ? ['ok', 'Đã lưu thành công thành file "' . $tenFile . '" (' . filesize($duongDan) . ' byte). File thử đã được xoá.']
            : ['loi', 'Hàm trả về "' . $tenFile . '" nhưng không thấy file trong thư mục Images.'];
        @unlink($duongDan);
    }
}

$soDat = count(array_filter($ketQua, fn($k) => $k[3]));
$tieuDe = 'Tự kiểm tra';
require 'layouts/header_guest.php';
?>
<h1>Tự kiểm tra các lớp (TODO 1 – 20)</h1>
<p>Đạt <b><?= $soDat ?> / <?= count($ketQua) ?></b> bài kiểm tra.
   TODO 11b và TODO 21 – 31 (các trang) hãy kiểm tra bằng cách dùng thử website theo tài liệu hướng dẫn.</p>
<table class="bang">
    <tr><th>Phần</th><th>TODO</th><th>Nội dung kiểm tra</th><th>Kết quả</th></tr>
    <?php foreach ($ketQua as [$phan, $todo, $moTa, $dat, $thongBao]): ?>
        <tr>
            <td><?= e($phan) ?></td>
            <td><?= e($todo) ?></td>
            <td><?= e($moTa) ?></td>
            <td style="color: <?= $dat ? 'green' : '#c62828' ?>"><?= $dat ? '✔ ' : '✘ ' ?><?= e($thongBao) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Thử tải ảnh thật (TODO 12)</h2>
<p>Chọn một ảnh JPG/PNG để thử, sau đó thử thêm một file <i>không phải ảnh</i> đổi đuôi thành .jpg
   (ví dụ đổi <code>ghi_chu.txt</code> thành <code>ghi_chu.jpg</code>) – file đó phải bị từ chối.</p>
<?php if ($ketQuaTaiAnh !== null): ?>
    <p class="<?= $ketQuaTaiAnh[0] === 'ok' ? 'thong-bao-ok' : 'thong-bao-loi' ?>"><?= $ketQuaTaiAnh[0] === 'ok' ? '✔ ' : '✘ ' ?><?= e($ketQuaTaiAnh[1]) ?></p>
<?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form-ngang">
    <input type="file" name="anh_thu" required>
    <button type="submit" class="nut nut-chinh">Thử tải lên</button>
</form>
<?php require 'layouts/footer.php'; ?>
