<?php 
include 'header.php'; 
// include_once '../config.php'; // Thêm dòng này để khai báo $conn
include_once '../classes/product.php';
include_once '../helpers/format.php';
?>
<?php
$fm = new Format();
$prod = new product();

// Ẩn sản phẩm
if (isset($_GET['hideid']) && $_GET['hideid'] != '') {
    $id = $_GET['hideid'];
    $hideProduct = $prod->hide_product($id);
}
?>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sản phẩm</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="textHeading">DANH SÁCH SẢN PHẨM
                    <form action="" method="get">
                        <a href="productadd.php"><button type="button" class="btn btn-success" style="float: right; margin-top:-32px">Thêm sản phẩm</button></a>
                    </form>
                    <p></p>
                </span>
            </div>
            <div class="panel-body">   
                <?php
                if (isset($hideProduct)) {
                    echo $hideProduct;
                }
                ?>
                <div class="table-responsive" style="margin-top: 2%">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Mã sản phẩm</th>
                                <th>Tên sản phẩm</th>
                                <th>Tên danh mục</th>
                                <th>Size</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Miêu tả sản phẩm</th>
                                <th>Trạng thái</th>
                                <th>Ảnh sản phẩm</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $prodList = $prod->show_product(); 
                            if ($prodList) {
                                while ($result = $prodList->fetch_assoc()) {
                                    if ($result['trangThaiSanPham'] == 1) {         
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $result['maSanPham']; ?></td>
                                <td><?php echo $result['tenSanPham']; ?></td>
                                <td><?php echo $result['tenLoai']; ?></td>
                                <td class="center"><?php echo $result['sizes']; ?></td> <!-- Hiển thị danh sách kích thước -->
                                <td><?php echo $result['totalStock']; ?></td> <!-- Tổng số lượng từ tbl_product_size -->
                                <td class="center">
                                    <?php 
                                    // Hiển thị phạm vi giá hoặc giá duy nhất
                                    echo ($result['minPrice'] != $result['maxPrice']) 
                                        ? number_format($result['minPrice']) . ' - ' . number_format($result['maxPrice']) 
                                        : number_format($result['minPrice']); 
                                    ?> VNĐ
                                </td>
                                <td><?php echo $fm->textShorten($result['mieuTaSanPham'], 80); ?></td>
                                <td class="center">
                                    <?php 
                                    if ($result['trangThaiSanPham'] == 1) {
                                        echo '<button type="button" class="btn btn-outline btn-success">Còn hàng</button>';
                                    }
                                    ?>
                                </td>
                                <td><img src="uploads/<?php echo $result['hinhAnhSanPham']; ?>" width='80'></td>
                                <td>
                                    <a href="productedit.php?productid=<?php echo $result['maSanPham']; ?>" onclick="return popitup('productedit.php?productid=<?php echo $result['maSanPham']; ?>')">
                                        <button type="button" class="btn btn-info">Sửa</button>
                                    </a>
                                    <a href="?hideid=<?php echo $result['maSanPham']; ?>" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                        <button type="button" class="btn btn-warning">Xóa</button>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                    }
                                }
                            } else {
                                echo '<script>alert("Không tìm thấy dữ liệu!"); history.back();</script>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="phanTrang">
                        <?php 
                        $productAll = $prod->getAllProduct(); // Lấy số sản phẩm
                        $productCount = mysqli_num_rows($productAll); // Đếm số dòng
                        $productButton = ceil($productCount / 5); // Số button phân trang, 5 sản phẩm/trang

                        if (!isset($_GET['trang'])) {
                            $trangHienTai = 1;
                        } else {
                            $trangHienTai = $_GET['trang'];
                        }

                        if (isset($_GET['nameSearch']) && !empty($_GET['nameSearch'])) {
                            $nameSearch = $_GET['nameSearch'];
                            // Button Prev
                            if ($trangHienTai > 1 && $productButton > 1) {
                                echo '<a href="?nameSearch='.$nameSearch.'&search=Tìm+kiếm&trang='.($trangHienTai - 1).'"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
                            }
                            // Create Buttons
                            for ($i = 1; $i <= $productButton; $i++) {
                                if ($i == $trangHienTai) {
                                    echo '<a href="?nameSearch='.$nameSearch.'&search=Tìm+kiếm&trang='.$i.'" style="background-color: grey;">'.$i.'</a>';
                                } else {
                                    echo '<a href="?nameSearch='.$nameSearch.'&search=Tìm+kiếm&trang='.$i.'">'.$i.'</a>';
                                }
                            }
                            // Button Next
                            if ($trangHienTai < $productButton && $productButton > 1) {
                                echo '<a href="?nameSearch='.$nameSearch.'&search=Tìm+kiếm&trang='.($trangHienTai + 1).'">Trang sau <i class="fa fa-angle-double-right"></i></a>';
                            }
                        } else {
                            // Button Prev
                            if ($trangHienTai > 1 && $productButton > 1) {
                                echo '<a href="?trang='.($trangHienTai - 1).'"><i class="fa fa-angle-double-left"></i> Trang trước</a>';
                            }
                            // Create Buttons
                            for ($i = 1; $i <= $productButton; $i++) {
                                if ($i == $trangHienTai) {
                                    echo '<a href="?trang='.$i.'" style="background-color: grey;">'.$i.'</a>';
                                } else {
                                    echo '<a href="?trang='.$i.'">'.$i.'</a>';
                                }
                            }
                            // Button Next
                            if ($trangHienTai < $productButton && $productButton > 1) {
                                echo '<a href="?trang='.($trangHienTai + 1).'">Trang sau <i class="fa fa-angle-double-right"></i></a>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
</div>
<!-- /.row -->
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<!-- jQuery -->
<script src="../js/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="../js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="../js/metisMenu.min.js"></script>
<!-- DataTables JavaScript -->
<script src="../js/dataTables/jquery.dataTables.min.js"></script>
<script src="../js/dataTables/dataTables.bootstrap.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="../js/startmin.js"></script>

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

<!-- Page-Level Demo Scripts - Tables -->
<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        responsive: true
    });
});
</script>
</body>
</html>