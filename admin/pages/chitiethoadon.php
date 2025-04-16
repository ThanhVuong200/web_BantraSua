<?php
include 'header.php';
include_once '../classes/hoadon.php';

$hoadon = new hoadon();

if (!isset($_GET['maHoaDon']) || empty($_GET['maHoaDon'])) {
    echo "<script>window.location = 'orders.php'</script>";
} else {
    $id = $_GET['maHoaDon'];
    $infoHD = $hoadon->show_chitiethoadon($id);
    $infoHD2 = $hoadon->show_chitiethoadon2($id);
}
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Hóa đơn</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">CHI TIẾT HÓA ĐƠN</div>
                    <div class="panel-body">
                        <?php
                        if ($infoHD && $resultHD = $infoHD->fetch_assoc()) {
                        ?>
                            <form action="" method="POST" enctype="multipart/form-data" name="formUser">
                                <table style="width: 100%;">
                                    <tr>
                                        <td class="tabLabel"><label class="labelAddProduct">Mã hóa đơn: </label></td>
                                        <td><h5 style="font-size: 16px;"><?php echo $resultHD['maHoaDon']; ?></h5></td>
                                    </tr>
                                    <tr>
                                        <td class="tabLabel"><label class="labelAddProduct">Mã khách hàng: </label></td>
                                        <td><h5 style="font-size: 16px;"><?php echo $resultHD['maKhachHang']; ?></h5></td>
                                    </tr>
                                    <tr>
                                        <td class="tabLabel"><label class="labelAddProduct">Tên người nhận: </label></td>
                                        <td><h5 style="font-size: 16px;"><?php echo $resultHD['tenNguoiNhan']; ?></h5></td>
                                    </tr>
                                    <tr>
                                        <td class="tabLabel"><label class="labelAddProduct">Số điện thoại: </label></td>
                                        <td><h5 style="font-size: 16px;">+84<?php echo $resultHD['sdtKH']; ?></h5></td>
                                    </tr>
                                    <tr>
                                        <td class="tabLabel"><label class="labelAddProduct">Địa chỉ giao: </label></td>
                                        <td><h5 style="font-size: 16px;"><?php echo $resultHD['diachi']; ?></h5></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                    <thead>
                                                        <tr>
                                                            <th>STT</th>
                                                            <th>Ảnh</th>
                                                            <th>Mã sản phẩm</th>
                                                            <th>Tên sản phẩm</th>
                                                            <th>Số lượng SP</th>
                                                            <th>Đơn giá</th>
                                                            <th>Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if ($infoHD2) {
                                                            $i = 0;
                                                            while ($resultSPHD = $infoHD2->fetch_assoc()) {
                                                                $i++;
                                                                $thanhtien = $resultSPHD['soLuongSP'] * $resultSPHD['giaSanPham'];
                                                        ?>
                                                                <tr class="odd gradeX">
                                                                    <td><?php echo $i; ?></td>
                                                                    <td><img src="uploads/<?php echo $resultSPHD['hinhAnhSP']; ?>" width="90"></td>
                                                                    <td><?php echo $resultSPHD['maSP']; ?></td>
                                                                    <td><?php echo $resultSPHD['tenSP']; ?></td>
                                                                    <td><?php echo $resultSPHD['soLuongSP']; ?></td>
                                                                    <td><?php echo number_format($resultSPHD['giaSanPham']); ?></td>
                                                                    <td><?php echo number_format($thanhtien); ?></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tabLabel"><label class="labelAddProduct">Giá trị đơn hàng: </label></td>
                                        <td><span style="font-size: 16px;"><?php echo number_format($resultHD['giaTriHD']); ?> VND</span></td>
                                    </tr>
                                </table>
                            </form>
                        <?php
                        } else {
                            echo "<p>Không tìm thấy hóa đơn!</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/metisMenu.min.js"></script>
<script src="../js/dataTables/jquery.dataTables.min.js"></script>
<script src="../js/dataTables/dataTables.bootstrap.min.js"></script>
<script src="../js/startmin.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>
</body>

</html>