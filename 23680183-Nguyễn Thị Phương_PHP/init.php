<?php
// ======================================================
// FILE KHỞI ĐỘNG - mọi trang đều require file này đầu tiên.
// ======================================================

date_default_timezone_set('Asia/Ho_Chi_Minh');

session_start();

require_once __DIR__ . '/config.php';

spl_autoload_register(function ($tenLop) {
    $file = __DIR__ . '/classes/' . $tenLop . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

function e($giaTri)
{
    return htmlspecialchars((string) $giaTri, ENT_QUOTES, 'UTF-8');
}
