<?php 
include_once 'config.php';  
include_once 'classes/category.php';
include_once 'classes/product.php';
include_once 'admin/helpers/format.php';

session_start();

$fm = new Format();
$prod = new product();
$category = new category();

// Lấy maKhachHang từ session
$maKhachHang = isset($_SESSION['maKhachHang']) ? $_SESSION['maKhachHang'] : null;

$pageTitle = "Sản Phẩm Yêu Thích | Company Coffee - Cà Hê Ngon";
function customPageHeader() { ?>
    <title><?php global $pageTitle; echo $pageTitle; ?></title>
<?php }

include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php customPageHeader(); ?>
    <!-- Các file CSS khác nếu có từ header.php -->
</head>
<body>
<!-- MAIN-CONTENT-SECTION START -->
<section class="main-content-section">
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 10px;"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="right-all-product">
                    <div class="product-category-title">
                        <h1>
                            <span class="cat-name">SẢN PHẨM YÊU THÍCH</span>
                        </h1>
                    </div>
                </div>
                <div class="all-gategory-product">
                    <div class="row">
                        <ul class="gategory-product">
                            <?php 
                            if ($maKhachHang) {
                                $favoriteList = $prod->getFavoriteProducts($maKhachHang);
                                
                                if ($favoriteList && mysqli_num_rows($favoriteList) > 0) {
                                    while ($resultProd = mysqli_fetch_assoc($favoriteList)) {
                                        $maSanPham = $resultProd['maSanPham'];
                                        $priceQuery = mysqli_query($conn, "SELECT MIN(giaSanPham) as minPrice FROM tbl_product_size WHERE maSanPham = $maSanPham");
                                        $priceData = mysqli_fetch_assoc($priceQuery);
                                        $giaSanPham = $priceData['minPrice'] ?? 0;
                            ?>
                            <li class="gategory-product-list col-lg-3 col-md-4 col-sm-6 col-xs-12" id="product-<?php echo $resultProd['maSanPham']; ?>">
                                <div class="single-product-item">
                                    <div class="product-image">
                                        <a href="single-product.php?maSanPham=<?php echo $resultProd['maSanPham']; ?>">
                                            <img src="admin/pages/uploads/<?php echo $resultProd['hinhAnhSanPham']; ?>" alt="product-image" />
                                        </a>
                                        <span class="favorite-icon" 
                                              data-product-id="<?php echo $resultProd['maSanPham']; ?>" 
                                              style="cursor: pointer; color: red;">
                                            <i class="fa fa-heart"></i>
                                        </span>
                                    </div>
                                    <div class="product-info">
                                        <div class="customar-comments-box"></div>
                                        <a href="single-product.php?maSanPham=<?php echo $resultProd['maSanPham']; ?>">
                                        <span style="text-transform: uppercase;">
                                                        <?php 
                                                            $productName = $resultProd['tenSanPham'];
                                                            echo mb_strlen($productName, 'UTF-8') > 20 
                                                                ? mb_substr($productName, 0, 18, 'UTF-8') . '...' 
                                                                : $productName;
                                                        ?>
                                                    </span>
                                        </a>
                                        <div class="price-box">
                                            <span class="price"><?php echo number_format($giaSanPham); ?> VNĐ</span>
                                        </div>
                                    </div>
                                                
                                </div>
                            </li>
                            <?php 
                                    }
                                } else {
                                    echo "<p style='text-align: center; font-size: 18px; color: #666;'>Không có sản phẩm yêu thích</p>";
                                }
                            } else {
                                echo "<p style='text-align: center; font-size: 18px; color: #666;'>Vui lòng đăng nhập để xem sản phẩm yêu thích</p>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- MAIN-CONTENT-SECTION END -->

<?php include 'footer.php'; ?>

<style type="text/css">
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .main-content-section {
        flex: 1 0 auto;
        margin-bottom: 36px;
    }

    footer {
        flex-shrink: 0;
    }

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
        font-size: 25px;
        margin-right: 5px;
        position: absolute;
        bottom: 5px;
        right: 5px;
    }
    .product-image {
        position: relative;
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

$(document).ready(function() {
    $('.favorite-icon').on('click', function() {
        var productId = $(this).data('product-id');
        var productItem = $('#product-' + productId);

        $.ajax({
            url: 'toggle-favorite.php',
            type: 'POST',
            data: { maSanPham: productId },
            success: function(response) {
                if (response === 'removed') {
                    productItem.remove();
                    if ($('.gategory-product').children().length === 0) {
                        $('.gategory-product').html("<p style='text-align: center; font-size: 18px; color: #666;'>Không có sản phẩm yêu thích!!!</p>");
                    }
                } else if (response === 'login_required') {
                    alert('Vui lòng đăng nhập để quản lý sản phẩm yêu thích!');
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