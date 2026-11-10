<?php
/**
 * LỚP AnhSanPham - Một sản phẩm có nhiều ảnh phụ (Thực hành OOP nâng cao)
 * Bảng: `anh_san_pham`
 */
class AnhSanPham extends Model
{
    protected static $table = 'anh_san_pham';
    protected static $orderBy = 'thu_tu ASC, id ASC';

    public $san_pham_id = 0;
    public $duong_dan_anh = '';
    public $thu_tu = 0;

    /** Lấy danh sách ảnh phụ theo sản phẩm id */
    public static function theoSanPham($sanPhamId)
    {
        $statement = static::db()->prepare('SELECT * FROM anh_san_pham WHERE san_pham_id = :sp_id ORDER BY thu_tu ASC, id ASC');
        $statement->execute([':sp_id' => (int) $sanPhamId]);
        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function urlAnh()
    {
        return TaiAnhSanPham::url($this->duong_dan_anh);
    }

    protected function insert()
    {
        $sql = 'INSERT INTO anh_san_pham (san_pham_id, duong_dan_anh, thu_tu) VALUES (:sp_id, :anh, :thu_tu)';
        $statement = static::db()->prepare($sql);
        $ketQua = $statement->execute([
            ':sp_id'   => (int) $this->san_pham_id,
            ':anh'     => $this->duong_dan_anh,
            ':thu_tu'  => (int) $this->thu_tu,
        ]);
        $this->id = (int) static::db()->lastInsertId();
        return $ketQua;
    }

    protected function update()
    {
        $sql = 'UPDATE anh_san_pham SET san_pham_id = :sp_id, duong_dan_anh = :anh, thu_tu = :thu_tu WHERE id = :id';
        $statement = static::db()->prepare($sql);
        return $statement->execute([
            ':sp_id'   => (int) $this->san_pham_id,
            ':anh'     => $this->duong_dan_anh,
            ':thu_tu'  => (int) $this->thu_tu,
            ':id'      => (int) $this->id,
        ]);
    }
}

