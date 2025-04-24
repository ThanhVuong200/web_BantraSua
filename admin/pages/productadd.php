<?php 
include 'header.php'; 
include_once '../classes/category.php';
include_once '../classes/product.php';
?>
<?php 
$prod = new product();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $insertProduct = $prod->insert_product($_POST, $_FILES);
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
                <span class="textHeading">Thêm sản phẩm</span>
            </div>
            <div class="panel-body">
                <?php
                if (isset($insertProduct)) {
                    echo $insertProduct;
                }
                ?>   
                <form method="POST" enctype="multipart/form-data" name="formUser" onsubmit="return validationForm();">
                    <table style="width: 100%;">
                        <tr>
                            <td class="tabLabel">
                                <label class="labelAddProduct">Tên sản phẩm: </label>
                            </td>
                            <td>
                                <input type="text" name="tenSanPham" placeholder="Nhập tên sản phẩm..." class="inputAddProduct" autofocus required>
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
                                    if ($catlist) {
                                        while ($result = $catlist->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $result['maLoai']; ?>"><?php echo $result['tenLoai']; ?></option>
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
                                <textarea name="mieuTaSanPham" rows="2" cols="25" placeholder="Nhập miêu tả sản phẩm..." class="inputAddProduct" style="height: 80px;" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="tabLabel">
                                <label class="labelAddProduct">Hình ảnh sản phẩm: </label>
                            </td>
                            <td>
                                <input name="image" type="file" accept="image/*" onchange="loadFile(event)" required>
                                <img id="output" style="width: 20%;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="tabLabel">
                                <label class="labelAddProduct">Kích thước, giá và số lượng: </label>
                            </td>
                            <td>
                                <div id="size-container">
                                <div class="size-row">
                                    <select name="sizes[]" class="inputAddProduct" style="width: 120px;" required>
                                        <option value="">Chọn size</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                    </select>
                                    <input type="number" name="prices[]" placeholder="Giá (VNĐ)" class="inputAddProduct" style="width: 150px;" required>
                                    <input type="number" name="quantities[]" placeholder="Số lượng" class="inputAddProduct" style="width: 120px;" required>
                                    <button type="button" onclick="removeSizeRow(this)" class="btn btn-danger" style="margin-left: 10px;">Xóa</button>
                                </div>
                                </div>
                                <button type="button" onclick="addSizeRow()" class="btn btn-primary" style="margin-top: 10px;">Thêm kích thước</button>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="submit" value="Thêm sản phẩm" class="btn btn-success" style="margin: 10px;">
                </form>  
            </div>  
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

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

<script type="text/javascript">
function validationForm() {
    var maLoai = document.formUser.maLoai.value;
    var sizes = document.getElementsByName('sizes[]');
    var prices = document.getElementsByName('prices[]');
    var quantities = document.getElementsByName('quantities[]');
    var image = document.formUser.image.value;

    if (maLoai == '0') {
        alert("Chưa chọn danh mục sản phẩm!");
        return false;
    }
    for (var i = 0; i < sizes.length; i++) {
        if (sizes[i].value == '' || prices[i].value == '' || quantities[i].value == '') {
            alert("Vui lòng nhập đầy đủ kích thước, giá và số lượng!");
            return false;
        }
    }
    if (image == '') {
        alert("Chưa chọn hình ảnh sản phẩm!");
        return false;
    }
    return true;
}

function addSizeRow() {
    var container = document.getElementById('size-container');
    var newRow = document.createElement('div');
    newRow.className = 'size-row';
    newRow.style.marginTop = '10px';
    newRow.innerHTML = '<select name="sizes[]" class="inputAddProduct" style="width: 120px;" required>' +
                       '<option value="">Chọn size</option>' +
                       '<option value="S">S</option>' +
                       '<option value="M">M</option>' +
                       '<option value="L">L</option>' +
                       '</select>' +
                       '<input type="number" name="prices[]" placeholder="Giá (VNĐ)" class="inputAddProduct" style="width: 150px;" required>' +
                       '<input type="number" name="quantities[]" placeholder="Số lượng" class="inputAddProduct" style="width: 120px;" value="1" required>' +
                       '<button type="button" onclick="removeSizeRow(this)" class="btn btn-danger" style="margin-left: 10px;">Xóa</button>';
    container.appendChild(newRow);
}

function removeSizeRow(button) {
    var container = document.getElementById('size-container');
    // Ensure at least one row remains
    if (container.getElementsByClassName('size-row').length > 1) {
        button.parentElement.remove();
    } else {
        alert("Phải có ít nhất một kích thước!");
    }
}

var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src); // Giải phóng bộ nhớ
    };
};
</script>
</body>
</html>