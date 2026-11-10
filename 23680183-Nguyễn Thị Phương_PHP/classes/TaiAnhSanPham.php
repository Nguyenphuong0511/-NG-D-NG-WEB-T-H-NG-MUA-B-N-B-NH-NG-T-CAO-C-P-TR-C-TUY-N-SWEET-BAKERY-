<?php

class TaiAnhSanPham
{
    const THU_MUC = __DIR__ . '/../Images';           // thư mục lưu ảnh trên server
    const DUONG_DAN_WEB = 'Images';                    // đường dẫn để hiển thị trên web
    const ANH_MAC_DINH = 'Images/khong-co-anh.svg';    // hiển thị khi sản phẩm chưa có ảnh
    const DUNG_LUONG_TOI_DA = 2 * 1024 * 1024;         // 2 MB

    private static $kieuChoPhep = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    private $loi = '';

    public function loi()
    {
        return $this->loi;
    }

    public static function coChonFile($file)
    {
        return is_array($file) && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
    }

    
    public static function vuotQuaGioiHanPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES)
            && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0;
    }

    
    public function luu($file)
    {
        // 1. Lỗi do PHP báo về
        $maLoi = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($maLoi === UPLOAD_ERR_INI_SIZE || $maLoi === UPLOAD_ERR_FORM_SIZE) {
            return $this->baoLoi('Ảnh quá lớn, tối đa ' . self::dungLuongToiDaMB() . ' MB.');
        }
        if ($maLoi !== UPLOAD_ERR_OK) {
            return $this->baoLoi('Tải ảnh lên không thành công (mã lỗi ' . $maLoi . '), vui lòng thử lại.');
        }
        // Bước 2: Kiểm tra dung lượng
        if ($file['size'] > self::DUNG_LUONG_TOI_DA) {
            return $this->baoLoi('Ảnh quá lớn, tối đa ' . self::dungLuongToiDaMB() . ' MB.');
        }

        // Bước 3: Phải thật sự được tải lên qua form
        if (!is_uploaded_file($file['tmp_name'])) {
            return $this->baoLoi('File tải lên không hợp lệ.');
        }

        // Bước 4: Kiểm tra nội dung file
        $kieu = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!isset(self::$kieuChoPhep[$kieu]) || @getimagesize($file['tmp_name']) === false) {
            return $this->baoLoi('Chỉ nhận file ảnh JPG, PNG, GIF hoặc WEBP.');
        }

        // Bước 5: Tự đặt tên mới
        $tenFile = 'sp_' . date('ymdHis') . '_' . bin2hex(random_bytes(3)) . '.' . self::$kieuChoPhep[$kieu];

        // Bước 6: Chuyển file vào thư mục ảnh
        if (!move_uploaded_file($file['tmp_name'], self::THU_MUC . '/' . $tenFile)) {
            return $this->baoLoi('Không lưu được ảnh lên server. Kiểm tra quyền ghi thư mục Images.');
        }
        return $tenFile;
    }

    public static function xoa($tenFile)
    {
       
        $tenFile = basename((string) $tenFile);
        if ($tenFile === '' || SanPham::demSoSanPhamDungAnh($tenFile) > 0) {
            return;
        }
        $duongDan = self::THU_MUC . '/' . $tenFile;
        clearstatcache(true, $duongDan);
        if (is_file($duongDan)) {
            @unlink($duongDan);
            clearstatcache(true, $duongDan);
        }
    }

    /** Đường dẫn để đặt vào thẻ <img src="...">; chưa có ảnh thì dùng ảnh mặc định */
    public static function url($tenFile)
    {
        if ($tenFile === null || $tenFile === '') {
            return self::ANH_MAC_DINH;
        }
        return self::DUONG_DAN_WEB . '/' . rawurlencode($tenFile);
    }

    public static function dungLuongToiDaMB()
    {
        return self::DUNG_LUONG_TOI_DA / 1024 / 1024;
    }

    private function baoLoi($thongBao)
    {
        $this->loi = $thongBao;
        return null;
    }
}
