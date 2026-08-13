<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Order status updated successfully.";
    } else {
        $_SESSION['msg'] = "Failed to update order status.";
    }
    $stmt->close();
    header("Location: admin_orders.php");
    exit();
} else {
    header("Location: admin_orders.php");
    exit();
}
?>
