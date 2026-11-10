<?php

class MucGioHang
{
    public $sanPham;  
    public $soLuong;

    public function __construct($sanPham, $soLuong)
    {
        $this->sanPham = $sanPham;
        $this->soLuong = (int) $soLuong;
    }

    public function thanhTien()
    {
        return $this->sanPham->tinhThanhTien($this->soLuong);
    }
}
