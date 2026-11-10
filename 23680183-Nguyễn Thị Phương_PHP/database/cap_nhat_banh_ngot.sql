-- Cập nhật cơ sở dữ liệu cho Tiệm Bánh Ngọt (Sweet Bakery)
-- Hỗ trợ 1 sản phẩm nhiều ảnh (bảng anh_san_pham) và giá thị trường chuẩn

SET NAMES utf8mb4;
USE `project_demo`;

-- 1. Bảng anh_san_pham lưu nhiều ảnh cho 1 sản phẩm
CREATE TABLE IF NOT EXISTS `anh_san_pham` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `san_pham_id` int(11) NOT NULL,
  `duong_dan_anh` varchar(100) NOT NULL,
  `thu_tu` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_sp_anh` (`san_pham_id`),
  CONSTRAINT `fk_anh_sanpham` FOREIGN KEY (`san_pham_id`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Cập nhật dữ liệu bánh ngọt chuẩn thị trường vào bảng sanpham
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `anh_san_pham`;
DELETE FROM `sanpham`;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `sanpham` (`id`, `ma_san_pham`, `ten_san_pham`, `gia_san_pham`, `anh_san_pham`) VALUES
(1, 'BN01', 'Bánh Mì Hoa Cúc Bơ Sữa', '65000', 'Bánh mì hoa cúc 1.jpg'),
(2, 'BN02', 'Bánh Mì Hoa Cúc Socola', '75000', 'Bánh mì hoa cúc socola.jpg'),
(3, 'BN03', 'Bánh Sừng Bò Croissant', '35000', 'Croissant.jpg'),
(4, 'BN04', 'Bánh Macaron Pháp 6 Vị', '120000', 'Macaron 1.jpg'),
(5, 'BN05', 'Bánh Su Kem Vani Giòn', '25000', 'Su kem 1.jpg'),
(6, 'BN06', 'Bánh Tart Trứng Nướng', '28000', 'tart trứng 1.jpg'),
(7, 'BN07', 'Bánh Tiramisu Truyền Thống Ý', '55000', 'Tiramisu.jpg'),
(8, 'BN08', 'Bánh Tiramisu Matcha Nhật Bản', '58000', 'Matcha Tiramisu.jpg'),
(9, 'BN09', 'Bánh Mousse Socola Đậm Đà', '48000', 'Mousse Chocolate.jpg'),
(10, 'BN10', 'Bánh Mousse Trà Xanh Matcha', '48000', 'Mousse Matcha.jpg'),
(11, 'BN11', 'Bánh Mousse Dâu Rừng Raspberry', '52000', 'Raspberry Mousse.jpg'),
(12, 'BN12', 'Bánh Chocolate Raspberry Mousse', '55000', 'Chocolate Raspberry Mousse .jpg'),
(13, 'BN13', 'Bánh Cheesecake Nướng Basque', '62000', 'Basque Cheesecake.jpg'),
(14, 'BN14', 'Bánh Cheesecake Dâu Tây', '58000', 'Strawberry cheesecake.jpg'),
(15, 'BN15', 'Bánh Cheesecake Việt Quất Chanh Vàng', '60000', 'Blackberry-Lemon Cheesecake.jpg');

-- 3. Thêm các ảnh phụ phong phú cho từng bánh (1 sản phẩm có nhiều ảnh để khách xem chi tiết)
INSERT INTO `anh_san_pham` (`san_pham_id`, `duong_dan_anh`, `thu_tu`) VALUES
(1, 'Bánh mì hoa cúc 2.jpg', 1),
(3, 'Croissant 1.jpg', 1),
(3, 'Croissant 2.jpg', 2),
(4, 'Macaron 2.jpg', 1),
(4, 'Macaron 3.jpg', 2),
(5, 'Su kem 2.jpg', 1),
(5, 'Su kem 3.jpg', 2),
(6, 'tart trứng 3.jpg', 1),
(6, 'tart trứng.jpg', 2);

-- 4. Phân quyền người dùng (khách hàng, nhân viên, quản trị)
ALTER TABLE `user` ADD COLUMN IF NOT EXISTS `vai_tro` VARCHAR(30) NOT NULL DEFAULT 'khach_hang';
UPDATE `user` SET `vai_tro` = 'admin' WHERE `ten_dang_nhap` = 'admin';
UPDATE `user` SET `vai_tro` = 'nhanvien' WHERE `ten_dang_nhap` LIKE 'nhanvien%';


