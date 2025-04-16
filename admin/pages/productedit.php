<?php include 'header.php'; ?>
<?php include_once '../classes/category.php'; ?>
<?php include_once '../classes/product.php'; ?>
<?php 
    $prod = new product();
    if (!isset($_GET['productid']) || $_GET['productid'] == ''){
        echo "<script>window.location = 'product.php'</script>";
    }else{
        $id = $_GET['productid'];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){
        $editProduct = $prod->edit_product($_POST, $_FILES, $id);
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
                            <span class="textHeading">Chỉnh sửa sản phẩm</span>
                        </div>
                        <div class="panel-body">
                        <?php
                                    if (isset($editProduct)){
                                        echo $editProduct;
                                    }
                        ?>   
                        <?php 
                            $get_product_by_id = $prod->getproductbyId($id);
                            if ($get_product_by_id){
                                while ($result_prod = $get_product_by_id->fetch_assoc()) {
                        ?>
                            <form action="" method="POST" enctype="multipart/form-data" name="formUser" onsubmit="return validationForm();"> <!--enctype để có thể thêm hình ảnh -->
                                <table style="width: 100%;">
                                <tr>
                                    <td class="tabLabel">
                                        <label class="labelAddProduct">Tên sản phẩm: </label>
                                    </td>
                                    <td>
                                        <input type="text" name="tenSanPham" value="<?php echo $result_prod['tenSanPham'] ?>" class="inputAddProduct" autofocus required>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="tabLabel">
                                        <label class="labelAddProduct">Danh mục sản phẩm: </label>
                                    </td>
                                    <td>
                                        <select class="inputAddProduct" name="maLoai" required>
                                            <option value="0">----Chọn danh mục----</option>
                                            <?php 
                                                $cat = new category();
                                                $catlist = $cat->show_category();
                                                if ($catlist){
                                                    while ($result = $catlist->fetch_assoc()) {
                                            ?>
                                            <option 
                                            <?php 
                                                if ($result['maLoai'] == $result_prod['maLoai'] ){ echo 'selected'; }
                                            ?>
                                            value="<?php echo $result['maLoai']; ?>"><?php echo $result['tenLoai']; ?>
                                            </option>
                                            <?php 
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="tabLabel">
                                        <label class="labelAddProduct">Miêu tả sản phẩm: </label>
                                    </td>
                                    <td>
                                        <textarea name="mieuTaSanPham" rows="2" cols="25" class="inputAddProduct" style="height: 80px;" required><?php echo $result_prod['mieuTaSanPham'] ?></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="tabLabel">
                                        <label class="labelAddProduct">Hình ảnh sản phẩm: </label>
                                    </td>
                                    <td>
                                        <input name="image" type="file" accept="image/*" onchange="loadFile(event)" >
                                        <img id="output" src="uploads/<?php echo $result_prod['hinhAnhSanPham']; ?>" style="width: 20%;" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="tabLabel">
                                        <label class="labelAddProduct">Kích thước và số lượng: </label>
                                    </td>
                                    <td>
                                        <?php 
                                        // Lấy danh sách kích thước hiện tại của sản phẩm từ tbl_product_size
                                        $product_sizes = $prod->getProductSizes($id);
                                        if ($product_sizes) {
                                            while ($size_data = $product_sizes->fetch_assoc()) {
                                        ?>
                                        <div class="size-row" style="margin-bottom: 10px;">
                                            <label>Kích thước:</label>
                                            <input type="text" name="sizes[]" value="<?php echo $size_data['size']; ?>" class="inputAddProduct" style="width: 100px;" required>
                                            <label>Số lượng:</label>
                                            <input type="number" name="quantities[]" value="<?php echo $size_data['soLuongSanPham']; ?>" class="inputAddProduct" style="width: 100px;" required min="0">
                                            <label>Giá:</label>
                                            <input type="number" name="prices[]" value="<?php echo $size_data['giaSanPham']; ?>" class="inputAddProduct" style="width: 100px;" required min="0">
                                            <button type="button" class="btn btn-danger" onclick="removeSize(this)">Xóa</button>
                                        </div>
                                        <?php 
                                            }
                                        } else {
                                            // Nếu không có kích thước, thêm một dòng mặc định
                                        ?>
                                        <div class="size-row" style="margin-bottom: 10px;">
                                            <label>Kích thước:</label>
                                            <input type="text" name="sizes[]" value="" class="inputAddProduct" style="width: 100px;" required>
                                            <label>Số lượng:</label>
                                            <input type="number" name="quantities[]" value="0" class="inputAddProduct" style="width: 100px;" required min="0">
                                            <label>Giá:</label>
                                            <input type="number" name="prices[]" value="0" class="inputAddProduct" style="width: 100px;" required min="0">
                                            <button type="button" class="btn btn-danger" onclick="removeSize(this)">Xóa</button>
                                        </div>
                                        <?php 
                                        }
                                        ?>
                                        <!-- Nút thêm kích thước mới -->
                                        <button type="button" class="btn btn-primary" id="addSizeBtn" onclick="addSize()">Thêm kích thước</button>
                                    </td>
                                </tr>
                                 </table>
                                 <input type="submit" name="submit" value="Cập nhật sản phẩm" class="btn btn-success" style="margin: 10px;">
                            </form>  
                            <?php 
                                }
                            }
                            ?>
                         </div>  
                    </div>
                    <!-- /.row -->
                          <!-- /.col-lg-6 -->
                        
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

        </div>
        <!-- /#wrapper -->
        <link rel="stylesheet" type="text/css" href="../css/style.css">
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

        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
        <script>
            $(document).ready(function() {
                $('#dataTables-example').DataTable({
                    responsive: true
                });
            });
        </script>

        <script>
            var loadFile = function(event) {
                var output = document.getElementById('output');
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src); // free memory
                };
            };

            // Đảm bảo jQuery đã load trước khi chạy các hàm khác
            $(document).ready(function() {
                // Hàm thêm kích thước mới
                let sizeCount = 0;
                window.addSize = function() {
                    const sizeDiv = document.createElement('div');
                    sizeDiv.className = 'size-row';
                    sizeDiv.style.marginBottom = '10px';
                    sizeDiv.innerHTML = `
                        <label>Kích thước:</label>
                        <input type="text" name="sizes[]" value="" class="inputAddProduct" style="width: 100px;" required>
                        <label>Số lượng:</label>
                        <input type="number" name="quantities[]" value="0" class="inputAddProduct" style="width: 100px;" required min="0">
                        <label>Giá:</label>
                        <input type="number" name="prices[]" value="0" class="inputAddProduct" style="width: 100px;" required min="0">
                        <button type="button" class="btn btn-danger" onclick="removeSize(this)">Xóa</button>
                    `;
                    document.querySelector('.tabLabel:last-child + td').appendChild(sizeDiv);
                    sizeCount++;
                };

                // Hàm xóa kích thước
                window.removeSize = function(button) {
                    button.parentElement.remove();
                    sizeCount--;
                };

                // Gắn sự kiện cho nút "Thêm kích thước"
                $('#addSizeBtn').on('click', function(e) {
                    e.preventDefault();
                    addSize();
                });
            });
        </script>
    </body>
</html>