<?php
$pageTitle = "Kết quả tìm kiếm | Company Coffee - Cà Hê Ngon";
function customPageHeader() {
    global $pageTitle;
    echo "<title>$pageTitle</title>";
}

include 'header.php';
require_once 'classes/product.php';
require_once 'admin/helpers/format.php';

$prod = new product();
$fm = new Format();

// Kiểm tra và lấy từ khóa tìm kiếm
if (isset($_GET['nameSearch']) && !empty(trim($_GET['nameSearch']))) {
    $nameSearch = $_GET['nameSearch'];
} else {
    echo "<script>window.location = '404.php';</script>";
    exit();
}
?>

<div class="all-gategory-product" style="width: 90%; margin-left: 5%; margin-bottom: 6%;">
    <div class="row">
        <ul class="gategory-product">
            <?php
            $prodList = $prod->show_search_result($nameSearch);
            if ($prodList && $prodList->num_rows > 0) {
                while ($resultProd = $prodList->fetch_assoc()) {
                    if ($resultProd['trangThaiSanPham'] == '1') {
            ?>
                        <!-- SINGLE ITEM START -->
                        <li class="gategory-product-list col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="single-product-item">
                                <div class="product-image">
                                    <a href="single-product.php?maSanPham=<?php echo $resultProd['maSanPham']; ?>">
                                        <img src="admin/pages/uploads/<?php echo $resultProd['hinhAnhSanPham']; ?>" alt="product-image" />
                                    </a>
                                </div>
                                <div class="product-info">
                                    <a href="single-product.php?maSanPham=<?php echo $resultProd['maSanPham']; ?>">
                                        <span style="text-transform: uppercase;">
                                            <?php echo $fm->textShorten($resultProd['tenSanPham'], 30); ?>
                                        </span>
                                    </a>
                                    <div class="price-box">
                                        <span class="price">
                                            <?php 
                                            // Lấy giá từ MIN(giaSanPham) trong tbl_product_size, giống index.php
                                            $maSanPham = $resultProd['maSanPham'];
                                            $priceQuery = mysqli_query($conn, "SELECT MIN(giaSanPham) as minPrice FROM tbl_product_size WHERE maSanPham = '$maSanPham'");
                                            $giaSanPham = 0;
                                            if ($priceQuery && $priceData = mysqli_fetch_assoc($priceQuery)) {
                                                $giaSanPham = (float)($priceData['minPrice'] ?? 0);
                                            }
                                            if ($giaSanPham <= 0) {
                                                error_log("giaSanPham không tồn tại hoặc là 0 cho maSanPham: " . $maSanPham);
                                                echo "195,000 VNĐ"; // Giá mặc định, hiển thị với định dạng hàng nghìn
                                            } else {
                                                echo number_format($giaSanPham, 0, '.', ',') . ' VNĐ';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- SINGLE ITEM END -->
            <?php
                    }
                }
            } else {
                echo '<script>alert("Không tìm thấy dữ liệu!"); history.back();</script>';
            }
            ?>
        </ul>
    </div>
    <div class="phanTrang">
        <?php
        $productAll = $prod->getAllProductSearch($nameSearch);
        $productCount = $productAll ? mysqli_num_rows($productAll) : 0;
        $productButton = ceil($productCount / 8);
        $trangHienTai = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;

        // Button Prev
        if ($trangHienTai > 1 && $productButton > 1) {
            echo '<a href="?nameSearch=' . urlencode($nameSearch) . '&search=Tìm+kiếm&trang=' . ($trangHienTai - 1) . '"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
        }

        // Create Buttons
        for ($i = 1; $i <= $productButton; $i++) {
            if ($i == $trangHienTai) {
                echo '<a href="?nameSearch=' . urlencode($nameSearch) . '&search=Tìm+kiếm&trang=' . $i . '" style="background-color: grey;">' . $i . '</a>';
            } else {
                echo '<a href="?nameSearch=' . urlencode($nameSearch) . '&search=Tìm+kiếm&trang=' . $i . '">' . $i . '</a>';
            }
        }

        // Button Next
        if ($trangHienTai < $productButton && $productButton > 1) {
            echo '<a href="?nameSearch=' . urlencode($nameSearch) . '&search=Tìm+kiếm&trang=' . ($trangHienTai + 1) . '">Trang sau <i class="fa fa-angle-double-right"></i></a>';
        }
        ?>
    </div>
</div>

<style type="text/css">
    .phanTrang a {
        text-decoration: none;
        cursor: pointer;
        color: black;
        float: left;
        padding: 5px 15px;
        border: 1px solid #999499;
        margin: 0px 2px 5px;
    }

    .phanTrang a:hover {
        background-color: grey;
        transition: 500ms;
    }
</style>

<?php
include 'footer.php';
?>