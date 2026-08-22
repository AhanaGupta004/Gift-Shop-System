<?php
include 'db.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Product ID not specified!";
    exit();
}

$product_id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");

if (mysqli_num_rows($result) == 0) {
    echo "Product not found!";
    exit();
}

$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $product['name'] ?> - Product Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .product-img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<!-- Simple Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Gift Shop</a>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?= $product['image'] ?>" class="product-img" alt="<?= $product['name'] ?>">
        </div>
        <div class="col-md-6">
            <h2><?= $product['name'] ?></h2>
            <h4 class="text-danger">₹<?= number_format($product['price'], 2) ?></h4>
            <p><?= $product['description'] ?></p>

            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" class="form-control w-25 mb-3">
                <button type="submit" class="btn btn-success">Add to Cart</button>
            </form>

            <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>
