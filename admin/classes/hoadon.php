<?php
require_once '../lib/database.php';
require_once '../helpers/format.php';

class hoadon
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function show_hoadon()
    {
        $query = "SELECT * FROM tbl_hoadon ORDER BY maHoaDon DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_chitiethoadon($id)
    {
        $query = "SELECT hd.*, cthd.tenNguoiNhan, cthd.sdtKH, cthd.ghiChu, cthd.maSP, cthd.tenSP, cthd.product_size_id, cthd.mieuTaSP, cthd.hinhAnhSP, cthd.diachi, ps.giaSanPham 
                  FROM tbl_hoadon hd 
                  LEFT JOIN tbl_chitiethoadon cthd ON hd.maHoaDon = cthd.maHoaDon 
                  LEFT JOIN tbl_product_size ps ON cthd.product_size_id = ps.id 
                  WHERE hd.maHoaDon = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_chitiethoadon2($id)
    {
        // Lưu ý: tbl_chitiethoadon không có cột soLuongSP trong SQL dump, cần thêm nếu muốn tính tổng số lượng
        $query = "SELECT cthd.*, ps.giaSanPham, COUNT(cthd.maSP) as soLuongSP 
                  FROM tbl_chitiethoadon cthd 
                  LEFT JOIN tbl_product_size ps ON cthd.product_size_id = ps.id 
                  WHERE cthd.maHoaDon = '$id' 
                  GROUP BY cthd.maSP, cthd.product_size_id";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_hoadonPhanTrang()
    {
        $sanPhamTungTrang = 10;
        $trang = isset($_GET['trang']) ? $_GET['trang'] : 1;
        $tungTrang = ($trang - 1) * $sanPhamTungTrang;

        $query = "SELECT * FROM tbl_hoadon";
        if (isset($_GET['wordSearch']) && !empty($_GET['wordSearch'])) {
            $wordSearch = $this->fm->validation($_GET['wordSearch']);
            $timTheo = isset($_GET['timTheo']) ? $_GET['timTheo'] : '';
            if ($timTheo == "Mã hóa đơn") {
                $query .= " WHERE maHoaDon LIKE '%$wordSearch%'";
            } elseif ($timTheo == "Mã khách hàng") {
                $query .= " WHERE maKhachHang LIKE '%$wordSearch%'";
            } elseif ($timTheo == "Ngày lập hóa đơn") {
                $query .= " WHERE ngayDat LIKE '%$wordSearch%'";
            }
        } elseif (isset($_GET['ngaytruoc']) && !empty($_GET['ngaytruoc']) && isset($_GET['ngaysau']) && !empty($_GET['ngaysau'])) {
            $ngaytruoc = $this->fm->validation($_GET['ngaytruoc']);
            $ngaysau = $this->fm->validation($_GET['ngaysau']);
            $query .= " WHERE ngayDat BETWEEN '$ngaytruoc' AND '$ngaysau'";
        }

        $query .= " ORDER BY maHoaDon DESC LIMIT $tungTrang, $sanPhamTungTrang";
        $result = $this->db->select($query);
        return $result;
    }

    public function getAllHoaDon()
    {
        $query = "SELECT * FROM tbl_hoadon";
        if (isset($_GET['wordSearch']) && !empty($_GET['wordSearch'])) {
            $wordSearch = $this->fm->validation($_GET['wordSearch']);
            $timTheo = isset($_GET['timTheo']) ? $_GET['timTheo'] : '';
            if ($timTheo == "Mã hóa đơn") {
                $query .= " WHERE maHoaDon LIKE '%$wordSearch%'";
            } elseif ($timTheo == "Mã khách hàng") {
                $query .= " WHERE maKhachHang LIKE '%$wordSearch%'";
            } elseif ($timTheo == "Ngày lập hóa đơn") {
                $query .= " WHERE ngayDat LIKE '%$wordSearch%'";
            }
        } elseif (isset($_GET['ngaytruoc']) && !empty($_GET['ngaytruoc']) && isset($_GET['ngaysau']) && !empty($_GET['ngaysau'])) {
            $ngaytruoc = $this->fm->validation($_GET['ngaytruoc']);
            $ngaysau = $this->fm->validation($_GET['ngaysau']);
            $query .= " WHERE ngayDat BETWEEN '$ngaytruoc' AND '$ngaysau'";
        }

        $query .= " ORDER BY maHoaDon DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function sum_hoadon()
    {
        $query = "SELECT SUM(giaTriHD) AS tongHD FROM tbl_hoadon";
        $result = $this->db->select($query);
        return $result;
    }
}
?>