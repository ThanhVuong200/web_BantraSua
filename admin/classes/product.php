<?php
include_once '../lib/database.php';
include_once '../helpers/format.php';

class product
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    // Thêm sản phẩm mới
    public function insert_product($data, $files)
    {
        $tenSanPham = mysqli_real_escape_string($this->db->link, $data['tenSanPham']);
        $maLoai = (int)$data['maLoai']; // Ép kiểu số nguyên
        $mieuTaSanPham = mysqli_real_escape_string($this->db->link, $data['mieuTaSanPham']);

        // Kiểm tra hình ảnh và tải lên
        $permited = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_temp = $_FILES['image']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(time(), 0, 10) . '.' . $file_ext;
        $uploaded_image = 'uploads/' . $unique_image;

        // Kiểm tra dữ liệu đầu vào
        if ($tenSanPham == "" || $maLoai == 0 || $mieuTaSanPham == "" || $file_name == "") {
            return "<div class='alert alert-danger'>Không được để trống!</div>";
        }

        $sizes = isset($data['sizes']) ? $data['sizes'] : [];
        $prices = isset($data['prices']) ? $data['prices'] : [];
        $quantities = isset($data['quantities']) ? $data['quantities'] : [];

        if (empty($sizes) || empty($prices) || empty($quantities)) {
            return "<div class='alert alert-danger'>Phải nhập ít nhất một kích thước, giá và số lượng!</div>";
        }

        // Thêm sản phẩm vào tbl_sanpham với prepared statement
        $query = "INSERT INTO tbl_sanpham (tenSanPham, maLoai, mieuTaSanPham, hinhAnhSanPham) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            return "<div class='alert alert-danger'>Lỗi chuẩn bị truy vấn: " . $this->db->link->error . "</div>";
        }
        $stmt->bind_param("siss", $tenSanPham, $maLoai, $mieuTaSanPham, $unique_image);
        move_uploaded_file($file_temp, $uploaded_image);
        $result = $stmt->execute();

        if ($result) {
            $maSanPham = mysqli_insert_id($this->db->link);
            // Thêm các kích thước vào tbl_product_size
            $sizeStmt = $this->db->link->prepare("INSERT INTO tbl_product_size (maSanPham, size, giaSanPham, soLuongSanPham) VALUES (?, ?, ?, ?)");
            if ($sizeStmt === false) {
                return "<div class='alert alert-danger'>Lỗi chuẩn bị truy vấn kích thước: " . $this->db->link->error . "</div>";
            }

            for ($i = 0; $i < count($sizes); $i++) {
                $size = mysqli_real_escape_string($this->db->link, $sizes[$i]);
                $price = (float)$prices[$i];
                $quantity = (int)$quantities[$i];
                $sizeStmt->bind_param("isdi", $maSanPham, $size, $price, $quantity);
                $sizeStmt->execute();
            }
            $sizeStmt->close();
            return "<div class='alert alert-success'>Thêm sản phẩm thành công!</div>";
        } else {
            return "<div class='alert alert-danger'>Thêm sản phẩm không thành công! Lỗi: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }

    // Hiển thị danh sách sản phẩm (có phân trang và tìm kiếm)
    public function show_product()
    {
        $sanPhamTungTrang = 5;
        $trang = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
        $tungTrang = ($trang - 1) * $sanPhamTungTrang;

        if (isset($_GET['nameSearch']) && !empty($_GET['nameSearch'])) {
            $nameSearch = '%' . mysqli_real_escape_string($this->db->link, $_GET['nameSearch']) . '%';
            $query = "SELECT s.*, c.tenLoai, GROUP_CONCAT(ps.size) as sizes, SUM(ps.soLuongSanPham) as totalStock, 
                             MIN(ps.giaSanPham) as minPrice, MAX(ps.giaSanPham) as maxPrice 
                      FROM tbl_sanpham s 
                      JOIN tbl_loaisanpham c ON s.maLoai = c.maLoai 
                      LEFT JOIN tbl_product_size ps ON s.maSanPham = ps.maSanPham 
                      WHERE s.trangThaiSanPham = '1' AND s.tenSanPham LIKE ? 
                      GROUP BY s.maSanPham 
                      ORDER BY s.maSanPham DESC 
                      LIMIT ?, ?";
            $stmt = $this->db->link->prepare($query);
            $stmt->bind_param("sii", $nameSearch, $tungTrang, $sanPhamTungTrang);
        } else {
            $query = "SELECT s.*, c.tenLoai, GROUP_CONCAT(ps.size) as sizes, SUM(ps.soLuongSanPham) as totalStock, 
                             MIN(ps.giaSanPham) as minPrice, MAX(ps.giaSanPham) as maxPrice 
                      FROM tbl_sanpham s 
                      JOIN tbl_loaisanpham c ON s.maLoai = c.maLoai 
                      LEFT JOIN tbl_product_size ps ON s.maSanPham = ps.maSanPham 
                      WHERE s.trangThaiSanPham = '1' 
                      GROUP BY s.maSanPham 
                      ORDER BY s.maSanPham DESC 
                      LIMIT ?, ?";
            $stmt = $this->db->link->prepare($query);
            $stmt->bind_param("ii", $tungTrang, $sanPhamTungTrang);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // Lấy tất cả sản phẩm (dùng cho phân trang)
    public function getAllProduct()
    {
        if (isset($_GET['nameSearch']) && !empty($_GET['nameSearch'])) {
            $nameSearch = '%' . mysqli_real_escape_string($this->db->link, $_GET['nameSearch']) . '%';
            $query = "SELECT s.*, c.tenLoai 
                      FROM tbl_sanpham s 
                      JOIN tbl_loaisanpham c ON s.maLoai = c.maLoai 
                      WHERE s.trangThaiSanPham = '1' AND s.tenSanPham LIKE ?";
            $stmt = $this->db->link->prepare($query);
            $stmt->bind_param("s", $nameSearch);
        } else {
            $query = "SELECT s.*, c.tenLoai 
                      FROM tbl_sanpham s 
                      JOIN tbl_loaisanpham c ON s.maLoai = c.maLoai 
                      WHERE s.trangThaiSanPham = '1'";
            $stmt = $this->db->link->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // Đếm tổng số lượng sản phẩm trong kho
    public function count_product()
    {
        $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE trangThaiSanPham = '1'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    // Lấy sản phẩm theo ID (dùng để sửa)
    public function getproductbyId($id)
    {
        $query = "SELECT s.*, GROUP_CONCAT(ps.size) as sizes, SUM(ps.soLuongSanPham) as totalStock, 
                         MIN(ps.giaSanPham) as minPrice, MAX(ps.giaSanPham) as maxPrice 
                  FROM tbl_sanpham s 
                  LEFT JOIN tbl_product_size ps ON s.maSanPham = ps.maSanPham 
                  WHERE s.maSanPham = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
    public function getProductSizes($productId) {
        $query = "SELECT id, size, soLuongSanPham, giaSanPham FROM tbl_product_size WHERE maSanPham = ?";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn getProductSizes: " . $this->db->link->error);
            return false;
        }
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // Sửa sản phẩm
    public function edit_product($data, $files, $id) {
        $tenSanPham = $data['tenSanPham'];
        $maLoai = $data['maLoai'];
        $mieuTaSanPham = $data['mieuTaSanPham'];
        $permited = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $files['image']['name'];
        $file_size = $files['image']['size'];
        $file_temp = $files['image']['tmp_name'];
    
        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = "uploads/" . $unique_image;
    
        if ($tenSanPham == "" || $maLoai == "" || $mieuTaSanPham == "") {
            return "<span class='error'>Các trường không được để trống!</span>";
        } else {
            if (!empty($file_name)) {
                if ($file_size > 2048000) {
                    return "<span class='error'>Kích thước file quá lớn (max 2MB)!</span>";
                } elseif (in_array($file_ext, $permited) === false) {
                    return "<span class='error'>Bạn chỉ có thể tải lên: " . implode(', ', $permited) . "</span>";
                }
                move_uploaded_file($file_temp, $uploaded_image);
                $query = "UPDATE tbl_sanpham SET tenSanPham = ?, maLoai = ?, mieuTaSanPham = ?, hinhAnhSanPham = ? WHERE maSanPham = ?";
                $stmt = $this->db->link->prepare($query);
                if ($stmt === false) {
                    error_log("Lỗi chuẩn bị truy vấn update tbl_sanpham: " . $this->db->link->error);
                    return "<span class='error'>Lỗi khi cập nhật sản phẩm!</span>";
                }
                $stmt->bind_param("sisss", $tenSanPham, $maLoai, $mieuTaSanPham, $uploaded_image, $id);
                $update_product = $stmt->execute();
                $stmt->close();
    
                if ($update_product) {
                    // Xử lý kích thước, số lượng, và giá từ tbl_product_size
                    if (isset($data['sizes']) && isset($data['quantities']) && isset($data['prices'])) {
                        $sizes = $data['sizes'];
                        $quantities = $data['quantities'];
                        $prices = $data['prices'];
                        $count = count($sizes);
    
                        // Xóa tất cả kích thước cũ của sản phẩm
                        $delete_query = "DELETE FROM tbl_product_size WHERE maSanPham = ?";
                        $stmt_delete = $this->db->link->prepare($delete_query);
                        if ($stmt_delete === false) {
                            error_log("Lỗi chuẩn bị truy vấn xóa tbl_product_size: " . $this->db->link->error);
                            return "<span class='error'>Lỗi khi xóa kích thước cũ!</span>";
                        }
                        $stmt_delete->bind_param("i", $id);
                        $stmt_delete->execute();
                        $stmt_delete->close();
    
                        // Thêm kích thước mới
                        for ($i = 0; $i < $count; $i++) {
                            if (!empty($sizes[$i]) && isset($quantities[$i]) && isset($prices[$i])) {
                                $size = $sizes[$i];
                                $quantity = (int)$quantities[$i];
                                $price = (int)$prices[$i];
    
                                $insert_query = "INSERT INTO tbl_product_size (maSanPham, size, soLuongSanPham, giaSanPham) VALUES (?, ?, ?, ?)";
                                $stmt_insert = $this->db->link->prepare($insert_query);
                                if ($stmt_insert === false) {
                                    error_log("Lỗi chuẩn bị truy vấn thêm tbl_product_size: " . $this->db->link->error);
                                    return "<span class='error'>Lỗi khi thêm kích thước mới!</span>";
                                }
                                $stmt_insert->bind_param("isii", $id, $size, $quantity, $price);
                                $stmt_insert->execute();
                                $stmt_insert->close();
                            }
                        }
                    }
                    return "<span class='success'>Cập nhật sản phẩm thành công!</span>";
                } else {
                    return "<span class='error'>Cập nhật sản phẩm không thành công!</span>";
                }
            } else {
                $query = "UPDATE tbl_sanpham SET tenSanPham = ?, maLoai = ?, mieuTaSanPham = ? WHERE maSanPham = ?";
                $stmt = $this->db->link->prepare($query);
                if ($stmt === false) {
                    error_log("Lỗi chuẩn bị truy vấn update tbl_sanpham (no image): " . $this->db->link->error);
                    return "<span class='error'>Lỗi khi cập nhật sản phẩm!</span>";
                }
                $stmt->bind_param("sisi", $tenSanPham, $maLoai, $mieuTaSanPham, $id);
                $update_product = $stmt->execute();
                $stmt->close();
    
                if ($update_product) {
                    // Xử lý kích thước, số lượng, và giá từ tbl_product_size
                    if (isset($data['sizes']) && isset($data['quantities']) && isset($data['prices'])) {
                        $sizes = $data['sizes'];
                        $quantities = $data['quantities'];
                        $prices = $data['prices'];
                        $count = count($sizes);
    
                        // Xóa tất cả kích thước cũ của sản phẩm
                        $delete_query = "DELETE FROM tbl_product_size WHERE maSanPham = ?";
                        $stmt_delete = $this->db->link->prepare($delete_query);
                        if ($stmt_delete === false) {
                            error_log("Lỗi chuẩn bị truy vấn xóa tbl_product_size: " . $this->db->link->error);
                            return "<span class='error'>Lỗi khi xóa kích thước cũ!</span>";
                        }
                        $stmt_delete->bind_param("i", $id);
                        $stmt_delete->execute();
                        $stmt_delete->close();
    
                        // Thêm kích thước mới
                        for ($i = 0; $i < $count; $i++) {
                            if (!empty($sizes[$i]) && isset($quantities[$i]) && isset($prices[$i])) {
                                $size = $sizes[$i];
                                $quantity = (int)$quantities[$i];
                                $price = (int)$prices[$i];
    
                                $insert_query = "INSERT INTO tbl_product_size (maSanPham, size, soLuongSanPham, giaSanPham) VALUES (?, ?, ?, ?)";
                                $stmt_insert = $this->db->link->prepare($insert_query);
                                if ($stmt_insert === false) {
                                    error_log("Lỗi chuẩn bị truy vấn thêm tbl_product_size: " . $this->db->link->error);
                                    return "<span class='error'>Lỗi khi thêm kích thước mới!</span>";
                                }
                                $stmt_insert->bind_param("isii", $id, $size, $quantity, $price);
                                $stmt_insert->execute();
                                $stmt_insert->close();
                            }
                        }
                    }
                    return "<span class='success'>Cập nhật sản phẩm thành công!</span>";
                } else {
                    return "<span class='error'>Cập nhật sản phẩm không thành công!</span>";
                }
            }
        }
    }
    public function show_chitiethoadon2($id)
    {
        $query = "SELECT cthd.*, s.tenSP, ps.size, ps.giaSP, ps.soLuongSanPham, s.hinhAnhSP 
                FROM tbl_chitiethoadon cthd 
                JOIN tbl_sanpham s ON cthd.maSP = s.maSanPham 
                JOIN tbl_product_size ps ON cthd.product_size_id = ps.id 
                WHERE cthd.maHoaDon = ? 
                GROUP BY cthd.product_size_id";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn show_chitiethoadon2: " . $this->db->link->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
    // Ẩn sản phẩm
    public function hide_product($id)
    {
        $query = "UPDATE tbl_sanpham SET trangThaiSanPham = 0 WHERE maSanPham = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return "<div class='alert alert-success'>Xóa sản phẩm thành công!</div>";
        } else {
            return "<div class='alert alert-danger'>Xóa sản phẩm không thành công! Lỗi: " . $this->db->link->error . "</div>";
        }
    }

    // Hiển thị sản phẩm theo danh mục (dùng trong shop-gird.php)
    public function show_productbyCat($catId)
    {
        $query = "SELECT s.*, GROUP_CONCAT(ps.size) as sizes, MIN(ps.giaSanPham) as minPrice 
                  FROM tbl_sanpham s 
                  LEFT JOIN tbl_product_size ps ON s.maSanPham = ps.maSanPham 
                  WHERE s.maLoai = ? AND s.trangThaiSanPham = '1' 
                  GROUP BY s.maSanPham 
                  ORDER BY s.maSanPham ASC";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("i", $catId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // Lấy tất cả sản phẩm theo danh mục (dùng trong shop-gird.php)
    public function getAllProductbyCat($catId)
    {
        $query = "SELECT s.* FROM tbl_sanpham s WHERE s.maLoai = ? AND s.trangThaiSanPham = '1'";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("i", $catId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }


    // Hiển thị kết quả tìm kiếm (dùng trong search.php)
    public function show_search_result($nameSearch)
    {
        $query = "SELECT sp.*, MIN(ps.giaSanPham) AS giaSanPham 
                  FROM tbl_sanpham sp 
                  LEFT JOIN tbl_product_size ps ON sp.maSanPham = ps.maSanPham 
                  WHERE sp.tenSanPham LIKE ? AND sp.trangThaiSanPham = '1' 
                  GROUP BY sp.maSanPham 
                  HAVING MIN(ps.giaSanPham) IS NOT NULL 
                  ORDER BY sp.maSanPham DESC 
                  LIMIT ?, ?";
        $trang = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
        $tungTrang = ($trang - 1) * 8; // 8 sản phẩm mỗi trang, theo logic phân trang trong search.php
        $searchTerm = "%$nameSearch%";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn show_search_result: " . $this->db->link->error);
            return false;
        }
        $stmt->bind_param("sii", $searchTerm, $tungTrang, 8);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function getAllProductSearch($nameSearch)
    {
        $query = "SELECT sp.* 
                  FROM tbl_sanpham sp 
                  WHERE sp.tenSanPham LIKE ? AND sp.trangThaiSanPham = '1' 
                  ORDER BY sp.maSanPham DESC";
        $searchTerm = "%$nameSearch%";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn getAllProductSearch: " . $this->db->link->error);
            return false;
        }
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function show_productLimit10Asc()
    {
        $query = "SELECT sp.*, MIN(ps.giaSanPham) AS giaSanPham 
                  FROM tbl_sanpham sp 
                  LEFT JOIN tbl_product_size ps ON sp.maSanPham = ps.maSanPham 
                  WHERE sp.trangThaiSanPham = '1' 
                  GROUP BY sp.maSanPham 
                  HAVING MIN(ps.giaSanPham) IS NOT NULL 
                  ORDER BY sp.maSanPham ASC 
                  LIMIT 10";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn show_productLimit10Asc: " . $this->db->link->error);
            return false;
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
}
?>