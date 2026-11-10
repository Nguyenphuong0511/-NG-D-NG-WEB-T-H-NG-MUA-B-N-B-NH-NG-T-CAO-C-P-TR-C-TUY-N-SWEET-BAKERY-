<?php
class Auth
{
    public static function dangNhap($tenDangNhap, $matKhau)
    {
        $user = User::timTheoTenDangNhap($tenDangNhap);
        if ($user !== null && $user->kiemTraMatKhau($matKhau)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['ten_dang_nhap'] = $user->ten_dang_nhap;
            $_SESSION['vai_tro'] = !empty($user->vai_tro) ? $user->vai_tro : 'khach_hang';
            return true;
        }
        return false;
    }

    public static function daDangNhap()
    {
        return isset($_SESSION['user_id']);
    }

    public static function tenNguoiDung()
    {
        return $_SESSION['ten_dang_nhap'] ?? '';
    }

    public static function vaiTro()
    {
        return $_SESSION['vai_tro'] ?? 'khach_hang';
    }

    /** Kiểm tra quyền Admin tối cao */
    public static function laAdmin()
    {
        return self::daDangNhap() && (self::tenNguoiDung() === 'admin' || self::vaiTro() === 'admin');
    }

    /** Kiểm tra quyền Quản trị (Admin hoặc Nhân viên) */
    public static function laQuanTri()
    {
        if (!self::daDangNhap()) {
            return false;
        }
        $vaiTro = self::vaiTro();
        $ten = self::tenNguoiDung();
        return ($vaiTro === 'admin' || $vaiTro === 'nhanvien' || $ten === 'admin' || (strpos($ten, 'nhanvien') === 0));
    }

    /** Kiểm tra là tài khoản khách hàng thông thường */
    public static function laKhachHang()
    {
        return self::daDangNhap() && !self::laQuanTri();
    }

    public static function yeuCauDangNhap()
    {
        if (!self::daDangNhap()) {
            header('Location: login.php');
            exit;
        }
    }

    /** Chặn khách hàng thường vào các trang quản trị */
    public static function yeuCauQuanTri()
    {
        self::yeuCauDangNhap();
        if (!self::laQuanTri()) {
            header('Location: san_pham.php');
            exit;
        }
    }

    public static function yeuCauAdmin()
    {
        self::yeuCauDangNhap();
        if (!self::laAdmin()) {
            header('Location: don_hang_admin.php');
            exit;
        }
    }

    public static function dangXuat()
    {
        $_SESSION = [];
        if (session_id() !== '') {
            session_destroy();
        }
    }
}
