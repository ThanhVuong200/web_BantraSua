<?php  
include_once 'admin/lib/database.php';
include_once 'admin/helpers/format.php';

class product
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function show_product()
    {
        $query = "SELECT * FROM tbl_sanpham, tbl_loaisanpham WHERE tbl_sanpham.maLoai = tbl_loaisanpham.maLoai ORDER BY maSanPham ASC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_productbyCat($idLoai)
	{
		$sanPhamTungTrang = 8; // Sản phẩm từng trang
		$trang = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
		$tungTrang = ($trang - 1) * $sanPhamTungTrang; // Vị trí bắt đầu
		$sortPrice = isset($_GET['sortPrice']) ? $_GET['sortPrice'] : ''; // Lấy tham số sortPrice
		$sizeSP = isset($_GET['size']) ? $_GET['size'] : ''; // Lấy tham số size nếu có

		// Xây dựng truy vấn cơ bản
		$query = "SELECT * FROM tbl_sanpham 
				INNER JOIN tbl_loaisanpham ON tbl_sanpham.maLoai = tbl_loaisanpham.maLoai 
				WHERE tbl_sanpham.maLoai = ? AND tbl_sanpham.trangThaiSanPham = '1'";
		
		// Thêm điều kiện size nếu có
		if (!empty($sizeSP)) {
			$query .= " AND sizeSanPham = ?";
		}

		// Thêm điều kiện sắp xếp
		if ($sortPrice == 'low_to_high') {
			$query .= " ORDER BY giaSanPham ASC";
		} elseif ($sortPrice == 'high_to_low') {
			$query .= " ORDER BY giaSanPham DESC";
		} else {
			$query .= " ORDER BY maSanPham ASC"; // Mặc định
		}

		// Thêm phân trang
		$query .= " LIMIT ?, ?";

		// Chuẩn bị và thực thi truy vấn với prepared statement
		$stmt = $this->db->link->prepare($query);
		if (!$stmt) {
			error_log("Prepare failed: " . $this->db->link->error);
			return null;
		}

		if (!empty($sizeSP)) {
			$stmt->bind_param("isii", $idLoai, $sizeSP, $tungTrang, $sanPhamTungTrang);
		} else {
			$stmt->bind_param("iii", $idLoai, $tungTrang, $sanPhamTungTrang);
		}

		if (!$stmt->execute()) {
			error_log("Execute failed: " . $stmt->error);
			$stmt->close();
			return null;
		}

		$result = $stmt->get_result();
		if (!$result) {
			error_log("Get result failed: " . $stmt->error);
			$stmt->close();
			return null;
		}

		$stmt->close();
		return $result;
	}

    public function getAllProductbyCat($idLoai) // Dùng cho phân trang
    {
        $sizeSP = isset($_GET['size']) ? $_GET['size'] : ''; // Lấy tham số size nếu có

        // Xây dựng truy vấn cơ bản
        $query = "SELECT * FROM tbl_sanpham 
                  INNER JOIN tbl_loaisanpham ON tbl_sanpham.maLoai = tbl_loaisanpham.maLoai 
                  WHERE tbl_sanpham.maLoai = ? AND tbl_sanpham.trangThaiSanPham = '1'";
        
        // Thêm điều kiện size nếu có
        if (!empty($sizeSP)) {
            $query .= " AND sizeSanPham = ?";
        }

        // Chuẩn bị và thực thi truy vấn với prepared statement
        $stmt = $this->db->link->prepare($query);
        if (!empty($sizeSP)) {
            $stmt->bind_param("is", $idLoai, $sizeSP);
        } else {
            $stmt->bind_param("i", $idLoai);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function show_productLimit10Asc()
	{
		$query = "SELECT * FROM tbl_sanpham, tbl_loaisanpham WHERE tbl_sanpham.maLoai = tbl_loaisanpham.maLoai ORDER BY RAND() LIMIT 10";
		$result = $this->db->select($query);
		return $result;
	}

    public function show_productLimit5() // FRONT-END
    {
        $query = "SELECT * FROM tbl_sanpham, tbl_loaisanpham WHERE tbl_sanpham.maLoai = tbl_loaisanpham.maLoai ORDER BY maSanPham DESC LIMIT 5";
        $result = $this->db->select($query);
        return $result;
    }

    public function count_product()
    {
        $query = "SELECT SUM(soLuongSanPham) AS soluongprod FROM tbl_sanpham";
        $result = $this->db->select($query);
        return $result;
    }

    public function getproductbyId($id) 
    {
        $query = "SELECT p.*, IFNULL((
                    SELECT SUM(soLuongSanPham) 
                    FROM tbl_chitietdonhang 
                    WHERE maSanPham = p.maSanPham
                  ), 0) as soLuotBan 
                  FROM tbl_sanpham p 
                  WHERE p.maSanPham = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_search_result($nameSearch) // Show product, có tìm kiếm theo tên, kết quả tìm kiếm phân trang
    {
        $sanPhamTungTrang = 8; // Sản phẩm từng trang
        $trang = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
        $tungTrang = ($trang - 1) * $sanPhamTungTrang; // Vị trí bắt đầu

        $query = "SELECT * FROM tbl_sanpham 
                  INNER JOIN tbl_loaisanpham ON tbl_sanpham.maLoai = tbl_loaisanpham.maLoai 
                  WHERE tbl_sanpham.trangThaiSanPham = '1' AND tbl_sanpham.tenSanPham LIKE ? 
                  ORDER BY maSanPham DESC LIMIT ?, ?";
        
        $searchTerm = "%$nameSearch%";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("sii", $searchTerm, $tungTrang, $sanPhamTungTrang);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function getAllProductSearch($nameSearch) // Dùng cho phân trang
    {
        $query = "SELECT * FROM tbl_sanpham 
                  INNER JOIN tbl_loaisanpham ON tbl_sanpham.maLoai = tbl_loaisanpham.maLoai 
                  WHERE tbl_sanpham.trangThaiSanPham = '1' AND tbl_sanpham.tenSanPham LIKE ? 
                  ORDER BY maSanPham DESC";
        
        $searchTerm = "%$nameSearch%";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function getFavoriteProducts($maKhachHang, $offset = 0, $limit = 8) {
        global $conn;
        $query = "SELECT sp.* 
                  FROM tbl_sanpham sp 
                  INNER JOIN tbl_sanphamyeuthich yt ON sp.maSanPham = yt.maSanPham 
                  WHERE yt.maKhachHang = ? AND sp.trangThaiSanPham = '1'
                  ORDER BY sp.maSanPham DESC
                  LIMIT ?, ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $maKhachHang, $offset, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
}
?>