<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to access your cart.'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items from the database
$cart_query = mysqli_query($conn, "SELECT cart.id, products.name, products.image, products.price, cart.quantity 
                                   FROM cart 
                                   JOIN products ON cart.product_id = products.id 
                                   WHERE cart.user_id = $user_id");

$total_price = 0;
$tax_rate = 0.18; // 18% GST
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center">Your Shopping Cart</h2>

    <?php if (mysqli_num_rows($cart_query) > 0): ?>
        <table class="table table-bordered text-center mt-4">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
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
                    <td>
                        <form action="update_cart.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?= $row['id']; ?>">
                            <input type="number" name="quantity" value="<?= $row['quantity']; ?>" min="1" class="form-control w-50 d-inline">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                    <td>₹<?= number_format($subtotal, 2); ?></td>
                    <td><a href="remove_from_cart.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Remove</a></td>
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

        <div class="text-end">
            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        </div>

    <?php else: ?>
        <h4 class="text-center text-danger">Your cart is empty!</h4>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
