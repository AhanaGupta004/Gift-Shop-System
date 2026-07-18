<?php
session_start();
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Get product details
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        echo "<div class='alert alert-danger'>Product not found.</div>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $new_image = $_FILES['image']['name'];

    // If a new image is uploaded, update it
    if (!empty($new_image)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($new_image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_to_save = $new_image;
    } else {
        $image_to_save = $product['image']; // Keep the old image
    }

    $query = "UPDATE products SET name='$name', price='$price', image='$image_to_save' WHERE id=$product_id";
    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>Product updated successfully! <a href='index.php'>View Products</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Gift Shop</a>
        <a href="index.php" class="btn btn-light">Back to Shop</a>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Edit Product</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" value="<?= $product['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" name="price" class="form-control" value="<?= $product['price']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img src="uploads/<?= $product['image']; ?>" width="100">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload New Image (Optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
