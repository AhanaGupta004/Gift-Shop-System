<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to add items to your cart.'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'];

// Check if product exists
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
$product = mysqli_fetch_assoc($result);

if ($product) {
    // Check if product is already in the cart
    $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");

    if (mysqli_num_rows($check_cart) > 0) {
        // If product exists, update quantity
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // If not, insert a new cart item
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }

    echo "<script>alert('Item added to cart!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Product not found!'); window.location='index.php';</script>";
}
?>
