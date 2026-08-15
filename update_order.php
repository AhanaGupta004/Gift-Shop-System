<?php
session_start();
include 'db.php';

// Ensure only admin can update
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Access denied!'); window.location='index.php';</script>";
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = intval($_GET['id']);
    $status = $_GET['status'];

    if ($status == 'approved' || $status == 'cancelled') {
        mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = $order_id");
        echo "<script>alert('Order status updated!'); window.location='admin_orders.php';</script>";
    } else {
        echo "<script>alert('Invalid status!'); window.location='admin_orders.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location='admin_orders.php';</script>";
}
?>
