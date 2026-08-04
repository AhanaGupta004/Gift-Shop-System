<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="text-center mb-4">Admin Dashboard</h2>

    <div class="row">
        <!-- Total Customers -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title">Total Customers</h4>
                    <?php
                    $customerCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'"));
                    echo '<h2>' . $customerCount['total'] . '</h2>';
                    ?>
                </div>
            </div>
        </div>

        <!-- Total Sales This Month -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title">Sales This Month</h4>
                    <?php
                    $currentMonth = date('Y-m');
                    $monthlySales = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) AS total FROM orders WHERE DATE_FORMAT(created_at, '%Y-%m') = '$currentMonth'"));
                    echo '<h2>₹' . number_format($monthlySales['total'], 2) . '</h2>';
                    ?>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title">Total Orders</h4>
                    <?php
                    $totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"));
                    echo '<h2>' . $totalOrders['total'] . '</h2>';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="admin_orders.php" class="btn btn-outline-dark">Manage Orders</a>
        <a href="admin_customers.php" class="btn btn-outline-dark">View Customers</a>
        <a href="add_product.php" class="btn btn-outline-dark">Add Product</a>
    </div>
</div>

</body>
</html>
