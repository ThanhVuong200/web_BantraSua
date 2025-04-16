<?php
header('Content-Type: application/json');
include_once '../../lib/database.php';
include_once '../../helpers/format.php';

$db = new Database();
$fm = new Format();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get and sanitize input
$month = isset($_POST['month']) ? $fm->validation($_POST['month']) : '';
$year = isset($_POST['year']) ? $fm->validation($_POST['year']) : '';
$startDate = isset($_POST['startDate']) ? $fm->validation($_POST['startDate']) : '';
$endDate = isset($_POST['endDate']) ? $fm->validation($_POST['endDate']) : '';

$query = "SELECT 
            DATE(ngayLapDH) as ngayDatHang,
            COUNT(*) as soLuongDon,
            SUM(tongTienDH) as doanhThu,
            SUM(tongTienDH * 0.2) as loiNhuan
          FROM tbl_donhang 
          WHERE trangThai = 2";

if (!empty($month)) {
    $query .= " AND MONTH(ngayLapDH) = " . $db->link->real_escape_string($month);
}

if (!empty($year)) {
    $query .= " AND YEAR(ngayLapDH) = " . $db->link->real_escape_string($year);
}

if (!empty($startDate)) {
    $query .= " AND DATE(ngayLapDH) >= '" . $db->link->real_escape_string($startDate) . "'";
}

if (!empty($endDate)) {
    $query .= " AND DATE(ngayLapDH) <= '" . $db->link->real_escape_string($endDate) . "'";
}

$query .= " GROUP BY DATE(ngayLapDH) ORDER BY ngayLapDH DESC";

// Log the query for debugging
error_log("SQL Query: " . $query);

$result = $db->select($query);
$data = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'ngayDatHang' => $row['ngayDatHang'],
            'soLuongDon' => $row['soLuongDon'],
            'doanhThu' => $row['doanhThu'],
            'loiNhuan' => $row['loiNhuan']
        );
    }
}

echo json_encode($data); 