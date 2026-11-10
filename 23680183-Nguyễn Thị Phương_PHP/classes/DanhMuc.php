<?php

class DanhMuc extends Model
{
    protected static $table = 'danh_muc';
    protected static $orderBy = 'id ASC';

    public $ten_danh_muc = '';
    public $mo_ta = '';

    public function sanPham()
    {
        $statement = static::db()->prepare('SELECT * FROM sanpham WHERE danh_muc_id = :id ORDER BY id DESC');
        $statement->execute([':id' => $this->id]);
        return $statement->fetchAll(PDO::FETCH_CLASS, SanPham::class);
    }

    protected function insert()
    {
        $statement = static::db()->prepare('INSERT INTO danh_muc (ten_danh_muc, mo_ta) VALUES (:ten, :mo_ta)');
        $ketQua = $statement->execute([
            ':ten'   => $this->ten_danh_muc,
            ':mo_ta' => $this->mo_ta
        ]);
        $this->id = (int) static::db()->lastInsertId();
        return $ketQua;
    }

    protected function update()
    {
        $statement = static::db()->prepare('UPDATE danh_muc SET ten_danh_muc = :ten, mo_ta = :mo_ta WHERE id = :id');
        return $statement->execute([
            ':ten'   => $this->ten_danh_muc,
            ':mo_ta' => $this->mo_ta,
            ':id'    => $this->id
        ]);
    }
}

