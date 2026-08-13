<?php
include 'db.php';

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$query = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .product-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            text-align: center;
        }
        .product-card img {
            max-height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Simple Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Gift Shop</a>
        <form class="form-inline" action="search.php" method="get">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" name="q" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
    </nav>

    <div class="container mt-4">
        <h3>Search Results for: "<?= htmlspecialchars($search) ?>"</h3>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="uploads/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="img-fluid">
                        <h5><?= $row['name'] ?></h5>
                        <p>₹<?= number_format($row['price'], 2) ?></p>
                        <a href="product_detail.php?id=<?= $row['id'] ?>" class="btn btn-primary">View</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <p class="text-muted mt-4">No products found matching your search.</p>
        <?php endif; ?>
    </div>
</body>
</html>
