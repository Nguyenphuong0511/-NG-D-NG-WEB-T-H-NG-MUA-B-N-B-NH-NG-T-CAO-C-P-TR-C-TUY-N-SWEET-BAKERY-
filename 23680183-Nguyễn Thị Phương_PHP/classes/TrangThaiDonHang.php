<?php

class TrangThaiDonHang
{
    const CHO_XAC_NHAN = 'cho_xac_nhan';
    const DA_XAC_NHAN  = 'da_xac_nhan';
    const DANG_GIAO    = 'dang_giao';
    const DA_GIAO      = 'da_giao';
    const HOAN_HANG    = 'hoan_hang';
    const DA_HUY       = 'da_huy';

    /** Tên hiển thị */
    private static $nhan = [
        self::CHO_XAC_NHAN => 'Chờ xác nhận',
        self::DA_XAC_NHAN  => 'Đã xác nhận',
        self::DANG_GIAO    => 'Đang giao',
        self::DA_GIAO      => 'Đã giao',
        self::HOAN_HANG    => 'Hoàn hàng',
        self::DA_HUY       => 'Đã huỷ',
    ];

    /**
     * Từ mỗi trạng thái được phép chuyển sang những trạng thái nào.
     */
    private static $buocTiep = [
        self::CHO_XAC_NHAN => [self::DA_XAC_NHAN, self::DANG_GIAO, self::DA_GIAO, self::DA_HUY],
        self::DA_XAC_NHAN  => [self::DANG_GIAO, self::DA_GIAO, self::DA_HUY],
        self::DANG_GIAO    => [self::DA_GIAO, self::HOAN_HANG, self::DA_HUY],
        self::DA_GIAO      => [self::HOAN_HANG], // Cho phép hoàn hàng nếu đã giao
        self::HOAN_HANG    => [], // Kết thúc
        self::DA_HUY       => [], // Kết thúc
    ];

    public static function tatCa()
    {
        return self::$nhan;
    }

    public static function hopLe($ma)
    {
        return isset(self::$nhan[$ma]);
    }

    public static function nhan($ma)
    {
        return self::$nhan[$ma] ?? 'Không rõ';
    }

    public static function lopCss($ma)
    {
        return 'trang-thai trang-thai-' . (self::hopLe($ma) ? $ma : 'khong-ro');
    }

    public static function buocTiep($ma)
    {
        return self::$buocTiep[$ma] ?? [];
    }

    public static function coTheChuyen($tu, $den)
    {
        return in_array($den, self::buocTiep($tu), true);
    }

    public static function laKetThuc($ma)
    {
        
        return $ma === self::DA_GIAO || $ma === self::DA_HUY || $ma === self::HOAN_HANG || count(self::buocTiep($ma)) === 0;
    }
}
