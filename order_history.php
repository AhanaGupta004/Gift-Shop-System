<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your order history.'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all orders by the user
$orders_query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Order History</h2>

    <?php if (mysqli_num_rows($orders_query) > 0): ?>
        <?php while ($order = mysqli_fetch_assoc($orders_query)): ?>
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <strong>Order ID:</strong> <?= $order['id']; ?> |
                    <strong>Date:</strong> <?= $order['created_at']; ?> |
                    <strong>Status:</strong> <?= ucfirst($order['status']); ?>
                </div>
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price (₹)</th>
                            <th>Subtotal (₹)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $order_id = $order['id'];
                        $items_query = mysqli_query($conn, "
                            SELECT products.name, products.image, order_items.quantity, order_items.price
                            FROM order_items
                            JOIN products ON order_items.product_id = products.id
                            WHERE order_items.order_id = $order_id
                        ");

                        $total_price = 0;
                        while ($item = mysqli_fetch_assoc($items_query)):
                            $subtotal = $item['quantity'] * $item['price'];
                            $total_price += $subtotal;
                            ?>
                            <tr>
                                <td><img src="uploads/<?= $item['image']; ?>" width="60"></td>
                                <td><?= $item['name']; ?></td>
                                <td><?= $item['quantity']; ?></td>
                                <td><?= number_format($item['price'], 2); ?></td>
                                <td><?= number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>

                    <h5 class="text-end">Total Amount: ₹<?= number_format($total_price, 2); ?></h5>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <h4 class="text-center text-danger">No previous orders found!</h4>
    <?php endif; ?>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-primary">Back to Shop</a>
    </div>
</div>
</body>
</html>
