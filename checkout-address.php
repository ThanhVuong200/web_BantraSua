<?php
session_start();
$pageTitle = "KIỂM TRA ĐỊA CHỈ | Company Coffee - Cà Hê Ngon";
function customPageHeader() {
    global $pageTitle;
    echo "<title>$pageTitle</title>";
}

include_once 'config.php';
include 'header.php';
?>

<!-- MAIN-CONTENT-SECTION START -->
<section class="main-content-section">
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 20px;">
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-title">Thông tin thanh toán</h2>
            </div>
            <!-- ADDRESS AREA START -->
             <div class="col-lg-3" ></div>
            <div class="col-lg-6 col-md-5 col-sm-6 col-xs-12">
                <div class="form-group primary-form-group p-info-group deli-address-group">
                    <form class="primari-box personal-info-box" id="personalinfo" name="Formaddress" 
                          onsubmit="return validateAdress()" method="post" action="address.php">
                        <center>ĐỊA CHỈ</center>

                        <label for="diachinh">Địa chỉ nhận hàng: <p class= "note" > (Freeship trong bán kính 5km)</p></label>
                        <input type="text" value="" name="diachinh" id="diachinh" class="form-control input-feild">

                        <div class="form-group primary-form-group p-info-group">
                            <label for="firstname">Tên người nhận:<sup></sup></label>
                            <input type="text" value="" name="firstname" id="firstname" class="form-control input-feild">
                        </div>

                        <div class="form-group primary-form-group p-info-group">
                            <label for="phone">Số điện thoại:<sup></sup></label>
                            <input type="text" value="" name="phone" id="phone" class="form-control input-feild">
                        </div>

                        <div class="form-group p-info-group type-address-group">
                            <label>Ghi chú cho đơn hàng (nếu có)</label>
                            <textarea class="form-control input-feild" name="addcomment"></textarea>
                        </div>

                        <!-- RETURNE-CONTINUE-SHOP START -->
                        <div class="returne-continue-shop ship-address">
                            <input type="submit" value="TIẾN HÀNH ĐẶT HÀNG" style="color: white;">
                        </div>
                        <!-- RETURNE-CONTINUE-SHOP END -->
                    </form>
                </div>
            </div>
            <div class="col-lg-5" ></div>
        </div>
    </div>
</section>
<!-- MAIN-CONTENT-SECTION END -->
<?php
include 'footer.php';
?>

<!-- JS -->
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

<script>
function validateAdress() {
    var diachi = document.getElementById('diachinh').value;
    var firstname = document.getElementById('firstname').value;
    var phone = document.getElementById('phone').value;

    if (diachi === "" || firstname === "" || phone === "") {
        alert("Vui lòng điền đầy đủ thông tin địa chỉ giao hàng.");
        return false;
    }

    // Kiểm tra định dạng số điện thoại (10 số, bắt đầu bằng 0)
    var phonePattern = /^0\d{9}$/;
    if (!phonePattern.test(phone)) {
        alert("Số điện thoại phải bắt đầu bằng 0 và có đúng 10 chữ số.");
        return false;
    }

    return true;
}
</script>
</body>
</html>