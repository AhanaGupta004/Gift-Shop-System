<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to place an order.'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = $_POST['total_price'];
$address = mysqli_real_escape_string($conn, $_POST['address']);

// Get last used address if none is provided
$address_query = mysqli_query($conn, "SELECT address FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1");
if (mysqli_num_rows($address_query) > 0) {
    $prev_address = mysqli_fetch_assoc($address_query)['address'];
    if (empty($address)) {
        $address = $prev_address;
    }
}

// Insert new order
$order_query = "INSERT INTO orders (user_id, total_price, address, status) VALUES ('$user_id', '$total_price', '$address', 'pending')";
if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn);

    // Fetch all cart items
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
    while ($cart_item = mysqli_fetch_assoc($cart_query)) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];

        // ✅ Fetch latest product price from `products` table
        $product_result = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");
        $product_data = mysqli_fetch_assoc($product_result);
        $price = $product_data['price'];

        // Insert into order_items
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                             VALUES ('$order_id', '$product_id', '$quantity', '$price')");
    }

    // Clear the cart after placing the order
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

    echo "<script>alert('Order placed successfully!'); window.location='order_confirmation.php';</script>";
} else {
    echo "<script>alert('Error placing order. Please try again.'); window.location='checkout.php';</script>";
}
?>
