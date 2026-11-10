<?php

class ChiTietDonHang extends Model
{
    protected static $table = 'chi_tiet_don_hang';

    public $don_hang_id;
    public $san_pham_id;
    public $ma_san_pham = '';
    public $ten_san_pham = '';
    public $don_gia = 0;
    public $so_luong = 0;
    public $thanh_tien = 0;

    public static function taoTuMucGioHang($muc, $donHangId)
    {
        $chiTiet = new ChiTietDonHang();
        $chiTiet->don_hang_id  = $donHangId;
        $chiTiet->san_pham_id  = $muc->sanPham->id;
        $chiTiet->ma_san_pham  = $muc->sanPham->ma_san_pham;
        $chiTiet->ten_san_pham = $muc->sanPham->ten_san_pham;
        $chiTiet->don_gia      = (int) $muc->sanPham->gia_san_pham;
        $chiTiet->so_luong     = (int) $muc->soLuong;
        $chiTiet->thanh_tien   = $muc->thanhTien();
        return $chiTiet;
    }

    public static function theoDonHang($donHangId)
    {
        $statement = static::db()->prepare('SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = :id ORDER BY id');
        $statement->execute([':id' => $donHangId]);
        return $statement->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    protected function insert()
    {
        $sql = 'INSERT INTO chi_tiet_don_hang
                    (don_hang_id, san_pham_id, ma_san_pham, ten_san_pham, don_gia, so_luong, thanh_tien)
                VALUES (:don_hang_id, :san_pham_id, :ma, :ten, :don_gia, :so_luong, :thanh_tien)';
        $statement = static::db()->prepare($sql);
        $ketQua = $statement->execute($this->thamSo());
        $this->id = (int) static::db()->lastInsertId();
        return $ketQua;
    }

    protected function update()
    {
        $sql = 'UPDATE chi_tiet_don_hang
                SET don_hang_id = :don_hang_id, san_pham_id = :san_pham_id, ma_san_pham = :ma, ten_san_pham = :ten,
                    don_gia = :don_gia, so_luong = :so_luong, thanh_tien = :thanh_tien
                WHERE id = :id';
        $statement = static::db()->prepare($sql);
        return $statement->execute($this->thamSo() + [':id' => $this->id]);
    }

    private function thamSo()
    {
        return [
            ':don_hang_id' => $this->don_hang_id,
            ':san_pham_id' => $this->san_pham_id,
            ':ma'          => $this->ma_san_pham,
            ':ten'         => $this->ten_san_pham,
            ':don_gia'     => $this->don_gia,
            ':so_luong'    => $this->so_luong,
            ':thanh_tien'  => $this->thanh_tien,
        ];
    }
}
