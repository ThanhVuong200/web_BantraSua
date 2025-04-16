<?php 
include_once 'config.php'; // Include file kết nối cơ sở dữ liệu
include_once 'classes/category.php';
include_once 'classes/product.php';
include_once 'admin/helpers/format.php';

session_start(); // Bắt đầu session để lấy thông tin khách hàng đăng nhập

if (!isset($_GET['maLoai']) || $_GET['maLoai'] == '' || $_GET['maLoai'] > 14 || $_GET['maLoai'] < 0) {
    echo "<script>window.location = '404.php'</script>";
} else {
    $idLoai = $_GET['maLoai'];
}

$fm = new Format();
$prod = new product();
$category = new category();
$catList = $category->getcatbyId($idLoai);
$resultCat = $catList->fetch_assoc();

// Lấy maKhachHang từ session
$maKhachHang = isset($_SESSION['maKhachHang']) ? $_SESSION['maKhachHang'] : null;

$pageTitle = "" . $resultCat['tenLoai'] . " | Company Coffee - Cà Hê Ngon";
function customPageHeader() { ?>
    <title><?php global $pageTitle; echo $pageTitle; ?></title>
<?php }

include 'header.php';
?>

<!-- MAIN-CONTENT-SECTION START -->
<section class="main-content-section">
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 20px;"></div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <!-- PRODUCT-LEFT-SIDEBAR START -->
                <div class="product-left-sidebar">
                    <h2 class="left-title pro-g-page-title">Mục lục</h2>
                    <!-- SINGLE SIDEBAR ENABLED FILTERS START -->
                    <div class="product-single-sidebar">
                        <span class="sidebar-title">LOẠI ĐÃ LỌC: </span>
                        <ul class="filtering-menu">
                            <li> <?php echo $resultCat['tenLoai']; ?></li>
                        </ul>
                    </div>
                    <!-- Thêm dropdown lọc giá -->
                    <div class="product-sort" style="margin-bottom: 20px;">
                        <form method="GET" action="">
                            <input type="hidden" name="maLoai" value="<?php echo $resultCat['maLoai']; ?>">
                            <label for="sortPrice" class="sortt" >Sắp xếp theo giá: </label>
                            <select name="sortPrice" id="sortPrice" onchange="this.form.submit()">
                                <option value="">Mặc định</option>
                                <option value="low_to_high" <?php if (isset($_GET['sortPrice']) && $_GET['sortPrice'] == 'low_to_high') echo 'selected'; ?>>Thấp đến cao</option>
                                <option value="high_to_low" <?php if (isset($_GET['sortPrice']) && $_GET['sortPrice'] == 'high_to_low') echo 'selected'; ?>>Cao đến thấp</option>
                            </select>
                        </form>
                    </div>
                    <!-- SINGLE SIDEBAR ENABLED FILTERS END -->
                </div>
                <!-- PRODUCT-LEFT-SIDEBAR END -->
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="right-all-product">
                    <div class="product-category-title">
                        <!-- PRODUCT-CATEGORY-TITLE START -->
                        <h1>
                            <span class="cat-name"><?php echo $resultCat['tenLoai']; ?></span>
                        </h1>
                        <!-- PRODUCT-CATEGORY-TITLE END -->
                    </div>
                    
                </div>
                <!-- ALL GATEGORY-PRODUCT START -->
                <div class="all-gategory-product">
                    <div class="row">
                        <ul class="gategory-product">
                            <?php 
                            $prodList = $prod->show_productbyCat($resultCat['maLoai']);
                            if ($prodList) {
                                while ($resultProd = $prodList->fetch_assoc()) {
                                    if ($resultProd['trangThaiSanPham'] == '1') {
                                        $maSanPham = $resultProd['maSanPham'];
                                        $priceQuery = mysqli_query($conn, "SELECT MIN(giaSanPham) as minPrice FROM tbl_product_size WHERE maSanPham = $maSanPham");
                                        $priceData = mysqli_fetch_assoc($priceQuery);
                                        $giaSanPham = $priceData['minPrice'] ?? 0;

                                        // Kiểm tra xem sản phẩm đã được yêu thích chưa
                                        $isFavorite = false;
                                        if ($maKhachHang) {
                                            $checkFavoriteQuery = mysqli_query($conn, "SELECT * FROM tbl_sanphamyeuthich WHERE maKhachHang = $maKhachHang AND maSanPham = $maSanPham");
                                            $isFavorite = mysqli_num_rows($checkFavoriteQuery) > 0;
                                        }
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
                                        <div class="customar-comments-box">
                                            <!-- Thêm hình trái tim -->
                                            <span class="favorite-icon" 
                                                  data-product-id="<?php echo $resultProd['maSanPham']; ?>" 
                                                  style="cursor: pointer; color: <?php echo $isFavorite ? 'red' : 'grey'; ?>;">
                                                <i class="fa fa-heart"></i>
                                            </span>
                                        </div>
                                        <a href="single-product.php?maSanPham=<?= $resultProd['maSanPham']; ?>">
                                            <span style="
                                                display: inline-block;
                                                max-width: 100%;
                                                white-space: nowrap;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                                text-transform: uppercase;
                                            ">
                                                <?= htmlspecialchars($fm->textShorten($resultProd['tenSanPham'], 400)); ?>
                                            </span>
                                        </a>

                                        <div class="price-box">
                                            <span class="price"><?php echo number_format($giaSanPham); ?> VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- SINGLE ITEM END -->
                            <?php 
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- ALL GATEGORY-PRODUCT END -->
                <!-- PRODUCT-SHOOTING-RESULT START -->
                <div class="product-shooting-result product-shooting-result-border">
                    <div class="phanTrang">
                        <?php 
                        $productAll = $prod->getAllProductbyCat($resultCat['maLoai']);
                        $productCount = mysqli_num_rows($productAll); // Đếm số dòng
                        $productButton = ceil($productCount / 8); // Số button, 8 sản phẩm/trang
                        if (!isset($_GET['trang'])) {
                            $trangHienTai = 1;
                        } else {
                            $trangHienTai = $_GET['trang'];
                        }

                        $sortPrice = isset($_GET['sortPrice']) ? $_GET['sortPrice'] : ''; // Lấy sortPrice từ URL

                        if (isset($_GET['size']) && !empty($_GET['size'])) {
                            $sizeSP = $_GET['size'];
                            // Button Prev
                            if ($trangHienTai > 1 && $productButton > 1) {
                                echo '<a href="?maLoai='.$resultCat['maLoai'].'&size='.$sizeSP.'&trang='.($trangHienTai - 1).'&sortPrice='.$sortPrice.'"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
                            }
                            // Create Buttons
                            for ($i = 1; $i <= $productButton; $i++) {
                                if ($i == $trangHienTai) {
                                    echo '<a href="?maLoai='.$resultCat['maLoai'].'&size='.$sizeSP.'&trang='.$i.'&sortPrice='.$sortPrice.'" style="background-color: grey;">'.$i.'</a>';
                                } else {
                                    echo '<a href="?maLoai='.$resultCat['maLoai'].'&size='.$sizeSP.'&trang='.$i.'&sortPrice='.$sortPrice.'">'.$i.'</a>';
                                }
                            }
                            // Button Next
                            if ($trangHienTai < $productButton && $productButton > 1) {
                                echo '<a href="?maLoai='.$resultCat['maLoai'].'&size='.$sizeSP.'&trang='.($trangHienTai + 1).'&sortPrice='.$sortPrice.'">Trang sau <i class="fa fa-angle-double-right"></i></a>';
                            }
                        } else {
                            // Button Prev
                            if ($trangHienTai > 1 && $productButton > 1) {
                                echo '<a href="?maLoai='.$resultCat['maLoai'].'&trang='.($trangHienTai - 1).'&sortPrice='.$sortPrice.'"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
                            }
                            // Create Buttons
                            for ($i = 1; $i <= $productButton; $i++) {
                                if ($i == $trangHienTai) {
                                    echo '<a href="?maLoai='.$resultCat['maLoai'].'&trang='.$i.'&sortPrice='.$sortPrice.'" style="background-color: grey;">'.$i.'</a>';
                                } else {
                                    echo '<a href="?maLoai='.$resultCat['maLoai'].'&trang='.$i.'&sortPrice='.$sortPrice.'">'.$i.'</a>';
                                }
                            }
                            // Button Next
                            if ($trangHienTai < $productButton && $productButton > 1) {
                                echo '<a href="?maLoai='.$resultCat['maLoai'].'&trang='.($trangHienTai + 1).'&sortPrice='.$sortPrice.'">Trang sau <i class="fa fa-angle-double-right"></i></a>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <!-- PRODUCT-SHOOTING-RESULT END -->
            </div>
        </div>
    </div>
</section>
<!-- MAIN-CONTENT-SECTION END -->
<?php include 'footer.php'; ?>

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
    .favorite-icon {
        font-size: 20px;
        margin-right: 10px;
        position: absolute;
        top: 175px;
        right: 10px;
    }
    .product-image {
        position: relative;
    }
    .product-sort{
        margin-top: 20px;
        margin-bottom: 30px;
        padding: 15px;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
    }
    .product-sort select {
        width: 100%;
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg fill="black" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 8px center;
        transition: all 0.3s ease;
    }
    .product-sort select:hover {
        border-color: #DBC5A4;
    }
    .product-sort select:focus {
        outline: none;
        border-color: #DBC5A4;
        box-shadow: 0 0 5px rgba(219, 197, 164, 0.3);
    }
    .sortt{
        display: block;
        color: #333;
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 10px;
        font-family: 'Bitter', serif;
        text-transform: uppercase;
    }
    .left-title {
        display: block;
        color: #333;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        font-family: 'Bitter', serif;
        text-transform: uppercase;
        padding-bottom: 10px;
        border-bottom: 2px solid #DBC5A4;
    }

    .product-single-sidebar {
        margin-top: 20px;
        margin-bottom: 30px;
        padding: 15px;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
    }

    .sidebar-title {
        display: block;
        color: #333;
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 10px;
        font-family: 'Bitter', serif;
        text-transform: uppercase;
    }

    .filtering-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .filtering-menu li {
        padding: 8px 12px;
        margin: 5px 0;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #666;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .filtering-menu li:hover {
        border-color: #DBC5A4;
        background: #fff;
        box-shadow: 0 0 5px rgba(219, 197, 164, 0.3);
    }
</style>

<!-- JS -->
<script src="js/vendor/jquery-1.11.3.min.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/jquery.bxslider.min.js"></script>
<script src="js/jquery.meanmenu.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.nivo.slider.js"></script>
<script src="js/jqueryui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/wow.js"></script>
<script>
new WOW().init();

// Xử lý sự kiện nhấn vào trái tim
$(document).ready(function() {
    $('.favorite-icon').on('click', function() {
        var productId = $(this).data('product-id');
        var icon = $(this);

        $.ajax({
            url: 'toggle-favorite.php',
            type: 'POST',
            data: { maSanPham: productId },
            success: function(response) {
                if (response === 'added') {
                    icon.css('color', 'red'); // Đổi màu trái tim thành đỏ
                } else if (response === 'removed') {
                    icon.css('color', 'grey'); // Đổi màu trái tim thành xám
                } else if (response === 'login_required') {
                    alert('Vui lòng đăng nhập để thêm sản phẩm yêu thích!');
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                }
            }
        });
    });
});
</script>
<script src="js/main.js"></script>
</body>
</html>