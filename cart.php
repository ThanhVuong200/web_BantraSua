<?php
session_start();
$pageTitle = "GIỎ HÀNG | Company Coffee - Cà Hê Ngon";
function customPageHeader() {
    global $pageTitle;
    echo "<title>$pageTitle</title>";
}
include_once 'config.php';
include 'header.php';

// Kiểm tra xem session có chứa giỏ hàng hay không
$sId = session_id();

// Xử lý khi xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['id_cart']) && isset($_GET['size_id'])) {
    $id_cart = $_GET['id_cart']; // maSanPham
    $size_id = $_GET['size_id']; // product_size_id
    $deleteQuery = mysqli_query($conn, "DELETE FROM `tbl_giohang` 
                                        WHERE `maSanPham`='$id_cart' AND `product_size_id`='$size_id' AND `sessionID`='$sId'");
    if ($deleteQuery) {
        echo "<script>window.location = 'cart.php';</script>";
    }
}

// Xử lý giảm số lượng sản phẩm
if (isset($_GET['maSPTru']) && isset($_GET['soluonght']) && isset($_GET['size_id'])) {
    $maSPTru = $_GET['maSPTru'];
    $soluonght = $_GET['soluonght'];
    $size_id = $_GET['size_id']; // Lấy product_size_id từ URL

    $queryTru = mysqli_query($conn, "SELECT `soLuongSanPham`, `maSanPham`, `product_size_id`, `sessionID` 
                                     FROM `tbl_giohang` 
                                     WHERE `sessionID`='$sId' AND `maSanPham`='$maSPTru' AND `product_size_id`='$size_id'");
    $rows = mysqli_fetch_assoc($queryTru);

    if ($rows && $soluonght > 1) {
        $updateQuery = mysqli_query($conn, "UPDATE tbl_giohang 
                                            SET soLuongSanPham = $soluonght - 1 
                                            WHERE `sessionID`='$sId' AND `maSanPham`='$maSPTru' AND `product_size_id`='$size_id'");
        if ($updateQuery) {
            echo "<script>window.location = 'cart.php';</script>";
        }
    } else if ($rows) {
        $deleteQuery = mysqli_query($conn, "DELETE FROM `tbl_giohang` 
                                            WHERE `maSanPham`='$maSPTru' AND `product_size_id`='$size_id' AND `sessionID`='$sId'");
        if ($deleteQuery) {
            echo "<script>window.location = 'cart.php';</script>";
        }
    }
}

// Xử lý tăng số lượng sản phẩm
if (isset($_GET['maSPCong']) && isset($_GET['soluonght']) && isset($_GET['size_id'])) {
    $maSPCong = $_GET['maSPCong'];
    $soluonght = $_GET['soluonght'];
    $size_id = $_GET['size_id']; // Lấy product_size_id từ URL

    // Lấy số lượng tồn kho từ tbl_product_size cho product_size_id cụ thể
    $querySLHC = mysqli_query($conn, "SELECT ps.soLuongSanPham 
                                      FROM tbl_giohang g 
                                      JOIN tbl_product_size ps ON g.product_size_id = ps.id 
                                      WHERE g.sessionID='$sId' AND g.maSanPham='$maSPCong' AND g.product_size_id='$size_id'");
    $resultSLHC = mysqli_fetch_assoc($querySLHC);
    $soluonghienco = $resultSLHC['soLuongSanPham'] ?? 0;

    if ($soluonght < $soluonghienco) {
        $updateQuery = mysqli_query($conn, "UPDATE tbl_giohang 
                                            SET soLuongSanPham = $soluonght + 1 
                                            WHERE `sessionID`='$sId' AND `maSanPham`='$maSPCong' AND `product_size_id`='$size_id'");
        if ($updateQuery) {
            echo "<script>window.location = 'cart.php';</script>";
        }
    } else {
        echo "<script>alert('Số lượng trong giỏ hàng vượt quá số lượng tồn kho!'); window.location = 'cart.php';</script>";
    }
}
?>

<!-- MAIN CONTENT SECTION -->
<section class="main-content-section">
    <div class="container">
        <h2 class="page-title">GIỎ HÀNG</h2>
        <div class="table-responsive">
            <table class="table table-bordered" id="cart-summary">
                <thead>
                    <tr>
                        <th class="cart-product">Sản phẩm</th>
                        <th class="cart-description">Miêu tả sản phẩm</th>
                        <th class="cart-avail text-center">Tình trạng hàng</th>
                        <th class="cart-unit text-right">Đơn giá</th>
                        <th class="cart-size text-center">Kích thước</th>
                        <th class="cart_quantity text-center">Số lượng</th>
                        <th class="cart-delete"> </th>
                        <th class="cart-total text-right">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Truy vấn tất cả sản phẩm trong giỏ hàng với thông tin từ tbl_product_size
                    $danhsach = mysqli_query($conn, "SELECT g.*, ps.giaSanPham, ps.size, ps.soLuongSanPham AS stock 
                                                    FROM tbl_giohang g 
                                                    JOIN tbl_product_size ps ON g.product_size_id = ps.id 
                                                    WHERE g.sessionID='$sId'");
                    $sup_total = 0;
                    while ($rows = mysqli_fetch_assoc($danhsach)) {
                    ?>
                    <tr>
                        <td class="cart-product">
                            <a href="#"><img alt="Blouse" src="admin/pages/uploads/<?php echo $rows['hinhAnhSanPham']; ?>"></a>
                        </td>
                        <td class="cart-description">
                            <p class="product-name"><a href="#"><?php echo $rows['tenSanPham']; ?></a></p>
                        </td>
                        <td class="cart-avail text-center">
                            <span class="label <?php echo $rows['stock'] > 0 ? 'label-success' : 'label-danger'; ?>">
                                <?php echo $rows['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
                            </span>
                        </td>
                        <td class="cart-unit text-right">
                            <ul class="price text-right">
                                <li class="price special-price"><?php echo number_format($rows['giaSanPham']); ?> VNĐ</li>
                            </ul>
                        </td>
                        <td class="cart-size text-center">
                            <?php echo $rows['size']; ?>
                        </td>
                        <td class="cart_quantity text-center">
                            <input class="cart-plus-minus" type="text" name="quantybutton" value="<?php echo $rows['soLuongSanPham']; ?>" readonly="readonly">
                            <a href="?maSPTru=<?php echo $rows['maSanPham']; ?>&soluonght=<?php echo $rows['soLuongSanPham']; ?>&size_id=<?php echo $rows['product_size_id']; ?>">
                                <div class="dec qtybutton" name="dec">-</div>
                            </a>
                            <a href="?maSPCong=<?php echo $rows['maSanPham']; ?>&soluonght=<?php echo $rows['soLuongSanPham']; ?>&size_id=<?php echo $rows['product_size_id']; ?>">
                                <div class="inc qtybutton" name="inc">+</div>
                            </a>
                        </td>
                        <td class="cart-delete text-center">
                            <a href="?id_cart=<?php echo $rows['maSanPham']; ?>&size_id=<?php echo $rows['product_size_id']; ?>" class="cart_quantity_delete" title="Xóa">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                        <td class="cart-total text-right">
                            <?php 
                            $total = $rows['giaSanPham'] * $rows['soLuongSanPham'];
                            $sup_total += $total;
                            ?>
                            <span class="price"><?php echo number_format($total); ?> VNĐ</span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="cart-total-price">
                        <td class="setup" colspan="4" rowspan="4"></td>
                        <td class="text-right" colspan="3">Tổng thanh toán:</td>
                        <td id="total_product" class="price" colspan="1"><?php echo number_format($sup_total); ?> VNĐ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="returne-continue-shop">
            <?php
            if (isset($_SESSION['soluong']) && $_SESSION['soluong'] > 0) {
                if (isset($_SESSION['ten'])) {
                    echo '<a href="checkout-address.php" class="continueshoping"><input type="submit" class="procedtocheckout" value="Thanh Toán" style="color: white;"></a>';
                } else {
                    echo '<a href="registration.php" class="continueshoping"><input type="submit" class="procedtocheckout" value="Thanh Toán" style="color: white;"></a>';
                }
            } else {
                echo '<input type="button" class="procedtocheckout" value="Thanh Toán" style="color: white;" onClick="alert(\'Chưa có sản phẩm trong giỏ hàng\')">';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- Include JS files -->
<script src="js/vendor/jquery-1.11.3.min.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/jquery.bxslider.min.js"></script>
<script src="js/jquery.meanmenu.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.nivo.slider.js"></script>
<script src="js/jqueryui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/wow.js"></script>
<script>new WOW().init();</script>
<script src="js/main.js"></script>
</body>
</html>