<?php
    include 'header.php';
    include_once '../classes/donhang.php';

    $donhang = new donhang();

    // Xử lý thay đổi trạng thái đơn hàng
    if (isset($_GET['statusid']) && !empty($_GET['statusid'])) {
        $id = $_GET['statusid'];
        $doiTrangThaiDH = $donhang->DoiTrangThaiDH($id);
    } else if (!isset($_GET['statusid'])) {
        // Không cần redirect nếu không có statusid, chỉ hiển thị danh sách
    }
?>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Đơn hàng</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        QUẢN LÝ ĐƠN HÀNG
                    </div>
                    <div class="panel-body">
                        <!-- Form lọc theo ngày -->
                        <form action="" method="get" style="margin-bottom: 15px;">
                            <input type="text" name="ngaytruoc" placeholder="Từ ngày.." id="datepicker1" value="<?php echo isset($_GET['ngaytruoc']) ? $_GET['ngaytruoc'] : ''; ?>" style="width: 18%; height: 34px; padding: 6px 12px; font-size: 14px;">
                            <span> tới </span>
                            <input type="text" name="ngaysau" placeholder="Tới ngày.." id="datepicker2" value="<?php echo isset($_GET['ngaysau']) ? $_GET['ngaysau'] : ''; ?>" style="width: 18%; height: 34px; padding: 6px 12px; font-size: 14px;">
                            <input type="submit" name="action" value="Lọc" class="btn btn-default">
                        </form>

                        <?php
                        // Hiển thị thông báo sau khi thay đổi trạng thái
                        if (isset($doiTrangThaiDH)) {
                            echo $doiTrangThaiDH;
                        }
                        ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Mã khách hàng</th>
                                        <th>Ngày lập đơn hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $donhanglist = $donhang->show_donhangPhanTrang();
                                    if ($donhanglist) {
                                        while ($resultDH = $donhanglist->fetch_assoc()) {
                                    ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $resultDH['maDonHang']; ?></td>
                                                <td><?php echo $resultDH['maKhachHang'] ?? 'Không xác định'; ?></td>
                                                <td><?php echo $resultDH['ngayLapDH']; ?></td>
                                                <td><?php echo number_format($resultDH['tongTienDH']); ?> VND</td>
                                                <td>
                                                    <?php
                                                    if ($resultDH['trangThaiDH'] == "Chưa giao") {
                                                        echo "<button type='button' class='btn btn-outline btn-warning'>Chưa giao</button>";
                                                    } else if ($resultDH['trangThaiDH'] == "Đã hoàn thành") {
                                                        echo "<button type='button' class='btn btn-outline btn-success'>Đã hoàn thành</button>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="chitietdonhang.php?maDonHang=<?php echo $resultDH['maDonHang']; ?>">
                                                        <button type="button" class="btn btn-info">Xem chi tiết</button>
                                                    </a>
                                                    <?php
                                                    if ($resultDH['trangThaiDH'] == "Chưa giao") {
                                                        echo "<a href='?statusid=" . $resultDH['maDonHang'] . "'><button type='button' class='btn btn-success'>Xác nhận đơn hàng</button></a>";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Không có đơn hàng nào.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <!-- Phân trang -->
                            <div class="phanTrang">
                                <?php
                                $donhangAll = $donhang->getAllDonHang();
                                if (mysqli_num_rows($donhangAll) == 0) {
                                    echo "<script>alert('Không tìm thấy dữ liệu!');</script>";
                                } else {
                                    $donhangCount = mysqli_num_rows($donhangAll);
                                    $donhangButton = ceil($donhangCount / 10); // 10 đơn hàng mỗi trang
                                    $trangHienTai = isset($_GET['trang']) ? $_GET['trang'] : 1;

                                    // Nút Previous
                                    if ($trangHienTai > 1 && $donhangButton > 1) {
                                        echo '<a href="?trang=' . ($trangHienTai - 1) . '"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
                                    }

                                    // Các nút phân trang
                                    for ($i = 1; $i <= $donhangButton; $i++) {
                                        if ($i == $trangHienTai) {
                                            echo '<a href="?trang=' . $i . '" style="background-color: grey;">' . $i . '</a>';
                                        } else {
                                            echo '<a href="?trang=' . $i . '">' . $i . '</a>';
                                        }
                                    }

                                    // Nút Next
                                    if ($trangHienTai < $donhangButton && $donhangButton > 1) {
                                        echo '<a href="?trang=' . ($trangHienTai + 1) . '">Trang sau <i class="fa fa-angle-double-right"></i></a>';
                                    }
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

<!-- jQuery -->
<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript">
    $("#datepicker1").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#datepicker2").datepicker({ dateFormat: 'yy-mm-dd' });
</script>
<!-- Bootstrap Core JavaScript -->
<script src="../js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="../js/metisMenu.min.js"></script>
<!-- DataTables JavaScript -->
<script src="../js/dataTables/jquery.dataTables.min.js"></script>
<script src="../js/dataTables/dataTables.bootstrap.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="../js/startmin.js"></script>
</body>
</html>