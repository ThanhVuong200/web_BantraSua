<?php
session_start();
include_once 'config.php'; // Include file kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['maKhachHang'])) {
    echo 'login_required';
    exit;
}

$maKhachHang = $_SESSION['maKhachHang'];
$maSanPham = isset($_POST['maSanPham']) ? (int)$_POST['maSanPham'] : 0;

// Kiểm tra xem sản phẩm đã được yêu thích chưa
$checkQuery = mysqli_prepare($conn, "SELECT * FROM tbl_sanphamyeuthich WHERE maKhachHang = ? AND maSanPham = ?");
mysqli_stmt_bind_param($checkQuery, "ii", $maKhachHang, $maSanPham);
mysqli_stmt_execute($checkQuery);
$result = mysqli_stmt_get_result($checkQuery);
$isFavorite = mysqli_num_rows($result) > 0;

if ($isFavorite) {
    // Nếu đã yêu thích, xóa khỏi danh sách yêu thích
    $deleteQuery = mysqli_prepare($conn, "DELETE FROM tbl_sanphamyeuthich WHERE maKhachHang = ? AND maSanPham = ?");
    mysqli_stmt_bind_param($deleteQuery, "ii", $maKhachHang, $maSanPham);
    if (mysqli_stmt_execute($deleteQuery)) {
        echo 'removed';
    } else {
        echo 'error';
    }
    mysqli_stmt_close($deleteQuery);
} else {
    // Nếu chưa yêu thích, thêm vào danh sách yêu thích
    $insertQuery = mysqli_prepare($conn, "INSERT INTO tbl_sanphamyeuthich (maKhachHang, maSanPham) VALUES (?, ?)");
    mysqli_stmt_bind_param($insertQuery, "ii", $maKhachHang, $maSanPham);
    if (mysqli_stmt_execute($insertQuery)) {
        echo 'added';
    } else {
        echo 'error';
    }
    mysqli_stmt_close($insertQuery);
}

mysqli_stmt_close($checkQuery);
mysqli_close($conn);
?>