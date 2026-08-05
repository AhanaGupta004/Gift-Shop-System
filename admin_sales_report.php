<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">📊 Sales Report</h2>

    <!-- Total Sales by Product -->
    <h4 class="mb-3">🔹 Total Sales by Product</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Sales (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                SELECT p.name, 
                       SUM(oi.quantity) as total_quantity, 
                       SUM(oi.quantity * oi.price) as total_sales
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                GROUP BY oi.product_id
                ORDER BY total_sales DESC
            ";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)):
            ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['total_quantity'] ?></td>
                    <td>₹<?= number_format($row['total_sales'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Monthly Sales Summary -->
    <h4 class="mt-5 mb-3">📅 Total Sales by Month</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Month</th>
                <th>Total Orders</th>
                <th>Total Sales (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                SELECT DATE_FORMAT(created_at, '%Y-%m') as order_month, 
                       COUNT(*) as total_orders, 
                       SUM(total_price) as monthly_sales
                FROM orders
                GROUP BY order_month
                ORDER BY order_month DESC
            ";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)):
            ?>
                <tr>
                    <td><?= $row['order_month'] ?></td>
                    <td><?= $row['total_orders'] ?></td>
                    <td>₹<?= number_format($row['monthly_sales'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
