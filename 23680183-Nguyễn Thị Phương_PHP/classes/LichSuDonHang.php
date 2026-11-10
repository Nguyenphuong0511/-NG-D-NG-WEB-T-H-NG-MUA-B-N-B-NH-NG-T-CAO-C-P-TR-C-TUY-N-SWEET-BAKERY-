<?php

class LichSuDonHang extends Model
{
    protected static $table = 'lich_su_don_hang';

    public $don_hang_id;
    public $trang_thai_cu = null;
    public $trang_thai_moi = '';
    public $ghi_chu = null;
    public $nguoi_thuc_hien = '';
    public $thoi_gian = null;

    public static function ghi($donHangId, $trangThaiCu, $trangThaiMoi, $ghiChu, $nguoiThucHien)
    {
        $lichSu = new LichSuDonHang();
        $lichSu->don_hang_id = $donHangId;
        $lichSu->trang_thai_cu = $trangThaiCu;
        $lichSu->trang_thai_moi = $trangThaiMoi;
        $lichSu->ghi_chu = ($ghiChu === '' || $ghiChu === null) ? null : $ghiChu;
        $lichSu->nguoi_thuc_hien = $nguoiThucHien;
        $lichSu->thoi_gian = date('Y-m-d H:i:s');
        $lichSu->save();
        return $lichSu;
    }

    public static function theoDonHang($donHangId)
    {
        $statement = static::db()->prepare('SELECT * FROM lich_su_don_hang WHERE don_hang_id = :id ORDER BY id');
        $statement->execute([':id' => $donHangId]);
        return $statement->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    protected function insert()
    {
        $sql = 'INSERT INTO lich_su_don_hang (don_hang_id, trang_thai_cu, trang_thai_moi, ghi_chu, nguoi_thuc_hien, thoi_gian)
                VALUES (:don_hang_id, :cu, :moi, :ghi_chu, :nguoi, :thoi_gian)';
        $statement = static::db()->prepare($sql);
        $ketQua = $statement->execute([
            ':don_hang_id' => $this->don_hang_id,
            ':cu'          => $this->trang_thai_cu,
            ':moi'         => $this->trang_thai_moi,
            ':ghi_chu'     => $this->ghi_chu,
            ':nguoi'       => $this->nguoi_thuc_hien,
            ':thoi_gian'   => $this->thoi_gian,
        ]);
        $this->id = (int) static::db()->lastInsertId();
        return $ketQua;
    }

    protected function update()
    {
        throw new LogicException('Không được sửa lịch sử đơn hàng.');
    }
}
