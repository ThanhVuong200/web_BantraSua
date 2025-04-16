<?php
include 'header.php';
?>

<!-- MAIN-CONTENT-SECTION START -->
<section class="main-content-section">
    <div class="container">
        <div class="row">
            <!-- MAIN-SLIDER-AREA START -->
            <div class="main-slider-area">
                <img src="img/wallpaper.jpg" alt="">
            </div>
            <!-- MAIN-SLIDER-AREA END -->
        </div>
        <!-- TOW-COLUMN-PRODUCT START -->
        <div class="row">    
            <!-- BESTSELLER-PRODUCTS-AREA START -->
            <div class="bestseller-products-area">
                <div class="center-title-area">
                    <h2 class="center-title">SẢN PHẨM NỔI BẬT</h2>
                </div>    
                <div class="col-xs-12">
                    <div class="row">
                        <!-- BESTSELLER-CAROUSEL START -->
                        <div class="bestseller-carousel">
                            <!-- BESTSELLER-SINGLE-ITEM START -->
                            <?php 
                            $prodList = $prod->show_productLimit10Asc();
                            if ($prodList) {
                                while ($resultProd = $prodList->fetch_assoc()) {
                                    if ($resultProd['trangThaiSanPham'] == '1') {
                                        // Lấy giá từ tbl_product_size (chọn giá thấp nhất nếu có nhiều kích thước)
                                        $maSanPham = $resultProd['maSanPham'];
                                        $priceQuery = mysqli_query($conn, "SELECT MIN(giaSanPham) as minPrice FROM tbl_product_size WHERE maSanPham = $maSanPham");
                                        $priceData = mysqli_fetch_assoc($priceQuery);
                                        $giaSanPham = $priceData['minPrice'] ?? 0; // Giá mặc định là 0 nếu không có kích thước
                            ?>
                            <div class="item">
                                <div class="single-product-item">
                                    <div class="product-image">
                                        <a href="single-product.php?maSanPham=<?php echo $resultProd['maSanPham']; ?>">
                                            <img src="admin/pages/uploads/<?php echo $resultProd['hinhAnhSanPham']; ?>" alt="product-image" />
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="customar-comments-box">
                                        </div>
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
                            </div>
                            <?php
                                    }
                                }
                            }
                            ?>
                            <!-- BESTSELLER-SINGLE-ITEM END -->
                        </div>    
                        <!-- BESTSELLER-CAROUSEL END -->    
                    </div>
                </div>                                
            </div>
            <!-- BESTSELLER-PRODUCTS-AREA END -->
        </div>
    </div>
</section>
<!-- MAIN-CONTENT-SECTION END -->

<?php
include 'footer.php';
?>

<!-- JS 
===============================================-->
<!-- jquery js -->
<script src="js/vendor/jquery-1.11.3.min.js"></script>

<!-- fancybox js -->
<script src="js/jquery.fancybox.js"></script>

<!-- bxslider js -->
<script src="js/jquery.bxslider.min.js"></script>

<!-- meanmenu js -->
<script src="js/jquery.meanmenu.js"></script>

<!-- owl carousel js -->
<script src="js/owl.carousel.min.js"></script>

<!-- nivo slider js -->
<script src="js/jquery.nivo.slider.js"></script>

<!-- jqueryui js -->
<script src="js/jqueryui.js"></script>

<!-- bootstrap js -->
<script src="js/bootstrap.min.js"></script>

<!-- wow js -->
<script src="js/wow.js"></script>        
<script>
    new WOW().init();
</script>

<!-- main js -->
<script src="js/main.js"></script>
</body>
</html>