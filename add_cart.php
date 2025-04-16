<?php
session_start();
include_once 'config.php'; // Kết nối với cơ sở dữ liệu

// Kiểm tra $conn
if (!$conn) {
    die("Lỗi: Không thể kết nối tới cơ sở dữ liệu. Kiểm tra config.php.");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_GET['id']) && isset($_POST['product_size_id']) && isset($_POST['qtybutton'])) {
        $id = $_GET['id']; // maSanPham
        $product_size_id = $_POST['product_size_id']; // ID kích thước từ form
        $qty = (int)$_POST['qtybutton']; // Số lượng từ form (ép kiểu thành số nguyên)

        $sId = session_id(); // Lấy session ID

        // Lấy thông tin chi tiết từ tbl_product_size
        $query = "SELECT s.maSanPham, s.tenSanPham, s.mieuTaSanPham, s.hinhAnhSanPham, s.maLoai, 
                         ps.size, ps.giaSanPham, ps.soLuongSanPham 
                  FROM tbl_sanpham s 
                  JOIN tbl_product_size ps ON s.maSanPham = ps.maSanPham 
                  WHERE s.maSanPham = ? AND ps.id = ?";
        
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $error = $conn->error;
            die("Lỗi prepare truy vấn: $error");
        }

        $stmt->bind_param("ii", $id, $product_size_id);
        $stmt->execute();
        $product = $stmt->get_result();

        if ($product && $product->num_rows > 0) {
            $row = $product->fetch_assoc();
            $maLoai = $row['maLoai'];
            $tenSanPham = $row['tenSanPham'];
            $size = $row['size'];
            $mieuTaSanPham = $row['mieuTaSanPham'];
            $giaSanPham = $row['giaSanPham'];
            $hinhAnhSanPham = $row['hinhAnhSanPham'];
            $slspHienCo = $row['soLuongSanPham'];

            // Kiểm tra số lượng sản phẩm có đủ trong kho
            if ($qty > 0 && $qty <= $slspHienCo) {
                // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
                $checkStmt = $conn->prepare("SELECT soLuongSanPham 
                                             FROM tbl_giohang 
                                             WHERE sessionID = ? AND maSanPham = ? AND product_size_id = ?");
                if ($checkStmt === false) {
                    $error = $conn->error;
                    die("Lỗi prepare kiểm tra trùng lặp: $error");
                }

                $checkStmt->bind_param("sii", $sId, $id, $product_size_id);
                $checkStmt->execute();
                $resultExist = $checkStmt->get_result();

                if ($resultExist && $resultExist->num_rows > 0) {
                    // Sản phẩm đã tồn tại, cộng dồn số lượng
                    $existing = $resultExist->fetch_assoc();
                    $newQty = $existing['soLuongSanPham'] + $qty;

                    if ($newQty <= $slspHienCo) {
                        $updateStmt = $conn->prepare("UPDATE tbl_giohang 
                                                      SET soLuongSanPham = ? 
                                                      WHERE sessionID = ? AND maSanPham = ? AND product_size_id = ?");
                        if ($updateStmt === false) {
                            $error = $conn->error;
                            die("Lỗi prepare cập nhật: $error");
                        }

                        $updateStmt->bind_param("isii", $newQty, $sId, $id, $product_size_id);
                        $success = $updateStmt->execute();
                    } else {
                        echo "<script>alert('Số lượng vượt quá tồn kho!'); 
                              window.location = 'single-product.php?maSanPham=$id';</script>";
                        exit();
                    }
                } else {
                    // Sản phẩm chưa tồn tại, thêm mới
                    $insertStmt = $conn->prepare("INSERT INTO `tbl_giohang` 
                                                  (`maSanPham`, `soLuongSanPham`, `sessionID`, `maLoai`, `tenSanPham`, `product_size_id`, `mieuTaSanPham`, `hinhAnhSanPham`)
                                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    if ($insertStmt === false) {
                        $error = $conn->error;
                        die("Lỗi prepare thêm mới: $error");
                    }

                    $insertStmt->bind_param("iisisiss", $id, $qty, $sId, $maLoai, $tenSanPham, $product_size_id, $mieuTaSanPham, $hinhAnhSanPham);
                    $success = $insertStmt->execute();
                }

                if ($success) {
                    // Chuyển hướng về trang sản phẩm sau khi thêm thành công
                    header("Location: single-product.php?maSanPham=$id");
                    exit();
                } else {
                    $error = $conn->error;
                    echo "<script>alert('Thêm vào giỏ hàng thất bại! Lỗi: " . addslashes($error) . "'); 
                          window.location = 'single-product.php?maSanPham=$id';</script>";
                }
            } else {
                echo "<script>alert('Số lượng không hợp lệ hoặc vượt quá tồn kho!'); 
                      window.location = 'single-product.php?maSanPham=$id';</script>";
            }
        } else {
            echo "<script>alert('Không tìm thấy sản phẩm hoặc kích thước!'); 
                  window.location = 'single-product.php?maSanPham=$id';</script>";
        }
    } else {
        echo "<script>alert('Thông tin sản phẩm không đầy đủ!'); 
              window.location = 'single-product.php?maSanPham=$id';</script>";
    }
}

mysqli_close($conn);
?>