<?php
require_once '../lib/database.php';
require_once '../helpers/format.php';

class donhang
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function show_donhang()
    {
        $query = "SELECT * FROM tbl_donhang ORDER BY maDonHang DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_chitietdonhang($id)
    {
        $query = "SELECT dh.*, ctdh.* 
                  FROM tbl_donhang dh 
                  LEFT JOIN tbl_chitietdonhang ctdh ON dh.maDonHang = ctdh.maDonHang 
                  WHERE dh.maDonHang = ? 
                  ORDER BY dh.maDonHang DESC";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn show_chitietdonhang: " . $this->db->link->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function show_chitietdonhang2($id)
    {
        // Lấy thông tin chi tiết đơn hàng, sử dụng product_size_id để lấy size, giá, số lượng từ tbl_product_size
        $query = "SELECT ctdh.*, s.tenSanPham, ps.size, ps.giaSanPham, ps.soLuongSanPham, p.hinhAnhSanPham AS hinhAnhSP 
                  FROM tbl_chitietdonhang ctdh 
                  JOIN tbl_sanpham s ON ctdh.maSanPham = s.maSanPham 
                  JOIN tbl_product_size ps ON ctdh.product_size_id = ps.id 
                  JOIN tbl_sanpham p ON ps.maSanPham = p.maSanPham 
                  WHERE ctdh.maDonHang = ? 
                  GROUP BY ctdh.product_size_id";
        $stmt = $this->db->link->prepare($query);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn show_chitietdonhang2: " . $this->db->link->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function show_donhangPhanTrang()
    {
        $sanPhamTungTrang = 10; // Số đơn hàng mỗi trang
        $trang = isset($_GET['trang']) ? $_GET['trang'] : 1;
        $tungTrang = ($trang - 1) * $sanPhamTungTrang;

        // Lọc theo ngày
        if (isset($_GET['ngaytruoc']) && !empty($_GET['ngaytruoc']) && isset($_GET['ngaysau']) && !empty($_GET['ngaysau'])) {
            $ngaytruoc = $_GET['ngaytruoc'];
            $ngaysau = $_GET['ngaysau'];
            $query = "SELECT * FROM tbl_donhang 
                      WHERE ngayLapDH BETWEEN '$ngaytruoc' AND '$ngaysau' 
                      ORDER BY maDonHang DESC 
                      LIMIT $tungTrang, $sanPhamTungTrang";
        } else {
            $query = "SELECT * FROM tbl_donhang 
                      ORDER BY maDonHang DESC 
                      LIMIT $tungTrang, $sanPhamTungTrang";
        }

        $result = $this->db->select($query);
        return $result;
    }

    public function DoiTrangThaiDH($id)
    {
        // Kiểm tra trạng thái hiện tại của đơn hàng
        $querySelect = "SELECT * FROM tbl_donhang WHERE maDonHang = '$id'";
        $resultSelect = $this->db->select($querySelect);
        if (!$resultSelect) {
            return "<div class='alert alert-danger'>Không tìm thấy đơn hàng!</div>";
        }

        $value = $resultSelect->fetch_assoc();
        if ($value['trangThaiDH'] !== 'Chưa giao') {
            return "<div class='alert alert-warning'>Đơn hàng đã được xử lý trước đó!</div>";
        }

        // Cập nhật trạng thái đơn hàng
        $queryHoanThanhHD = "UPDATE tbl_donhang SET trangThaiDH = 'Đã hoàn thành' WHERE maDonHang = '$id'";
        $resultUpdate = $this->db->update($queryHoanThanhHD);

        if (!$resultUpdate) {
            return "<div class='alert alert-danger'>Cập nhật trạng thái không thành công!</div>";
        }

        // Lấy thông tin để thêm vào hóa đơn
        $maKH = $value['maKhachHang'];
        $ngaydat = date("Y-m-d");
        $giatri = $value['tongTienDH'];

        // Lấy mã hóa đơn mới
        $queryMHD = "SELECT MAX(maHoaDon) FROM tbl_hoadon";
        $resultMHD = $this->db->select($queryMHD);
        $fetchMHD = $resultMHD->fetch_assoc();
        $dataMHDNew = $fetchMHD['MAX(maHoaDon)'] ? $fetchMHD['MAX(maHoaDon)'] + 1 : 1;

        // Thêm vào bảng tbl_hoadon
        $queryThemHD = "INSERT INTO tbl_hoadon (maHoaDon, maKhachHang, ngayDat, giaTriHD) 
                        VALUES ('$dataMHDNew', '$maKH', '$ngaydat', '$giatri')";
        $resultThemHD = $this->db->insert($queryThemHD);

        if (!$resultThemHD) {
            return "<div class='alert alert-danger'>Thêm hóa đơn không thành công!</div>";
        }

        // Thêm chi tiết hóa đơn từ chi tiết đơn hàng
        $queryDH = "SELECT * FROM tbl_chitietdonhang WHERE maDonHang = '$id'";
        $resultdataGH = $this->db->select($queryDH);

        if ($resultdataGH) {
            while ($dataGH = $resultdataGH->fetch_assoc()) {
                $tenNN = $dataGH['tenNguoiNhan'];
                $sdtKH = $dataGH['sdtKH'];
                $diachiNN = $dataGH['diachi'];
                $ghiChu = $dataGH['ghiChuCuaKhachhang'];
                $maSP = $dataGH['maSanPham'];
                $tenSP = $dataGH['tenSanPham'];
                $product_size_id = $dataGH['product_size_id'];
                $mieuTaSP = $dataGH['mieuTaSP'];
                $hinhAnhSP = $dataGH['hinhAnhSP'];

                $querychitietdonhang = "INSERT INTO tbl_chitiethoadon 
                                        (maHoaDon, tenNguoiNhan, sdtKH, ghiChu, maSP, tenSP, product_size_id, mieuTaSP, hinhAnhSP, diachi) 
                                        VALUES ('$dataMHDNew', '$tenNN', '$sdtKH', '$ghiChu', '$maSP', '$tenSP', '$product_size_id', '$mieuTaSP', '$hinhAnhSP', '$diachiNN')";
                $themCTHD = $this->db->insert($querychitietdonhang);

                if (!$themCTHD) {
                    return "<div class='alert alert-danger'>Thêm chi tiết hóa đơn không thành công!</div>";
                }
            }
        }

        return "<div class='alert alert-success'>Thành công!</div>";
    }
    public function getAllDonHang()
    {
        if (isset($_GET['wordSearch']) && !empty($_GET['wordSearch'])) {
            if (isset($_GET['timTheo']) && !empty($_GET['timTheo'])) {
                $wordSearch = mysqli_real_escape_string($this->db->link, $_GET['wordSearch']);
                $timTheo = mysqli_real_escape_string($this->db->link, $_GET['timTheo']);

                if ($timTheo == "Mã đơn hàng") {
                    $query = "SELECT * FROM tbl_donhang WHERE maDonHang LIKE ? ORDER BY maDonHang DESC";
                    $stmt = $this->db->link->prepare($query);
                    $searchTerm = "%$wordSearch%";
                    $stmt->bind_param("s", $searchTerm);
                } elseif ($timTheo == "Mã khách hàng") {
                    $query = "SELECT * FROM tbl_donhang WHERE maKhachHang LIKE ? ORDER BY maDonHang DESC";
                    $stmt = $this->db->link->prepare($query);
                    $searchTerm = "%$wordSearch%";
                    $stmt->bind_param("s", $searchTerm);
                } elseif ($timTheo == "Trạng thái") {
                    $query = "SELECT * FROM tbl_donhang WHERE trangThaiDH LIKE ? ORDER BY maDonHang DESC";
                    $stmt = $this->db->link->prepare($query);
                    $searchTerm = "%$wordSearch%";
                    $stmt->bind_param("s", $searchTerm);
                } elseif ($timTheo == "Ngày lập đơn hàng") {
                    $query = "SELECT * FROM tbl_donhang WHERE ngayLapDH LIKE ? ORDER BY maDonHang DESC";
                    $stmt = $this->db->link->prepare($query);
                    $searchTerm = "%$wordSearch%";
                    $stmt->bind_param("s", $searchTerm);
                } elseif ($timTheo == "Mã giao hàng") {
                    $query = "SELECT * FROM tbl_donhang WHERE maGiaoHang LIKE ? ORDER BY maDonHang DESC";
                    $stmt = $this->db->link->prepare($query);
                    $searchTerm = "%$wordSearch%";
                    $stmt->bind_param("s", $searchTerm);
                }

                if (isset($stmt)) {
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    return $result;
                }
            }
        } else {
            $query = "SELECT * FROM tbl_donhang ORDER BY maDonHang DESC";
            $result = $this->db->select($query);
            return $result;
        }

        return false; // Trả về false nếu không có điều kiện nào khớp
    }

    public function count_donhangchuagiao()
    {
        $query = "SELECT COUNT(maDonHang) AS soluongDHchuagiao FROM tbl_donhang WHERE trangThaiDH = 'Chưa giao'";
        $result = $this->db->select($query);
        return $result;
    }

    public function count_donhangdagiao()
    {
        $query = "SELECT COUNT(maDonHang) AS soluongDHdagiao FROM tbl_donhang WHERE trangThaiDH = 'Đã hoàn thành'";
        $result = $this->db->select($query);
        return $result;
    }

    public function count_donhang()
    {
        $query = "SELECT COUNT(*) as total FROM tbl_donhang";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    public function getTotalRevenue()
    {
        $query = "SELECT SUM(tongTienDH) as total FROM tbl_donhang WHERE trangThaiDH = 'Đã hoàn thành'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        return 0;
    }

    public function getTodayRevenue()
    {
        $query = "SELECT SUM(tongTienDH) as total FROM tbl_donhang 
                  WHERE DATE(ngayLapDH) = CURDATE() AND trangThaiDH = 'Đã hoàn thành'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        return 0;
    }

    public function getMonthRevenue()
    {
        $query = "SELECT SUM(tongTienDH) as total FROM tbl_donhang 
                  WHERE MONTH(ngayLapDH) = MONTH(CURRENT_DATE()) 
                  AND YEAR(ngayLapDH) = YEAR(CURRENT_DATE())
                  AND trangThaiDH = 'Đã hoàn thành'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        return 0;
    }

    public function getYearRevenue()
    {
        $query = "SELECT SUM(tongTienDH) as total FROM tbl_donhang 
                  WHERE YEAR(ngayLapDH) = YEAR(CURRENT_DATE())
                  AND trangThaiDH = 'Đã hoàn thành'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        return 0;
    }

    public function getRevenueList($startDate = null, $endDate = null)
    {
        $query = "SELECT DATE(ngayLapDH) as ngayDatHang,
                         COUNT(*) as soLuongDon,
                         SUM(tongTienDH) as doanhThu,
                         SUM(tongTienDH * 0.2) as loiNhuan
                  FROM tbl_donhang 
                  WHERE trangThaiDH = 'Đã hoàn thành'";
        
        if ($startDate && $endDate) {
            $query .= " AND ngayLapDH BETWEEN '$startDate' AND '$endDate'";
        }
        
        $query .= " GROUP BY DATE(ngayLapDH) ORDER BY ngayLapDH DESC LIMIT 30";
        
        return $this->db->select($query);
    }

    public function getMonthlyRevenue()
    {
        $query = "SELECT MONTH(ngayLapDH) as thang,
                         SUM(tongTienDH) as doanhThu
                  FROM tbl_donhang 
                  WHERE YEAR(ngayLapDH) = YEAR(CURRENT_DATE())
                  AND trangThaiDH = 'Đã hoàn thành'
                  GROUP BY MONTH(ngayLapDH)
                  ORDER BY MONTH(ngayLapDH)";
        
        return $this->db->select($query);
    }
}
?>