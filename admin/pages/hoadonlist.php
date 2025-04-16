<?php
include 'header.php';
include_once '../classes/hoadon.php';

$hoadon = new hoadon();
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
                    <div class="panel-heading">QUẢN LÝ HÓA ĐƠN</div>
                    <div class="panel-body">
                        <form action="" method="get" style="margin-bottom: 15px;">
                            <input type="text" name="ngaytruoc" placeholder="Từ ngày.." id="datepicker1" value="<?php if (isset($_GET['ngaytruoc'])) echo $_GET['ngaytruoc']; ?>" style="width: 18%; height: 34px; padding: 6px 12px; font-size: 14px;">
                            <span> tới </span>
                            <input type="text" name="ngaysau" placeholder="Tới ngày.." id="datepicker2" value="<?php if (isset($_GET['ngaysau'])) echo $_GET['ngaysau']; ?>" style="width: 18%; height: 34px; padding: 6px 12px; font-size: 14px;">
                            <input type="submit" name="action" value="Lọc" class="btn btn-default">
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã hóa đơn</th>
                                        <th>Mã khách hàng</th>
                                        <th>Ngày lập hóa đơn</th>
                                        <th>Giá trị hóa đơn</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $hoadonlist = $hoadon->show_hoadonPhanTrang();
                                    if ($hoadonlist) {
                                        while ($resultHD = $hoadonlist->fetch_assoc()) {
                                    ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $resultHD['maHoaDon']; ?></td>
                                                <td><?php echo $resultHD['maKhachHang']; ?></td>
                                                <td><?php echo $resultHD['ngayDat']; ?></td>
                                                <td><?php echo number_format($resultHD['giaTriHD']); ?> VND</td>
                                                <td>
                                                    <a href="chitiethoadon.php?maHoaDon=<?php echo $resultHD['maHoaDon']; ?>">
                                                        <button type="button" class="btn btn-info">Xem chi tiết</button>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="phanTrang">
                                <?php
                                $donhangAll = $hoadon->getAllHoaDon();
                                if ($donhangAll) {
                                    $donhangCount = mysqli_num_rows($donhangAll);
                                    $donhangButton = ceil($donhangCount / 10);
                                    $trangHienTai = isset($_GET['trang']) ? $_GET['trang'] : 1;

                                    if (isset($_GET['ngaytruoc']) && !empty($_GET['ngaytruoc'])) {
                                        $ngaytruoc = $_GET['ngaytruoc'];
                                        $ngaysau = $_GET['ngaysau'];
                                        if ($trangHienTai > 1) {
                                            echo '<a href="?ngaytruoc=' . $ngaytruoc . '&ngaysau=' . $ngaysau . '&trang=' . ($trangHienTai - 1) . '"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
                                        }
                                        for ($i = 1; $i <= $donhangButton; $i++) {
                                            echo '<a href="?ngaytruoc=' . $ngaytruoc . '&ngaysau=' . $ngaysau . '&trang=' . $i . '" ' . ($i == $trangHienTai ? 'style="background-color: grey;"' : '') . '>' . $i . '</a>';
                                        }
                                        if ($trangHienTai < $donhangButton) {
                                            echo '<a href="?ngaytruoc=' . $ngaytruoc . '&ngaysau=' . $ngaysau . '&trang=' . ($trangHienTai + 1) . '">Trang sau <i class="fa fa-angle-double-right"></i></a>';
                                        }
                                    } else {
                                        if ($trangHienTai > 1) {
                                            echo '<a href="?trang=' . ($trangHienTai - 1) . '"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
                                        }
                                        for ($i = 1; $i <= $donhangButton; $i++) {
                                            echo '<a href="?trang=' . $i . '" ' . ($i == $trangHienTai ? 'style="background-color: grey;"' : '') . '>' . $i . '</a>';
                                        }
                                        if ($trangHienTai < $donhangButton) {
                                            echo '<a href="?trang=' . ($trangHienTai + 1) . '">Trang sau <i class="fa fa-angle-double-right"></i></a>';
                                        }
                                    }
                                } else {
                                    echo "<script>alert('Không tìm thấy dữ liệu!'); history.back();</script>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

<script src="../js/jquery.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/metisMenu.min.js"></script>
<script src="../js/dataTables/jquery.dataTables.min.js"></script>
<script src="../js/dataTables/dataTables.bootstrap.min.js"></script>
<script src="../js/startmin.js"></script>
<script>
    $(document).ready(function() {
        $("#datepicker1").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#datepicker2").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
</body>

</html>