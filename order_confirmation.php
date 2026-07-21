<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 text-center">
    <h2 class="text-success">Order Placed Successfully! 🎉</h2>
    <p class="mt-3">Thank you for shopping with us. Your order has been placed successfully.</p>
    
    <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
    <a href="order_details.php" class="btn btn-secondary mt-3">View Orders</a>
</div>

</body>
</html>
