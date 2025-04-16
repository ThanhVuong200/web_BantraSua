<?php
    include 'classes/adminlogin.php';
?>
<?php
    $class = new adminlogin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' ){
        $tenDangNhap = $_POST['tenDangNhap'];
        $matKhau = $_POST['matKhau'];

        $login_check = $class->login_admin($tenDangNhap,$matKhau);
    }
    
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>ĐĂNG NHẬP TRANG QUẢN TRỊ</title>

        <!-- Bootstrap Core CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="../css/startmin.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            body {
                background: linear-gradient(135deg,rgb(243, 243, 243) 0%,rgb(225, 217, 233) 100%);
                font-family: 'Poppins', sans-serif;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0;
            }
            .login-container {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                padding: 40px;
                width: 100%;
                max-width: 400px;
                margin: 20px;
            }
            .login-header {
                text-align: center;
                margin-bottom: 30px;
            }
            .login-header img {
                width: 80px;
                margin-bottom: 15px;
            }
            .login-header h3 {
                color: #333;
                font-weight: 600;
                margin: 0;
                font-size: 24px;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-control {
                height: 50px;
                border-radius: 8px;
                border: 1px solid #ddd;
                padding: 10px 15px;
                font-size: 14px;
                transition: all 0.3s ease;
            }
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }
            .btn-login {
                height: 50px;
                border-radius: 8px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                font-weight: 500;
                font-size: 16px;
                transition: all 0.3s ease;
            }
            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            }
            .error-message {
                background: #fff3f3;
                color: #dc3545;
                padding: 10px 15px;
                border-radius: 8px;
                margin-bottom: 20px;
                font-size: 14px;
            }
            @media (max-width: 480px) {
                .login-container {
                    margin: 15px;
                    padding: 30px 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="login-header">
                <h3>ĐĂNG NHẬP TRANG QUẢN TRỊ</h3>
            </div>
            
            <?php if (isset($login_check)): ?>
                <div class="error-message">
                    <?php echo $login_check; ?>
                </div>
            <?php endif; ?>

            <form role="form" action="index.php" method="POST">
                <div class="form-group">
                    <input class="form-control" placeholder="Tên đăng nhập" name="tenDangNhap" autofocus required>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Mật khẩu" name="matKhau" type="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-login">
                    <i class="fa fa-sign-in"></i> Đăng nhập
                </button>
            </form>
        </div>

        <!-- jQuery -->
        <script src="../js/jquery.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
