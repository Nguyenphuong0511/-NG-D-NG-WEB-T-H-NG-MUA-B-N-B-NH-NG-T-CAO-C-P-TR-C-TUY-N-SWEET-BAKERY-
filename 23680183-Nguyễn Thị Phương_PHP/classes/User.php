<?php

class User extends Model
{
    protected static $table = 'user';

    public $ten_dang_nhap = '';
    public $mat_khau = '';
    public $vai_tro = 'khach_hang'; // 'admin', 'nhanvien', 'khach_hang'

    public function laAdmin()
    {
        return $this->vai_tro === 'admin' || $this->ten_dang_nhap === 'admin';
    }

    public function laQuanTri()
    {
        return in_array($this->vai_tro, ['admin', 'nhanvien'], true)
            || $this->ten_dang_nhap === 'admin'
            || (strpos($this->ten_dang_nhap, 'nhanvien') === 0);
    }

    public static function timTheoTenDangNhap($tenDangNhap)
    {
        
        $statement = static::db()->prepare('SELECT * FROM user WHERE ten_dang_nhap = :ten');
        $statement->execute([':ten' => $tenDangNhap]);
        $statement->setFetchMode(PDO::FETCH_CLASS, self::class);
        $doiTuong = $statement->fetch();
        return $doiTuong ? $doiTuong : null;
    }

    
    public function kiemTraMatKhau($matKhau)
    {
        
        if (password_verify($matKhau, $this->mat_khau)) {
            return true;
        }
        return $this->mat_khau === $matKhau;
    }

    protected function insert()
    {
        $statement = static::db()->prepare('INSERT INTO user (ten_dang_nhap, mat_khau, vai_tro) VALUES (:ten, :mk, :vai_tro)');
        $ketQua = $statement->execute([
            ':ten'      => $this->ten_dang_nhap,
            ':mk'       => $this->mat_khau,
            ':vai_tro'  => $this->vai_tro ?: 'khach_hang'
        ]);
        $this->id = (int) static::db()->lastInsertId();
        return $ketQua;
    }

    protected function update()
    {
        $statement = static::db()->prepare('UPDATE user SET ten_dang_nhap = :ten, mat_khau = :mk, vai_tro = :vai_tro WHERE id = :id');
        return $statement->execute([
            ':ten'      => $this->ten_dang_nhap,
            ':mk'       => $this->mat_khau,
            ':vai_tro'  => $this->vai_tro ?: 'khach_hang',
            ':id'       => $this->id
        ]);
    }
}
