<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your order.'); window.location='login.php';</script>";
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "<script>alert('Invalid order.'); window.location='order_history.php';</script>";
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

// Fetch order details
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id");

if (mysqli_num_rows($order_query) == 0) {
    echo "<script>alert('Order not found.'); window.location='order_history.php';</script>";
    exit();
}

$order = mysqli_fetch_assoc($order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Order Details</h2>
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <strong>Order ID:</strong> <?= $order['id']; ?> |
            <strong>Date:</strong> <?= $order['created_at']; ?>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $items_query = mysqli_query($conn, "SELECT products.name, products.image, order_items.quantity, order_items.price 
                                                        FROM order_items 
                                                        JOIN products ON order_items.product_id = products.id 
                                                        WHERE order_items.order_id = $order_id");

                    $total_price = 0;
                    while ($item = mysqli_fetch_assoc($items_query)): 
                        $subtotal = $item['quantity'] * $item['price'];
                        $total_price += $subtotal;
                    ?>
                    <tr>
                        <td><img src="uploads/<?= $item['image']; ?>" width="50"></td>
                        <td><?= $item['name']; ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td>₹<?= number_format($item['price'], 2); ?></td>
                        <td>₹<?= number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h4 class="text-end text-success">Total: ₹<?= number_format($total_price, 2); ?></h4>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-primary">Back to Shop</a>
    </div>
</div>

</body>
</html>
