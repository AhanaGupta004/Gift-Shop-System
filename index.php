<?php
session_start();
include 'db.php';

// Check login & admin status
$loggedIn = isset($_SESSION['user_id']);
$isAdmin = ($loggedIn && isset($_SESSION['role']) && $_SESSION['role'] == 'admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gift Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Gift Shop</a>
        <form class="d-flex me-auto ms-3" action="search.php" method="GET">
            <input class="form-control me-2" type="search" name="q" placeholder="Search products" aria-label="Search">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
        <div class="d-flex">
            <?php if ($loggedIn): ?>
                <?php if (!$isAdmin): ?>
                    <a href="cart.php" class="btn btn-warning me-2">View Cart</a>
                    <a href="order_history.php" class="btn btn-secondary me-2">My Orders</a>
                    <a href="profile.php" class="btn btn-info me-2">Profile</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary me-2">Login</a>
                <a href="register.php" class="btn btn-success">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <!-- Hero Product Section -->
    <?php
    $heroQuery = mysqli_query($conn, "
        SELECT product_id, SUM(quantity) as total_sold
        FROM order_items
        GROUP BY product_id
        ORDER BY total_sold DESC
        LIMIT 1
    ");

    if (mysqli_num_rows($heroQuery) > 0) {
        $heroData = mysqli_fetch_assoc($heroQuery);
        $product_id = $heroData['product_id'];
        $productQuery = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
        $product = mysqli_fetch_assoc($productQuery);
    ?>
        <div class="alert alert-info text-center">
            <h4>🔥 Most Popular Product</h4>
            <h5><?= $product['name']; ?> - ₹<?= number_format($product['price'], 2); ?></h5>
            <img src="uploads/<?= $product['image']; ?>" alt="<?= $product['name']; ?>" style="height: 200px; object-fit: cover;" class="rounded">
        </div>
    <?php } ?>

    <h2 class="text-center">Welcome to the Gift Shop</h2>

    <!-- Admin Buttons -->
    <?php if ($isAdmin): ?>
        <div class="text-center mb-4">
            <a href="add_product.php" class="btn btn-primary">Add Product</a>
            <a href="admin_orders.php" class="btn btn-warning">Manage Orders</a>
            <a href="admin_customers.php" class="btn btn-info">Customer Info</a>
            <a href="sales_report.php" class="btn btn-success">Sales Report</a>
            <a href="add_admin.php" class="btn btn-danger">Add New Admin</a>
        </div>
    <?php endif; ?>

    <!-- Product Search/Filter Form -->
    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-5">
            <input type="text" class="form-control" name="search" placeholder="Search by product name" value="<?= $_GET['search'] ?? '' ?>">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="min" placeholder="Min Price" value="<?= $_GET['min'] ?? '' ?>">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="max" placeholder="Max Price" value="<?= $_GET['max'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
        </div>
    </form>

    <!-- All Products -->
    <div class="row">
        <?php
        $where = "1";
        if (!empty($_GET['search'])) {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            $where .= " AND name LIKE '%$search%'";
        }
        if (!empty($_GET['min'])) {
            $min = (int)$_GET['min'];
            $where .= " AND price >= $min";
        }
        if (!empty($_GET['max'])) {
            $max = (int)$_GET['max'];
            $where .= " AND price <= $max";
        }

        $result = mysqli_query($conn, "SELECT * FROM products WHERE $where");
        while ($row = mysqli_fetch_assoc($result)):
        ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="uploads/<?= $row['image']; ?>" class="card-img-top" alt="<?= $row['name']; ?>" style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $row['name']; ?></h5>
                        <p class="card-text">₹<?= number_format($row['price'], 2); ?></p>
                        <?php if (!$isAdmin): ?>
                            <a href="add_to_cart.php?id=<?= $row['id']; ?>" class="btn btn-success">Add to Cart</a>
                        <?php else: ?>
                            <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn btn-danger">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
