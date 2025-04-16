<?php include 'header.php';?>
<?php include_once '../classes/donhang.php';?>
<?php
    $donhang = new donhang();
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="revenue-stats">
            <h1 class="revenue-title">Báo Cáo Doanh Thu</h1>
            
            <!-- Thống kê tổng quan -->
            <div class="revenue-summary">
                <div class="summary-card total">
                    <h3>Tổng Doanh Thu</h3>
                    <div class="value">
                        <?php
                            $totalRevenue = $donhang->getTotalRevenue();
                            echo number_format($totalRevenue) . ' VNĐ';
                        ?>
                    </div>
                </div>
                
                <div class="summary-card today">
                    <h3>Doanh Thu Hôm Nay</h3>
                    <div class="value">
                        <?php
                            $todayRevenue = $donhang->getTodayRevenue();
                            echo number_format($todayRevenue) . ' VNĐ';
                        ?>
                    </div>
                </div>
                
                <div class="summary-card month">
                    <h3>Doanh Thu Tháng Này</h3>
                    <div class="value">
                        <?php
                            $monthRevenue = $donhang->getMonthRevenue();
                            echo number_format($monthRevenue) . ' VNĐ';
                        ?>
                    </div>
                </div>
                
                <div class="summary-card year">
                    <h3>Doannh Thu Năm Nay</h3>
                    <div class="value">
                        <?php
                            $yearRevenue = $donhang->getYearRevenue();
                            echo number_format($yearRevenue) . ' VNĐ';
                        ?>
                    </div>
                </div>
            </div>

            <!-- Bảng doanh thu -->
            <div class="revenue-table">
                <table>
                    <thead>
                        <tr>
                            <th>NGÀY</th>
                            <th>SỐ ĐƠN HÀNG</th>
                            <th>DOANH THU</th>
                            <th>LỢI NHUẬN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $revenueList = $donhang->getRevenueList();
                            if($revenueList) {
                                while($result = $revenueList->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $result['ngayDatHang'] . "</td>";
                                    echo "<td>" . $result['soLuongDon'] . "</td>";
                                    echo "<td>" . number_format($result['doanhThu']) . " VNĐ</td>";
                                    echo "<td>" . number_format($result['loiNhuan']) . " VNĐ</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Tổng doanh thu -->
            <div class="revenue-total">
                Tổng doanh thu: <?php echo number_format($donhang->getTotalRevenue()) . ' VNĐ'; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html> 