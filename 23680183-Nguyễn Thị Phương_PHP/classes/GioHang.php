<?php

class GioHang
{
    const SO_LUONG_TOI_DA = 999;

    private $dsMuc = [];
    private $canhBao = [];

   
    public static function taoTuJson($json)
    {
        $gioHang = new GioHang();
        $duLieu = json_decode((string) $json, true);

        if (!is_array($duLieu)) {
            return $gioHang;   
        }

        foreach ($duLieu as $muc) {
            $id = (int) ($muc['id'] ?? 0);
            $soLuong = (int) ($muc['qty'] ?? 0);

            $sanPham = SanPham::find($id);
            if ($sanPham === null) {
                $gioHang->canhBao[] = 'Một sản phẩm trong giỏ không còn bán nên đã được bỏ ra.';
                continue;
            }

            if ($soLuong < 1 || $soLuong > self::SO_LUONG_TOI_DA) {
                $gioHang->canhBao[] = 'Số lượng "' . $sanPham->ten_san_pham . '" không hợp lệ nên đã được bỏ ra.';
                continue;
            }

            $gioHang->them($sanPham, $soLuong);
        }

        return $gioHang;
    }

    public function them($sanPham, $soLuong)
    {
        foreach ($this->dsMuc as $muc) {
            if ($muc->sanPham->id == $sanPham->id) {
                $muc->soLuong = min(self::SO_LUONG_TOI_DA, $muc->soLuong + (int) $soLuong);
                return;
            }
        }
        $this->dsMuc[] = new MucGioHang($sanPham, (int) $soLuong);
    }

    public function dsMuc()
    {
        return $this->dsMuc;
    }

    public function canhBao()
    {
        return $this->canhBao;
    }

    public function laRong()
    {
        return count($this->dsMuc) === 0;
    }

    public function tongSoLuong()
    {
        $tong = 0;
        foreach ($this->dsMuc as $muc) {
            $tong += $muc->soLuong;
        }
        return $tong;
    }

    public function tongTien()
    {
        $tong = 0;
        foreach ($this->dsMuc as $muc) {
            $tong += $muc->thanhTien();
        }
        return $tong;
    }
}
