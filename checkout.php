<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to proceed to checkout.'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$cart_query = mysqli_query($conn, "SELECT cart.id, products.name, products.image, products.price, cart.quantity 
                                   FROM cart 
                                   JOIN products ON cart.product_id = products.id 
                                   WHERE cart.user_id = $user_id");

$total_price = 0;
$tax_rate = 0.18; // 18% GST

if (mysqli_num_rows($cart_query) == 0) {
    echo "<script>alert('Your cart is empty!'); window.location='index.php';</script>";
    exit();
}

// Fetch previous address
$prev_address = "";
$address_query = mysqli_query($conn, "SELECT address FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1");
if (mysqli_num_rows($address_query) > 0) {
    $prev_address = mysqli_fetch_assoc($address_query)['address'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Checkout</h2>

    <table class="table table-bordered text-center mt-4">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($cart_query)): 
                $subtotal = $row['price'] * $row['quantity'];
                $total_price += $subtotal;
            ?>
            <tr>
                <td><img src="uploads/<?= $row['image']; ?>" width="50"></td>
                <td><?= $row['name']; ?></td>
                <td>₹<?= number_format($row['price'], 2); ?></td>
                <td><?= $row['quantity']; ?></td>
                <td>₹<?= number_format($subtotal, 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php 
    $tax_amount = $total_price * $tax_rate;
    $final_total = $total_price + $tax_amount;
    ?>

    <h4 class="text-end">Subtotal: ₹<?= number_format($total_price, 2); ?></h4>
    <h5 class="text-end text-danger">Tax (18% GST): ₹<?= number_format($tax_amount, 2); ?></h5>
    <h3 class="text-end text-success">Total: ₹<?= number_format($final_total, 2); ?></h3>

    <form action="process_order.php" method="POST">
        <input type="hidden" name="total_price" value="<?= $final_total; ?>">
        
        <div class="mt-4">
            <h5>Shipping Address</h5>
            <textarea name="address" required class="form-control" rows="3" placeholder="Enter your delivery address"><?= htmlspecialchars($prev_address); ?></textarea>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Place Order</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="cart.php" class="btn btn-warning">Back to Cart</a>
    </div>
</div>

</body>
</html>
