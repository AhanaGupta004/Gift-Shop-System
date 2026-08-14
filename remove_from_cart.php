<?php
session_start();
include 'db.php';

$cart_id = $_GET['id'];

// Remove item from cart
mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id");

header("Location: cart.php");
exit();
?>
