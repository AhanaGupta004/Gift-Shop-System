<?php
session_start();
include 'db.php';

$cart_id = $_POST['cart_id'];
$quantity = $_POST['quantity'];

if ($quantity > 0) {
    mysqli_query($conn, "UPDATE cart SET quantity = $quantity WHERE id = $cart_id");
}

header("Location: cart.php");
exit();
?>
