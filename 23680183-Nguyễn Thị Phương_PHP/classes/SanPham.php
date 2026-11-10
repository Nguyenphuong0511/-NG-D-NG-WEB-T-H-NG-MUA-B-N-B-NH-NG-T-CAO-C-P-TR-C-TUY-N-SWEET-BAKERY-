<?php

class SanPham extends Model
{
    protected static $table = 'sanpham';

    public $ma_san_pham = '';
    public $ten_san_pham = '';
    public $gia_san_pham = 0;
    public $anh_san_pham = '';

    public function tinhThanhTien($soLuong)
    {
        
        return (int) $this->gia_san_pham * (int) $soLuong;
    }

    public function urlAnh()
    {
        return TaiAnhSanPham::url($this->anh_san_pham);
    }

    public function dsAnhPhu()
    {
        return AnhSanPham::theoSanPham($this->id);
    }

    public function tatCaAnh()
    {
        $danhSach = [$this->anh_san_pham];
        foreach ($this->dsAnhPhu() as $anhPhu) {
            if (!empty($anhPhu->duong_dan_anh) && !in_array($anhPhu->duong_dan_anh, $danhSach, true)) {
                $danhSach[] = $anhPhu->duong_dan_anh;
            }
        }
        return $danhSach;
    }

    public static function demSoSanPhamDungAnh($tenFile)
    {
        $statement = static::db()->prepare('SELECT COUNT(*) FROM sanpham WHERE anh_san_pham = :anh');
        $statement->execute([':anh' => $tenFile]);
        return (int) $statement->fetchColumn();
    }

    public function ganDuLieu($duLieu)
    {
        $this->ma_san_pham  = trim($duLieu['ma_san_pham']  ?? '');
        $this->ten_san_pham = trim($duLieu['ten_san_pham'] ?? '');
        $this->gia_san_pham = trim($duLieu['gia_san_pham'] ?? '');
    }

    public function kiemTraHopLe()
    {
        $loi = [];
        if ($this->ma_san_pham === '') {
            $loi[] = 'Mã sản phẩm không được để trống.';
        }
        if ($this->ten_san_pham === '') {
            $loi[] = 'Tên sản phẩm không được để trống.';
        }
        if (!is_numeric($this->gia_san_pham) || $this->gia_san_pham < 0) {
            $loi[] = 'Giá sản phẩm phải là số lớn hơn hoặc bằng 0.';
        }

        return $loi;
    }

    protected function insert()
    {
        $statement = static::db()->prepare(
            'INSERT INTO sanpham (ma_san_pham, ten_san_pham, gia_san_pham, anh_san_pham) VALUES (:ma, :ten, :gia, :anh)'
        );
        $ketQua = $statement->execute([
            ':ma'  => $this->ma_san_pham,
            ':ten' => $this->ten_san_pham,
            ':gia' => $this->gia_san_pham,
            ':anh' => $this->anh_san_pham,
        ]);
        $this->id = (int) static::db()->lastInsertId();
        return $ketQua;
    }

    protected function update()
    {
        $statement = static::db()->prepare(
            'UPDATE sanpham SET ma_san_pham = :ma, ten_san_pham = :ten, gia_san_pham = :gia, anh_san_pham = :anh WHERE id = :id'
        );
        return $statement->execute([
            ':ma'  => $this->ma_san_pham,
            ':ten' => $this->ten_san_pham,
            ':gia' => $this->gia_san_pham,
            ':anh' => $this->anh_san_pham,
            ':id'  => $this->id,
        ]);
    }
}
