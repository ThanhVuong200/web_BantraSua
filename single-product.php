<?php 
session_start();
include_once 'classes/product.php';
include_once 'admin/helpers/format.php';

if (!isset($_GET['maSanPham']) || $_GET['maSanPham'] == '') {
    echo "<script>window.location = '404.php'</script>";
} else {
    $idSanPham = $_GET['maSanPham'];
}

$fm = new Format();
$prod = new product();
$prodList = $prod->getproductbyId($idSanPham);
$resultProd = $prodList->fetch_assoc();

$pageTitle = $resultProd['tenSanPham'] . " | Company Coffee - Cà Hê Ngon";
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
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <!-- SINGLE-PRODUCT-DESCRIPTION START -->
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
                        <div class="single-product-view">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="thumbnail_1">
                                    <div class="single-product-image">
                                        <img src="admin/pages/uploads/<?php echo $resultProd['hinhAnhSanPham']; ?>" alt="single-product-image" />
                                        <a class="fancybox" href="admin/pages/uploads/<?php echo $resultProd['hinhAnhSanPham']; ?>" data-fancybox-group="gallery">
                                            <span class="btn large-btn">Phóng to <i class="fa fa-search-plus"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
                        <div class="single-product-descirption">
                            <h2 name="m"><?php echo $resultProd['tenSanPham']; ?></h2>
                            <div class="single-product-review-box"></div>
                            <?php
                            // Lấy danh sách kích thước từ tbl_product_size
                            $maSanPham = $resultProd['maSanPham'];
                            $sizeQuery = mysqli_query($conn, "SELECT id, size, giaSanPham, soLuongSanPham FROM tbl_product_size WHERE maSanPham = $maSanPham");
                            $sizes = [];
                            $minPrice = PHP_INT_MAX;
                            $totalStock = 0;
                            while ($sizeRow = mysqli_fetch_assoc($sizeQuery)) {
                                $sizes[] = $sizeRow;
                                $minPrice = min($minPrice, $sizeRow['giaSanPham']);
                                $totalStock += $sizeRow['soLuongSanPham'];
                            }
                            ?>
                            <div class="single-product-price">
                                <h2><?php echo number_format($minPrice); ?> VNĐ</h2> <!-- Hiển thị giá thấp nhất -->
                            </div>
                            <div class="single-product-desc">
                                <p><?php echo $resultProd['mieuTaSanPham']; ?></p>
                                <div class="product-in-stock">
                                    <p>
                                        <?php 
                                        if ($resultProd['trangThaiSanPham'] == '1') {
                                            // echo 'Còn lại ' . $totalStock . ' sản phẩm';
                                            echo 'Tình trạng sản phẩm:';
                                            if ($totalStock > 0) {
                                                echo ' <a style= "color: #0bb10b;">Còn hàng</a>';
                                            } else {
                                                echo ' <a style= "color: red;">Hết hàng</a>';
                                            }
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="product-sold">
                                <p><strong>Số lượt bán:</strong> <span><?php echo $resultProd['soLuotBan']; ?></span></p>

                                </div>
                            </div>
                            <div class="single-product-add-cart">
                                <div class="single-product-size">
                                    <p class="small-title">Kích thước:</p>
                                    <div class="size-options">
                                        <?php foreach ($sizes as $size) { ?>
                                            <div class="size-box" data-id="<?php echo $size['id']; ?>" 
                                                data-price="<?php echo $size['giaSanPham']; ?>" 
                                                data-stock="<?php echo $size['soLuongSanPham']; ?>">
                                                <?php echo $size['size']; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <form method="post" action="add_cart.php?id=<?php echo $resultProd['maSanPham']; ?>">
                                    <div class="single-product-quantity">
                                        <?php if ($totalStock > 0) { ?>
                                            <p class="small-title">Số lượng:</p>
                                            <div class="cart-quantity">
                                                <div class="cart-plus-minus-button single-qty-btn">
                                                    <input class="cart-plus-minus sing-pro-qty" type="text" name="qtybutton" value="1" readonly="readonly">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php if ($totalStock > 0) { ?>
                                        <input type="hidden" name="product_size_id" id="hidden_product_size_id">
                                        <input type="submit" name="add_to_cart" class="add-cart-text" value="Thêm vào giỏ hàng" title="Add to cart">
                                    <?php } else { ?>
                                        <input type="submit" disabled name="add_to_cart" class="add-cart-text" value="Sản Phẩm Hết">
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SINGLE-PRODUCT-DESCRIPTION END -->
            </div>
        </div>
    </div>
</section>
<!-- MAIN-CONTENT-SECTION END -->
<?php include 'footer.php'; ?>

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

// Cập nhật giá khi chọn kích thước
$(document).ready(function() {
    $('#product-size').change(function() {
        var selectedOption = $(this).find(':selected');
        var price = selectedOption.data('price');
        var stock = selectedOption.data('stock');
        $('.single-product-price h2').text(price.toLocaleString('vi-VN') + ' VNĐ');
        $('#hidden_product_size_id').val($(this).val());
        if (stock > 0) {
            $('.single-product-quantity').show();
            $('input[name="add_to_cart"]').prop('disabled', false).val('Thêm vào giỏ hàng');
        } else {
            $('.single-product-quantity').hide();
            $('input[name="add_to_cart"]').prop('disabled', true).val('Hết hàng');
        }
    });
    // Khởi tạo giá trị ban đầu
    $('#product-size').trigger('change');
});
$(document).ready(function() {
    // Xử lý chọn kích thước
    $('.size-box').on('click', function() {
        $('.size-box').removeClass('active'); // Bỏ class active khỏi tất cả
        $(this).addClass('active'); // Thêm class active cho phần tử được chọn

        var price = $(this).data('price');
        var stock = $(this).data('stock');
        var sizeId = $(this).data('id');

        // Cập nhật giá
        $('.single-product-price h2').text(price.toLocaleString('vi-VN') + ' VNĐ');
        $('#hidden_product_size_id').val(sizeId);

        // Kiểm tra số lượng và cập nhật nút "Thêm vào giỏ hàng"
        if (stock > 0) {
            $('.single-product-quantity').show();
            $('input[name="add_to_cart"]').prop('disabled', false).val('Thêm vào giỏ hàng');
        } else {
            $('.single-product-quantity').hide();
            $('input[name="add_to_cart"]').prop('disabled', true).val('Hết hàng');
        }
    });

    // Khởi tạo giá trị ban đầu (chọn kích thước đầu tiên)
    if ($('.size-box').length > 0) {
        $('.size-box:first').trigger('click');
    }
});
</script>
<script src="js/main.js"></script>
</body>
</html>