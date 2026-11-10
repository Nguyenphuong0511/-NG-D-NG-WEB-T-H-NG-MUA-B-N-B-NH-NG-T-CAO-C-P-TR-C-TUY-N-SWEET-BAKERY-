<?php

abstract class Model
{
    protected static $table = '';
    protected static $orderBy = 'id';

    public $id = null;

    protected static function db()
    {
        return Database::getConnection();
    }

    public static function all()
    {

        $sql = 'SELECT * FROM ' . static::$table . ' ORDER BY ' . static::$orderBy;
        return static::db()->query($sql)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function find($id)
    {
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE id = :id';
        $statement = static::db()->prepare($sql);
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, static::class);
        $doiTuong = $statement->fetch();
        return $doiTuong ? $doiTuong : null;
    }

    public function save()
    {
       
        if ($this->id === null) {
            return $this->insert();
        }
        return $this->update();
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . static::$table . ' WHERE id = :id';
        $statement = static::db()->prepare($sql);
        return $statement->execute([':id' => $this->id]);
    }

    abstract protected function insert();
    abstract protected function update();
}
