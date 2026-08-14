<?php
session_start();
include 'db.php';

// Optional: Restrict access to admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location='admin_login.php';</script>";
    exit();
}

// Total orders
$total_orders_result = mysqli_query($conn, "SELECT COUNT(*) as total_orders FROM orders");
$total_orders = mysqli_fetch_assoc($total_orders_result)['total_orders'];

// Monthly sales
$monthly_sales_query = mysqli_query($conn, "
    SELECT DATE_FORMAT(created_at, '%M %Y') as month, SUM(total_price) as total
    FROM orders
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY created_at DESC
");

// Best-selling products
$top_products_query = mysqli_query($conn, "
    SELECT p.name, SUM(oi.quantity) as total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id
    ORDER BY total_sold DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">📈 Sales Report</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h3 class="text-success"><?= $total_orders; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5">📊 Monthly Sales</h4>
    <table class="table table-bordered text-center mt-2">
        <thead class="table-dark">
            <tr>
                <th>Month</th>
                <th>Total Sales (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($monthly_sales_query)): ?>
                <tr>
                    <td><?= $row['month']; ?></td>
                    <td>₹<?= number_format($row['total'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h4 class="mt-5">🔥 Top 5 Best-Selling Products</h4>
    <table class="table table-bordered text-center mt-2">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Units Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = mysqli_fetch_assoc($top_products_query)): ?>
                <tr>
                    <td><?= $product['name']; ?></td>
                    <td><?= $product['total_sold']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
