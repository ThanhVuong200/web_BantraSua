<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
	$pageTitle = "ĐĂNG NHẬP/ĐĂNG KÝ | Company Coffee - Cà Hê Ngon";
	function customPageHeader(){?>
		<title>$pageTitle</title>
	<?php }

	include 'header.php';
?>
		<!-- MAIN-CONTENT-SECTION START -->
		<section class="main-content-section">
			<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" 
		style="margin: 20px;">
        </div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h2 class="page-title">Đăng nhập / Đăng ký</h2>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<!-- CREATE-NEW-ACCOUNT START -->
						<div class="create-new-account">
						<form class="new-account-box primari-box" id="create-new-account" method="post" action="#">
								<h3 class="box-subheading">Tạo tài khoản</h3>
								<div class="form-content">
									
									<div class="submit-button">
										<a href="checkout-registration.php" id="SubmitCreate" class="btn main-btn">
											<span>
												<i class="fa fa-user submit-icon"></i>
												TẠO TÀI KHOẢN
											</span>											
										</a>
									</div>
								</div>
							</form>						
						</div>
						<!-- CREATE-NEW-ACCOUNT END -->
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<!-- REGISTERED-ACCOUNT START -->
						<div class="primari-box registered-account">
						<form class="new-account-box" id="accountLogin" name="Forms" onsubmit="return validate()" method="post" action="check1.php">
								<h3 class="box-subheading">ĐÃ CÓ TÀI KHOẢN?</h3>
								<div class="form-content">
								<div id="thongbao"><?php if(isset($_SESSION['success'])){
									echo $_SESSION['success'];
								}
							//endif;
								else {
									unset($_SESSION['success']);
								}
							
							?></div>
									<div class="form-group primary-form-group">
										<label for="loginemail">Tên đăng nhập</label>
										<input type="text" value="" name="loginname" id="loginname" class="form-control input-feild">
									</div>
									<div class="form-group primary-form-group">
										<label for="password">Mật khẩu</label>
										<input type="password" value="" name="loginpassword" id="loginpassword" class="form-control input-feild">
									</div>
									
									<div class="submit-button">
										
										<input type="submit"  id="signinCreate" class="btn main-btn" value=" ĐĂNG NHẬP" >
										</a>
									</div>
								</div>
								
							</form>							
						</div>
						<!-- REGISTERED-ACCOUNT END -->
					</div>
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