-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 03:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web2`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chitietdonhang`
--

CREATE TABLE `tbl_chitietdonhang` (
  `IDCTDH` int(11) NOT NULL,
  `maDonHang` int(11) NOT NULL,
  `tenNguoiNhan` varchar(255) NOT NULL,
  `sdtKH` int(11) NOT NULL,
  `ghiChuCuaKhachhang` varchar(255) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `tenSanPham` varchar(255) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `mieuTaSP` varchar(255) NOT NULL,
  `hinhAnhSP` varchar(255) NOT NULL,
  `diachi` varchar(255) NOT NULL,
  `soLuongSanPham` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_chitietdonhang`
--

INSERT INTO `tbl_chitietdonhang` (`IDCTDH`, `maDonHang`, `tenNguoiNhan`, `sdtKH`, `ghiChuCuaKhachhang`, `maSanPham`, `tenSanPham`, `product_size_id`, `mieuTaSP`, `hinhAnhSP`, `diachi`, `soLuongSanPham`) VALUES
(129, 1, 'Phạm Thanh Vương', 974228483, 'SGU', 159, 'Cafe sữa nóng', 22, 'Cà phê sữa nóng là một thức uống phổ biến, đặc biệt là ở các quốc gia Đông Nam Á như Việt Nam.', '1742956644.png', 'Đại học Sài Gòn', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chitiethoadon`
--

CREATE TABLE `tbl_chitiethoadon` (
  `idHD` int(11) NOT NULL,
  `maHoaDon` int(11) NOT NULL,
  `tenNguoiNhan` varchar(255) NOT NULL,
  `sdtKH` int(11) NOT NULL,
  `ghiChu` varchar(255) NOT NULL,
  `maSP` int(11) NOT NULL,
  `tenSP` varchar(255) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `mieuTaSP` varchar(255) NOT NULL,
  `hinhAnhSP` varchar(255) NOT NULL,
  `diachi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_chitiethoadon`
--

INSERT INTO `tbl_chitiethoadon` (`idHD`, `maHoaDon`, `tenNguoiNhan`, `sdtKH`, `ghiChu`, `maSP`, `tenSP`, `product_size_id`, `mieuTaSP`, `hinhAnhSP`, `diachi`) VALUES
(95, 1, 'Phạm Thanh Vương', 974228483, 'SGU', 159, 'Cafe sữa nóng', 22, 'Cà phê sữa nóng là một thức uống phổ biến, đặc biệt là ở các quốc gia Đông Nam Á như Việt Nam.', '1742956644.png', 'Đại học Sài Gòn');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_donhang`
--

CREATE TABLE `tbl_donhang` (
  `maDonHang` int(10) NOT NULL,
  `maKhachHang` int(10) DEFAULT NULL,
  `ngayLapDH` date NOT NULL,
  `tongTienDH` int(10) NOT NULL,
  `trangThaiDH` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_donhang`
--

INSERT INTO `tbl_donhang` (`maDonHang`, `maKhachHang`, `ngayLapDH`, `tongTienDH`, `trangThaiDH`) VALUES
(1, 28, '2025-04-16', 160000, 'Đã hoàn thành');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_giohang`
--

CREATE TABLE `tbl_giohang` (
  `id_giohang` int(10) NOT NULL,
  `maSanPham` int(20) NOT NULL,
  `sessionID` varchar(225) NOT NULL,
  `maLoai` int(10) NOT NULL,
  `tenSanPham` varchar(225) NOT NULL,
  `product_size_id` int(10) NOT NULL,
  `mieuTaSanPham` varchar(225) NOT NULL,
  `hinhAnhSanPham` varchar(225) NOT NULL,
  `soLuongSanPham` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_giohang`
--

INSERT INTO `tbl_giohang` (`id_giohang`, `maSanPham`, `sessionID`, `maLoai`, `tenSanPham`, `product_size_id`, `mieuTaSanPham`, `hinhAnhSanPham`, `soLuongSanPham`) VALUES
(560, 159, 'qg85trd278lqombpo8086ppb81', 1, 'Cafe sữa nóng', 22, 'Cà phê sữa nóng là một thức uống phổ biến, đặc biệt là ở các quốc gia Đông Nam Á như Việt Nam.', '1742956644.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hoadon`
--

CREATE TABLE `tbl_hoadon` (
  `maHoaDon` int(11) NOT NULL,
  `maKhachHang` int(11) NOT NULL,
  `ngayDat` date NOT NULL,
  `giaTriHD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_hoadon`
--

INSERT INTO `tbl_hoadon` (`maHoaDon`, `maKhachHang`, `ngayDat`, `giaTriHD`) VALUES
(1, 28, '2025-04-16', 160000),
(2, 28, '2025-04-16', 40000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_khachhang`
--

CREATE TABLE `tbl_khachhang` (
  `maKhachHang` int(11) NOT NULL,
  `tenDangNhap` varchar(255) NOT NULL,
  `matKhau` varchar(255) NOT NULL,
  `hoTenKhachHang` varchar(255) NOT NULL,
  `thuDienTuKH` varchar(255) NOT NULL,
  `trangThai` varchar(255) NOT NULL DEFAULT 'Active',
  `ngaySinh` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_khachhang`
--

INSERT INTO `tbl_khachhang` (`maKhachHang`, `tenDangNhap`, `matKhau`, `hoTenKhachHang`, `thuDienTuKH`, `trangThai`, `ngaySinh`) VALUES
(28, 'Vwuong', 'a08149bfa4f2b061d9d8c1b14b5342f5', 'Vwuong', 'ThanhVuong@gmail.com', 'Active', '2015-08-24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loaisanpham`
--

CREATE TABLE `tbl_loaisanpham` (
  `maLoai` int(11) NOT NULL,
  `tenLoai` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_loaisanpham`
--

INSERT INTO `tbl_loaisanpham` (`maLoai`, `tenLoai`) VALUES
(1, 'COFFEE'),
(2, 'TRÀ SỮA'),
(3, 'MÓN ĂN NHẸ');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_size`
--

CREATE TABLE `tbl_product_size` (
  `id` int(11) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `giaSanPham` int(11) NOT NULL,
  `soLuongSanPham` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_product_size`
--

INSERT INTO `tbl_product_size` (`id`, `maSanPham`, `size`, `giaSanPham`, `soLuongSanPham`) VALUES
(7, 154, 'S', 35000, 30),
(8, 154, 'M', 40000, 50),
(9, 154, 'L', 45000, 50),
(10, 155, 'S', 35000, 48),
(11, 155, 'M', 40000, 49),
(12, 155, 'L', 45000, 50),
(13, 156, 'S', 35000, 50),
(14, 156, 'M', 40000, 50),
(15, 156, 'L', 45000, 50),
(16, 157, 'S', 35000, 50),
(17, 157, 'M', 40000, 50),
(18, 157, 'L', 45000, 50),
(22, 159, 'S', 40000, 74),
(23, 159, 'M', 45000, 200),
(24, 159, 'L', 50000, 100),
(25, 160, 'S', 49000, 48),
(26, 160, 'M', 59000, 50),
(27, 160, 'L', 69000, 50),
(28, 161, 'S', 30000, 50),
(29, 161, 'M', 35000, 50),
(30, 161, 'L', 40000, 50),
(31, 162, 'S', 30000, 40),
(32, 162, 'M', 35000, 50),
(33, 162, 'L', 40000, 50),
(34, 163, 'S', 30000, 50),
(35, 163, 'M', 35000, 60),
(36, 163, 'L', 40000, 70),
(37, 164, 'S', 30000, 50),
(38, 164, 'M', 35000, 50),
(39, 164, 'L', 40000, 50),
(40, 165, 'S', 30000, 50),
(41, 165, 'M', 35000, 50),
(42, 165, 'L', 40000, 50),
(43, 166, 'S', 45000, 100),
(44, 166, 'M', 55000, 100),
(45, 166, 'L', 65000, 100),
(46, 167, 'S', 30000, 100),
(47, 167, 'M', 35000, 100),
(48, 167, 'L', 40000, 100),
(49, 168, 'S', 45000, 100),
(50, 168, 'M', 50000, 100),
(51, 168, 'L', 60000, 100),
(52, 169, 'S', 45000, 100),
(53, 169, 'M', 55000, 100),
(54, 169, 'L', 65000, 100),
(55, 170, 'S', 29000, 100),
(56, 170, 'M', 35000, 100),
(57, 170, 'L', 39000, 100),
(58, 158, 'S', 40000, 100),
(59, 158, 'M', 45000, 100),
(60, 158, 'L', 50000, 100),
(61, 171, 'S', 29000, 100),
(62, 171, 'M', 35000, 100),
(63, 171, 'L', 39000, 100),
(64, 172, 'S', 29000, 100),
(65, 172, 'M', 35000, 100),
(66, 172, 'L', 39000, 100),
(70, 174, 'S', 29000, 100),
(71, 174, 'M', 35000, 100),
(72, 174, 'L', 39000, 100),
(73, 175, 'S', 1000, 100),
(74, 175, 'M', 2000, 100),
(75, 175, 'L', 3000, 100),
(76, 176, 'S', 40000, 100),
(77, 176, 'M', 45000, 100),
(78, 176, 'L', 50000, 100),
(79, 177, 'S', 30000, 100),
(80, 177, 'M', 35000, 100),
(81, 177, 'M', 40000, 100),
(82, 178, 'S', 39000, 100),
(83, 178, 'M', 49000, 100),
(84, 178, 'L', 59000, 100),
(88, 180, 'S', 1, 1),
(89, 179, 'S', 39000, 100),
(90, 179, 'M', 49000, 100),
(91, 179, 'L', 59005, 100),
(95, 173, 'S', 29000, 100),
(96, 173, 'S', 35000, 100),
(97, 173, 'L', 39000, 100);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quantri`
--

CREATE TABLE `tbl_quantri` (
  `tenDangNhap` varchar(255) NOT NULL,
  `matKhau` varchar(255) NOT NULL,
  `tenNguoiQuanTri` varchar(255) NOT NULL,
  `thuDienTuQT` varchar(255) NOT NULL,
  `trangThai` varchar(255) NOT NULL DEFAULT 'Active',
  `maVaiTro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_quantri`
--

INSERT INTO `tbl_quantri` (`tenDangNhap`, `matKhau`, `tenNguoiQuanTri`, `thuDienTuQT`, `trangThai`, `maVaiTro`) VALUES
('Ngan', 'f951ee98279e096df5e03f2e3242a9c1', 'Ngan', 'Ngan@gmail.com', 'Inactive', 2),
('Vuong', '2d2c09b0f1a0edbdf3f6b8512e78eb23', 'Admin', 'Vuong@gmail.com', 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sanpham`
--

CREATE TABLE `tbl_sanpham` (
  `maSanPham` int(11) NOT NULL,
  `maLoai` int(11) NOT NULL,
  `tenSanPham` varchar(255) NOT NULL,
  `mieuTaSanPham` mediumtext NOT NULL,
  `trangThaiSanPham` varchar(255) NOT NULL DEFAULT '1',
  `hinhAnhSanPham` varchar(255) NOT NULL,
  `giaSanPham` decimal(10,2) DEFAULT 0.00,
  `soLuotBan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_sanpham`
--

INSERT INTO `tbl_sanpham` (`maSanPham`, `maLoai`, `tenSanPham`, `mieuTaSanPham`, `trangThaiSanPham`, `hinhAnhSanPham`, `giaSanPham`, `soLuotBan`) VALUES
(154, 2, 'Trà sữa matcha kem cheese Oreo', 'Trà sữa matcha kem cheese Oreo, một sự kết hợp độc đáo giữa vị matcha thanh mát, lớp kem cheese béo mặn và topping bánh Oreo giòn rụm.', '1', '1742956355.png', 35000.00, 0),
(155, 2, 'Trà sữa pudding trân châu', 'Trà sữa pudding trân châu, một sự kết hợp độc đáo giữa vị trà sữa béo ngậy, lớp pudding mềm mịn và những viên trân châu dai giòn hấp dẫn.', '1', '1742956447.png', 35000.00, 1),
(156, 2, 'Trà sữa pudding kem cheese', 'Trà sữa pudding kem cheese, một thức uống hấp dẫn với sự kết hợp hoàn hảo giữa trà sữa thơm béo, lớp kem cheese mặn nhẹ và pudding mềm mịn', '1', '1742956483.png', 35000.00, 0),
(157, 2, 'Trà sữa kem trứng trân châu đường đen', 'Trà sữa kem trứng trân châu đường đen, một biến tấu hấp dẫn của trà sữa truyền thống với lớp kem trứng béo mịn phủ trên cùng.', '1', '1742956545.png', 35000.00, 0),
(158, 2, 'Trà sữa trân châu đường đen', 'Sản phẩm trong hình là một ly trà sữa trân châu đường đen, một trong những thức uống hot nhất hiện nay, được yêu thích bởi sự kết hợp hoàn hảo giữa vị ngọt đậm đà, béo thơm và kết cấu dai giòn của trân châu.', '1', '1742956599.png', 40000.00, 0),
(159, 1, 'Cafe sữa nóng', 'Cà phê sữa nóng là một thức uống phổ biến, đặc biệt là ở các quốc gia Đông Nam Á như Việt Nam.', '1', '1742956644.png', 40000.00, 8),
(160, 1, 'Freeze Sô-Cô-La', 'Freeze socola là một loại thức uống kết hợp giữa vị ngọt ngào của socola và cảm giác mát lạnh, thường được ưa chuộng trong các quán cà phê hay cửa hàng đồ uống.', '1', '1742956693.png', 49000.00, 1),
(161, 3, 'Bánh mì Gà Xé Tương Ớt', 'Bánh mì Việt Nam giòn thơm với nhân gà xé, rau, gia vị hòa quyện cùng nước sốt đặc biệt.', '1', '1742956775.png', 30000.00, 0),
(162, 3, 'Bánh Mì Chả Lụa Xá Xíu', 'Bánh mì Việt Nam giòn thơm với chả lụa và thịt xá xíu thơm ngon, kết hợp cùng rau và gia vị, hòa quyện cùng nước sốt độc đáo.', '1', '1742956819.png', 30000.00, 10),
(163, 3, 'Bánh Mì Xíu Mại', 'Bánh mì Việt Nam giòn thơm, với nhân thịt viên hấp dẫn, phủ thêm một lớp nước sốt cà chua ngọt, cùng với rau tươi và gia vị đậm đà.', '1', '1742956861.png', 30000.00, 0),
(164, 3, 'Bánh Mì Thịt Nướng', 'Bánh mì thịt nướng là một món ăn đường phố nổi tiếng của Việt Nam, mang đậm hương vị truyền thống kết hợp giữa thịt nướng thơm ngon và bánh mì giòn rụm.', '1', '1742956888.png', 30000.00, 0),
(165, 3, 'BÁNH SÔ-CÔ-LA', 'Bánh sô-cô-la là một loại bánh ngọt có hương vị chủ đạo từ sô-cô-la hoặc ca-cao, mang đến trải nghiệm ngọt ngào và thơm ngon cho người thưởng thức.', '1', '1742956920.png', 30000.00, 0),
(166, 2, 'Trà Sen Vàng', 'Trà sen vàng là một loại trà nổi tiếng và cao cấp của Việt Nam, được yêu thích bởi hương vị thơm ngon, thanh tao kết hợp với hương sen tự nhiên.', '1', '1742956984.jpg', 45000.00, 0),
(167, 3, 'BÁNH MOUSSE ĐÀO', 'Bánh mousse đào là một loại bánh tráng miệng nổi tiếng nhờ kết cấu nhẹ nhàng, mềm mịn của lớp mousse kết hợp với vị ngọt dịu và hương thơm tươi mát từ trái đào.', '1', '1742957026.png', 30000.00, 0),
(168, 2, 'Trà sữa trân trâu đen', 'Trà sữa trân châu đen là một món đồ uống nổi tiếng và phổ biến, đặc biệt là trong giới trẻ, xuất phát từ Đài Loan và đã lan rộng ra khắp thế giới. ', '1', '1742957108.png', 45000.00, 0),
(169, 2, 'Trà thạch đào', 'Trà thạch đào là một món đồ uống giải khát được yêu thích bởi sự kết hợp giữa vị trà thanh mát và những miếng thạch đào ngọt ngào, mềm dẻo.', '1', '1742957150.png', 45000.00, 0),
(170, 1, 'Coffee Phin Đen Nóng', 'Cà phê phin đen nóng là một trong những nét đặc trưng của văn hóa thưởng thức cà phê Việt Nam, mang đậm hương vị đắng đậm đà, nguyên chất từ hạt cà phê rang xay.', '1', '1742957196.png', 29000.00, 0),
(171, 1, 'Bạc Xỉu Đá', 'Bạc xỉu đá là một loại thức uống quen thuộc trong văn hóa cà phê Việt Nam, có nguồn gốc từ Sài Gòn. ', '1', '1742957430.png', 29000.00, 0),
(172, 3, 'BÁNH CHUỐI', 'Bánh chuối là một món tráng miệng dân dã, phổ biến ở nhiều vùng miền Việt Nam, mang hương vị ngọt ngào và thơm đặc trưng từ chuối chín.', '1', '1742957478.jpg', 29000.00, 0),
(173, 3, 'Bánh Mousse', 'Bánh mousse cacao là một loại bánh tráng miệng tinh tế, kết hợp giữa lớp mousse mịn mượt và vị đắng ngọt thanh của cacao. ', '1', '1742957514.png', 29000.00, 0),
(174, 3, 'Bánh Caramel Phô Mai', 'Ngon khó cưỡng! Bánh phô mai thơm béo được phủ bằng lớp caramel ngọt ngào.', '1', '1742957547.jpg', 29000.00, 0),
(175, 2, 'Trà sữa đậu xanh', 'Trà sữa đậu xanh là một thức uống sáng tạo, kết hợp giữa hương vị trà sữa truyền thống và vị bùi béo đặc trưng của đậu xanh. Dưới đây là mô tả chi tiết về thức uống này.', '1', '1742957654.png', 1000.00, 0),
(176, 2, 'Trà sữa kem muối', 'Trà sữa kem muối caramel, một sự kết hợp đầy tinh tế giữa vị trà sữa thơm béo, lớp kem muối caramel.', '1', '1742957753.png', 40000.00, 0),
(177, 3, 'Bánh Tiramisu', 'Tiramisu thơm béo, làm từ ca-cao Việt Nam đậm đà, kết hợp với phô mai ít béo, vani và chút rum nhẹ nhàng.', '1', '1742957808.jpg', 30000.00, 0),
(178, 1, 'Coffee Phindi hạnh nhân', 'PhinDi hạnh nhân - Cà phê Phin thế hệ mới với chất Phin êm hơn, lần đầu tiên kết hợp cùng Hồng Trà mang đến hương vị quyện êm.', '1', '1742957849.png', 39000.00, 0),
(179, 1, 'Coffee Phindi Kem Sữa', 'PhinDi Kem Sữa - Cà phê Phin thế hệ mới với chất Phin êm hơn, kết hợp cùng Kem Sữa béo ngậy mang đến hương vị mới lạ, không thể hấp dẫn hơn!', '1', '1742957873.png', 39000.00, 0),
(180, 1, '1', '21', '0', '1742958246.png', 1.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sanphamyeuthich`
--

CREATE TABLE `tbl_sanphamyeuthich` (
  `idYeuThich` int(11) NOT NULL,
  `maKhachHang` int(11) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `ngayThem` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_sanphamyeuthich`
--

INSERT INTO `tbl_sanphamyeuthich` (`idYeuThich`, `maKhachHang`, `maSanPham`, `ngayThem`) VALUES
(102, 28, 159, '2025-04-16 13:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_thongtingiaohang1`
--

CREATE TABLE `tbl_thongtingiaohang1` (
  `IDTTGH` int(11) NOT NULL,
  `maKhachHang` int(11) NOT NULL,
  `tenNguoiNhan` varchar(255) NOT NULL,
  `soDienThoai` int(11) NOT NULL,
  `ghiChuKH` varchar(255) NOT NULL,
  `sessionID` varchar(255) NOT NULL,
  `diachi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_thongtingiaohang1`
--

INSERT INTO `tbl_thongtingiaohang1` (`IDTTGH`, `maKhachHang`, `tenNguoiNhan`, `soDienThoai`, `ghiChuKH`, `sessionID`, `diachi`) VALUES
(120, 28, 'Phạm Thanh Vương', 974228483, 'SGU', 'qg85trd278lqombpo8086ppb81', 'Đại học Sài Gòn');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vaitro`
--

CREATE TABLE `tbl_vaitro` (
  `maVaiTro` int(11) NOT NULL,
  `tenVaiTro` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_vaitro`
--

INSERT INTO `tbl_vaitro` (`maVaiTro`, `tenVaiTro`) VALUES
(1, 'admin'),
(2, 'manager');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_chitietdonhang`
--
ALTER TABLE `tbl_chitietdonhang`
  ADD PRIMARY KEY (`IDCTDH`),
  ADD KEY `maDonHang` (`maDonHang`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Indexes for table `tbl_chitiethoadon`
--
ALTER TABLE `tbl_chitiethoadon`
  ADD PRIMARY KEY (`idHD`),
  ADD KEY `maHoaDon` (`maHoaDon`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Indexes for table `tbl_donhang`
--
ALTER TABLE `tbl_donhang`
  ADD PRIMARY KEY (`maDonHang`),
  ADD KEY `maKhachHang` (`maKhachHang`);

--
-- Indexes for table `tbl_giohang`
--
ALTER TABLE `tbl_giohang`
  ADD PRIMARY KEY (`id_giohang`),
  ADD KEY `maSanPham` (`maSanPham`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Indexes for table `tbl_hoadon`
--
ALTER TABLE `tbl_hoadon`
  ADD PRIMARY KEY (`maHoaDon`),
  ADD KEY `maKhachHang` (`maKhachHang`);

--
-- Indexes for table `tbl_khachhang`
--
ALTER TABLE `tbl_khachhang`
  ADD PRIMARY KEY (`maKhachHang`);

--
-- Indexes for table `tbl_loaisanpham`
--
ALTER TABLE `tbl_loaisanpham`
  ADD PRIMARY KEY (`maLoai`);

--
-- Indexes for table `tbl_product_size`
--
ALTER TABLE `tbl_product_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maSanPham` (`maSanPham`);

--
-- Indexes for table `tbl_quantri`
--
ALTER TABLE `tbl_quantri`
  ADD PRIMARY KEY (`tenDangNhap`),
  ADD KEY `maVaiTro` (`maVaiTro`);

--
-- Indexes for table `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  ADD PRIMARY KEY (`maSanPham`),
  ADD KEY `maLoai` (`maLoai`);

--
-- Indexes for table `tbl_sanphamyeuthich`
--
ALTER TABLE `tbl_sanphamyeuthich`
  ADD PRIMARY KEY (`idYeuThich`),
  ADD KEY `maKhachHang` (`maKhachHang`),
  ADD KEY `maSanPham` (`maSanPham`);

--
-- Indexes for table `tbl_thongtingiaohang1`
--
ALTER TABLE `tbl_thongtingiaohang1`
  ADD PRIMARY KEY (`IDTTGH`),
  ADD KEY `maKhachHang` (`maKhachHang`);

--
-- Indexes for table `tbl_vaitro`
--
ALTER TABLE `tbl_vaitro`
  ADD PRIMARY KEY (`maVaiTro`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_chitietdonhang`
--
ALTER TABLE `tbl_chitietdonhang`
  MODIFY `IDCTDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `tbl_chitiethoadon`
--
ALTER TABLE `tbl_chitiethoadon`
  MODIFY `idHD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `tbl_donhang`
--
ALTER TABLE `tbl_donhang`
  MODIFY `maDonHang` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_giohang`
--
ALTER TABLE `tbl_giohang`
  MODIFY `id_giohang` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=566;

--
-- AUTO_INCREMENT for table `tbl_hoadon`
--
ALTER TABLE `tbl_hoadon`
  MODIFY `maHoaDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_khachhang`
--
ALTER TABLE `tbl_khachhang`
  MODIFY `maKhachHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_loaisanpham`
--
ALTER TABLE `tbl_loaisanpham`
  MODIFY `maLoai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_product_size`
--
ALTER TABLE `tbl_product_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  MODIFY `maSanPham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `tbl_sanphamyeuthich`
--
ALTER TABLE `tbl_sanphamyeuthich`
  MODIFY `idYeuThich` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `tbl_thongtingiaohang1`
--
ALTER TABLE `tbl_thongtingiaohang1`
  MODIFY `IDTTGH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `tbl_vaitro`
--
ALTER TABLE `tbl_vaitro`
  MODIFY `maVaiTro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_chitietdonhang`
--
ALTER TABLE `tbl_chitietdonhang`
  ADD CONSTRAINT `tbl_chitietdonhang_ibfk_1` FOREIGN KEY (`maDonHang`) REFERENCES `tbl_donhang` (`maDonHang`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_chitietdonhang_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `tbl_product_size` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_chitiethoadon`
--
ALTER TABLE `tbl_chitiethoadon`
  ADD CONSTRAINT `tbl_chitiethoadon_ibfk_1` FOREIGN KEY (`maHoaDon`) REFERENCES `tbl_hoadon` (`maHoaDon`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_chitiethoadon_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `tbl_product_size` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_donhang`
--
ALTER TABLE `tbl_donhang`
  ADD CONSTRAINT `tbl_donhang_ibfk_1` FOREIGN KEY (`maKhachHang`) REFERENCES `tbl_khachhang` (`maKhachHang`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_giohang`
--
ALTER TABLE `tbl_giohang`
  ADD CONSTRAINT `tbl_giohang_ibfk_1` FOREIGN KEY (`maSanPham`) REFERENCES `tbl_sanpham` (`maSanPham`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giohang_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `tbl_product_size` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_hoadon`
--
ALTER TABLE `tbl_hoadon`
  ADD CONSTRAINT `tbl_hoadon_ibfk_1` FOREIGN KEY (`maKhachHang`) REFERENCES `tbl_khachhang` (`maKhachHang`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_product_size`
--
ALTER TABLE `tbl_product_size`
  ADD CONSTRAINT `tbl_product_size_ibfk_1` FOREIGN KEY (`maSanPham`) REFERENCES `tbl_sanpham` (`maSanPham`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_quantri`
--
ALTER TABLE `tbl_quantri`
  ADD CONSTRAINT `tbl_quantri_ibfk_1` FOREIGN KEY (`maVaiTro`) REFERENCES `tbl_vaitro` (`maVaiTro`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_sanpham`
--
ALTER TABLE `tbl_sanpham`
  ADD CONSTRAINT `tbl_sanpham_ibfk_1` FOREIGN KEY (`maLoai`) REFERENCES `tbl_loaisanpham` (`maLoai`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_sanphamyeuthich`
--
ALTER TABLE `tbl_sanphamyeuthich`
  ADD CONSTRAINT `tbl_sanphamyeuthich_ibfk_1` FOREIGN KEY (`maKhachHang`) REFERENCES `tbl_khachhang` (`maKhachHang`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_sanphamyeuthich_ibfk_2` FOREIGN KEY (`maSanPham`) REFERENCES `tbl_sanpham` (`maSanPham`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_thongtingiaohang1`
--
ALTER TABLE `tbl_thongtingiaohang1`
  ADD CONSTRAINT `tbl_thongtingiaohang1_ibfk_1` FOREIGN KEY (`maKhachHang`) REFERENCES `tbl_khachhang` (`maKhachHang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
