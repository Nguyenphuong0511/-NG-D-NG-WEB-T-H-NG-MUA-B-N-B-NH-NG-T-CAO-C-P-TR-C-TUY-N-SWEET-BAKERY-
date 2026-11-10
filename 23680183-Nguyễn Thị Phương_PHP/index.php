<?php
require 'init.php';

// Đã đăng nhập thì vào trang quản trị, chưa thì vào cửa hàng
if (Auth::daDangNhap()) {
    header('Location: san_pham_admin.php');
} else {
    header('Location: san_pham.php');
}
exit;
