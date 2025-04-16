<?php
    include 'header.php';
    include_once '../classes/category.php';
    include_once '../classes/donhang.php';
    $cat = new category();
    $donhang = new donhang();
?>
<link rel="stylesheet" href="../css/admin-style.css">
<div id="page-wrapper">
    <div class="container-fluid">
        <h1 class="text-center mb-4">Bảng thống kê</h1>
        
        <div class="row stats-container">
            <div class="col-md-3">
                <div class="stat-box product-box">
                    <h2>Sản phẩm</h2>
                    <div class="stat-number"><?php echo $prod->count_product(); ?></div>
                    <a href="product.php" class="stat-link">xem chi tiết →</a>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-box customer-box">
                    <h2>Khách hàng</h2>
                    <div class="stat-number"><?php echo $customer->count_customer(); ?></div>
                    <a href="customerlist.php" class="stat-link">xem chi tiết →</a>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-box category-box">
                    <h2>Danh mục</h2>
                    <div class="stat-number"><?php echo $cat->count_category(); ?></div>
                    <a href="category.php" class="stat-link">xem chi tiết →</a>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-box order-box">
                    <h2>Đơn hàng</h2>
                    <div class="stat-number"><?php echo $donhang->count_donhang(); ?></div>
                    <a href="orders.php" class="stat-link">xem chi tiết →</a>
                </div>
            </div>
        </div>

        <!-- Bảng báo cáo doanh thu -->
        <div class="revenue-report">
            <h2>Báo cáo doanh thu</h2>
            <div class="revenue-summary">
                <div class="summary-item">
                    <h4>Doanh Thu Hôm Nay</h4>
                    <div class="value">
                        <?php
                            $todayRevenue = $donhang->getTodayRevenue();
                            echo number_format($todayRevenue) . ' VNĐ';
                        ?>
                    </div>
                </div>
                
                <div class="summary-item">
                    <h4>Doanh Thu Tháng Này</h4>
                    <div class="value">
                        <?php
                            $monthRevenue = $donhang->getMonthRevenue();
                            echo number_format($monthRevenue) . ' VNĐ';
                        ?>
                    </div>
                </div>
                
                <div class="summary-item">
                    <h4>Doanh Thu Năm Nay</h4>
                    <div class="value">
                        <?php
                            $yearRevenue = $donhang->getYearRevenue();
                            echo number_format($yearRevenue) . ' VNĐ';
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="view-more">
                <a href="revenue.php" class="btn btn-primary">Xem chi tiết báo cáo →</a>
            </div>
        </div>
    </div>
</div>

<style>
.stats-container {
    padding: 20px;
    display: flex;
    justify-content: center;
    gap: 20px;
}

.stat-box {
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    color: white;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 200px;
    transition: transform 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-5px);
}

.product-box {
    background-color: #4e73df;
}

.customer-box {
    background-color: #8e44ad;
}

.category-box {
    background-color: #27ae60;
}

.order-box {
    background-color: #c0392b;
}

.stat-box h2 {
    font-size: 24px;
    margin: 0;
    font-weight: bold;
}

.stat-number {
    font-size: 48px;
    font-weight: bold;
    margin: 20px 0;
}

.stat-link {
    color: white;
    text-decoration: none;
    font-size: 16px;
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.stat-link:hover {
    opacity: 1;
    color: white;
    text-decoration: none;
}

h1.text-center {
    color: #333;
    margin-bottom: 40px;
    font-weight: bold;
}

/* Báo cáo doanh thu */
.revenue-report {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-top: 30px;
}

.revenue-report h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}

.revenue-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.summary-item:nth-child(1) {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #333;
}

.summary-item:nth-child(2) {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #333;
}

.summary-item:nth-child(3) {
    background: linear-gradient(135deg, #fff3e0, #ffe0b2);
    color: #333;
}

.summary-item:nth-child(4) {
    background: linear-gradient(135deg, #fce4ec, #f8bbd0);
    color: #333;
}

.summary-item {
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.summary-item h4 {
    font-size: 16px;
    margin: 0 0 10px 0;
    color: #666;
}

.summary-item .value {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.view-more {
    text-align: center;
    margin-top: 20px;
}

.view-more .btn {
    display: inline-block;
    padding: 10px 20px;
    background: #6c5ce7;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
}

.view-more .btn:hover {
    background: #5849c2;
}

@media (max-width: 768px) {
    .stats-container {
        flex-direction: column;
    }
    
    .stat-box {
        margin-bottom: 20px;
    }
    
    .revenue-summary {
        grid-template-columns: 1fr;
    }
}
</style>

</body>
</html>