<?php
session_start();
include 'db.php';

// Check if admin is logged in (optional check, if you use session roles)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location='admin_login.php';</script>";
    exit();
}

// Fetch customers
$customers_query = mysqli_query($conn, "SELECT id, name, email FROM users WHERE role = 'customer'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Customer List</h2>

    <?php if (mysqli_num_rows($customers_query) > 0): ?>
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <!-- Uncomment these if you add phone/address fields later -->
                    <!-- <th>Phone</th>
                    <th>Address</th> -->
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; while ($customer = mysqli_fetch_assoc($customers_query)): ?>
                    <tr>
                        <td><?= $sn++; ?></td>
                        <td><?= htmlspecialchars($customer['name']); ?></td>
                        <td><?= htmlspecialchars($customer['email']); ?></td>
                        <!--
                        <td><?= isset($customer['phone']) ? htmlspecialchars($customer['phone']) : 'N/A'; ?></td>
                        <td><?= isset($customer['address']) ? htmlspecialchars($customer['address']) : 'N/A'; ?></td>
                        -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h5 class="text-danger text-center">No customers found.</h5>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
