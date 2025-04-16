<?php
session_start();
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $makhachhang = $_SESSION['maKhachHang'];

    if (isset($_POST['price'])) {
        $price = $_POST['price'];
    } else {
        die("Lỗi: Giá trị tổng tiền không được gửi.");
    }

    $sId = session_id();
    $today = date("Y/m/d");

    // Lấy mã đơn hàng mới
    $queryMDH = mysqli_query($conn, "SELECT MAX(maDonHang) FROM tbl_donhang");
    $fetchMDH = mysqli_fetch_assoc($queryMDH);
    $dataMDHNew = $fetchMDH['MAX(maDonHang)'] != NULL ? $fetchMDH['MAX(maDonHang)'] + 1 : 1;

    // Lấy thông tin giao hàng từ tbl_thongtingiaohang1
    $queryIDTTGH = mysqli_query($conn, "SELECT * FROM tbl_thongtingiaohang1 
                                        WHERE IDTTGH = (SELECT MAX(IDTTGH) FROM tbl_thongtingiaohang1 WHERE sessionID = '$sId') 
                                        AND sessionID = '$sId'");
    $dataTTGH = mysqli_fetch_assoc($queryIDTTGH);

    $tenNN = $dataTTGH['tenNguoiNhan'];
    $SDTKH = $dataTTGH['soDienThoai'];
    $diachiNN = $dataTTGH['diachi'];
    $ghiChu = $dataTTGH['ghiChuKH'];

    // Lấy thông tin giỏ hàng với kích thước từ tbl_product_size
    $queryGH = mysqli_query($conn, "SELECT g.*, ps.size, ps.giaSanPham, ps.soLuongSanPham AS stock 
                                    FROM tbl_giohang g 
                                    JOIN tbl_product_size ps ON g.product_size_id = ps.id 
                                    WHERE g.sessionID = '$sId'");

    // Thêm vào tbl_donhang
    $donhang = mysqli_query($conn, "INSERT INTO `tbl_donhang` (`maDonHang`, `maKhachHang`, `ngayLapDH`, `tongTienDH`, `trangThaiDH`)
                                    VALUES ('$dataMDHNew', '$makhachhang', '$today', '$price', 'Chưa giao')");

    if (!$donhang) {
        die("Lỗi khi thêm đơn hàng: " . mysqli_error($conn));
    }

    $chieuDaiGH = mysqli_num_rows($queryGH);

    while ($dataGH = mysqli_fetch_array($queryGH)) {
        $maSP = $dataGH['maSanPham'];
        $tenSP = $dataGH['tenSanPham'];
        $SLSP = $dataGH['soLuongSanPham'];
        $sizeSP = $dataGH['size'];
        $giaSP = $dataGH['giaSanPham'];
        $mieutaSP = $dataGH['mieuTaSanPham'];
        $hinhanhSP = $dataGH['hinhAnhSanPham'];
        $product_size_id = $dataGH['product_size_id'];

        // Thêm vào tbl_chitietdonhang với product_size_id thay vì sizeSanPham
        $chitietdonhang = mysqli_query($conn, "INSERT INTO `tbl_chitietdonhang` 
                                               (`maDonHang`, `tenNguoiNhan`, `sdtKH`, `ghiChuCuaKhachhang`, `maSanPham`, `tenSanPham`, `product_size_id`, `mieuTaSP`, `hinhAnhSP`, `diachi`)
                                               VALUES ('$dataMDHNew', '$tenNN', '$SDTKH', '$ghiChu', '$maSP', '$tenSP', '$product_size_id', '$mieutaSP', '$hinhanhSP', '$diachiNN')");

        if (!$chitietdonhang) {
            die("Lỗi khi thêm chi tiết đơn hàng: " . mysqli_error($conn));
        }

        $capnhatSL = mysqli_query($conn, "UPDATE tbl_product_size 
                                          SET soLuongSanPham = soLuongSanPham - $SLSP 
                                          WHERE id = '$product_size_id'");

        if (!$capnhatSL) {
            die("Lỗi khi cập nhật số lượng tồn kho: " . mysqli_error($conn));
        }

        $capnhatLuotBan = mysqli_query($conn, "UPDATE tbl_sanpham 
                                              SET soLuotBan = soLuotBan + $SLSP 
                                              WHERE maSanPham = '$maSP'");

        if (!$capnhatLuotBan) {
            die("Lỗi khi cập nhật số lượt bán: " . mysqli_error($conn));
        }

    }

    // Xóa giỏ hàng sau khi đặt hàng thành công
    $xoaGioHang = mysqli_query($conn, "DELETE FROM tbl_giohang WHERE sessionID = '$sId'");
    if ($xoaGioHang) {
        $_SESSION['soluong'] = 0;
        header("location:order_status.php");
        exit();
    } else {
        die("Lỗi khi xóa giỏ hàng: " . mysqli_error($conn));
    }
}
?>