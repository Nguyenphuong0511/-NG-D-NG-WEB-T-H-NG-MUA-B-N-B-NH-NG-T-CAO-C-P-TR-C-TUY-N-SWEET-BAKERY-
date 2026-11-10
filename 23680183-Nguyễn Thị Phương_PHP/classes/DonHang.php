<?php

class DonHang extends Model
{
    protected static $table = 'don_hang';
    protected static $orderBy = 'id DESC';   // đơn mới nhất lên đầu

    public $ma_don_hang = null;
    public $ho_ten = '';
    public $so_dien_thoai = '';
    public $dia_chi = '';
    public $ghi_chu = null;
    public $tong_tien = 0;
    public $trang_thai = TrangThaiDonHang::CHO_XAC_NHAN;
    public $ngay_dat = null;
    public $ngay_cap_nhat = null;

    public $tong_so_luong = 0;

    public static function taoTuGioHang($gioHang, $hoTen, $soDienThoai, $diaChi, $ghiChu = '')
    {
        $db = static::db();
        $db->beginTransaction();

        try {
            $don = new DonHang();
            $don->ho_ten = $hoTen;
            $don->so_dien_thoai = $soDienThoai;
            $don->dia_chi = $diaChi;
            $don->ghi_chu = ($ghiChu === '') ? null : $ghiChu;
            $don->tong_tien = $gioHang->tongTien();
            $don->trang_thai = TrangThaiDonHang::CHO_XAC_NHAN;
            $don->ngay_dat = date('Y-m-d H:i:s');
            $don->save();

            foreach ($gioHang->dsMuc() as $muc) {
                ChiTietDonHang::taoTuMucGioHang($muc, $don->id)->save();
            }

            LichSuDonHang::ghi($don->id, null, $don->trang_thai, 'Khách đặt hàng', 'Khách hàng');

            $db->commit();
            return $don;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }


    public function chiTiet()
    {
        return ChiTietDonHang::theoDonHang($this->id);
    }

    public function lichSu()
    {
        return LichSuDonHang::theoDonHang($this->id);
    }

    public function nhanTrangThai()
    {
        return TrangThaiDonHang::nhan($this->trang_thai);
    }

    public function lopCssTrangThai()
    {
        return TrangThaiDonHang::lopCss($this->trang_thai);
    }

    public function cacTrangThaiTiepTheo()
    {
        return TrangThaiDonHang::buocTiep($this->trang_thai);
    }

   
    public function chuyenTrangThai($trangThaiMoi, $ghiChu, $nguoiThucHien)
    {
        if (!TrangThaiDonHang::coTheChuyen($this->trang_thai, $trangThaiMoi)) {
            throw new InvalidArgumentException(
                'Không thể chuyển đơn từ "' . TrangThaiDonHang::nhan($this->trang_thai)
                . '" sang "' . TrangThaiDonHang::nhan($trangThaiMoi) . '".'
            );
        }

        $db = static::db();
        $db->beginTransaction();

        try {
            $trangThaiCu = $this->trang_thai;
            $this->trang_thai = $trangThaiMoi;
            $this->ngay_cap_nhat = date('Y-m-d H:i:s');
            $this->save();

            LichSuDonHang::ghi($this->id, $trangThaiCu, $trangThaiMoi, $ghiChu, $nguoiThucHien);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function timKiem($trangThai = '', $tuKhoa = '')
    {
        $sql = 'SELECT d.*,
                       (SELECT COALESCE(SUM(c.so_luong), 0) FROM chi_tiet_don_hang c
                        WHERE c.don_hang_id = d.id) AS tong_so_luong
                FROM don_hang d
                WHERE 1 = 1';
        $thamSo = [];

        if ($trangThai !== '') {
            $sql .= ' AND d.trang_thai = :trang_thai';
            $thamSo[':trang_thai'] = $trangThai;
        }
        if ($tuKhoa !== '') {
            $sql .= ' AND (d.ma_don_hang LIKE :tk1 OR d.ho_ten LIKE :tk2 OR d.so_dien_thoai LIKE :tk3)';
            $thamSo[':tk1'] = $thamSo[':tk2'] = $thamSo[':tk3'] = '%' . $tuKhoa . '%';
        }
        $sql .= ' ORDER BY d.id DESC';

        $statement = static::db()->prepare($sql);
        $statement->execute($thamSo);
        return $statement->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function demTheoTrangThai()
    {
        $ketQua = array_fill_keys(array_keys(TrangThaiDonHang::tatCa()), 0);
        $rows = static::db()->query('SELECT trang_thai, COUNT(*) AS so FROM don_hang GROUP BY trang_thai');
        foreach ($rows as $row) {
            $ketQua[$row['trang_thai']] = (int) $row['so'];
        }
        return $ketQua;
    }

    public static function doanhThu()
    {
        $statement = static::db()->prepare('SELECT COALESCE(SUM(tong_tien), 0) FROM don_hang WHERE trang_thai = :tt');
        $statement->execute([':tt' => TrangThaiDonHang::DA_GIAO]);
        return (int) $statement->fetchColumn();
    }

    public static function timTheoMaVaSoDienThoai($maDonHang, $soDienThoai)
    {
        $statement = static::db()->prepare(
            'SELECT * FROM don_hang WHERE ma_don_hang = :ma AND so_dien_thoai = :sdt'
        );
        $statement->execute([':ma' => $maDonHang, ':sdt' => $soDienThoai]);
        $statement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $don = $statement->fetch();
        return $don ? $don : null;
    }

    protected function insert()
    {
        $sql = 'INSERT INTO don_hang (ho_ten, so_dien_thoai, dia_chi, ghi_chu, tong_tien, trang_thai, ngay_dat)
                VALUES (:ho_ten, :sdt, :dia_chi, :ghi_chu, :tong_tien, :trang_thai, :ngay_dat)';
        $statement = static::db()->prepare($sql);
        $ketQua = $statement->execute([
            ':ho_ten'     => $this->ho_ten,
            ':sdt'        => $this->so_dien_thoai,
            ':dia_chi'    => $this->dia_chi,
            ':ghi_chu'    => $this->ghi_chu,
            ':tong_tien'  => $this->tong_tien,
            ':trang_thai' => $this->trang_thai,
            ':ngay_dat'   => $this->ngay_dat,
        ]);
        $this->id = (int) static::db()->lastInsertId();

        $this->ma_don_hang = 'DH' . date('ymd', strtotime($this->ngay_dat)) . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        static::db()->prepare('UPDATE don_hang SET ma_don_hang = :ma WHERE id = :id')
                    ->execute([':ma' => $this->ma_don_hang, ':id' => $this->id]);

        return $ketQua;
    }

    protected function update()
    {
        $sql = 'UPDATE don_hang
                SET ho_ten = :ho_ten, so_dien_thoai = :sdt, dia_chi = :dia_chi, ghi_chu = :ghi_chu,
                    tong_tien = :tong_tien, trang_thai = :trang_thai, ngay_cap_nhat = :ngay_cap_nhat
                WHERE id = :id';
        $statement = static::db()->prepare($sql);
        return $statement->execute([
            ':ho_ten'        => $this->ho_ten,
            ':sdt'           => $this->so_dien_thoai,
            ':dia_chi'       => $this->dia_chi,
            ':ghi_chu'       => $this->ghi_chu,
            ':tong_tien'     => $this->tong_tien,
            ':trang_thai'    => $this->trang_thai,
            ':ngay_cap_nhat' => $this->ngay_cap_nhat,
            ':id'            => $this->id,
        ]);
    }

 
    public function delete()
    {
        $db = static::db();
        $db->beginTransaction();
        try {
            $db->prepare('DELETE FROM chi_tiet_don_hang WHERE don_hang_id = :id')->execute([':id' => $this->id]);
            $db->prepare('DELETE FROM lich_su_don_hang WHERE don_hang_id = :id')->execute([':id' => $this->id]);
            $ketQua = parent::delete();
            $db->commit();
            return $ketQua;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
