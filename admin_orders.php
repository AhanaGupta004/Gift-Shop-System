<?php
session_start();
include 'db.php';

// Allow only admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Success message flag
$success = false;

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];

    // Prepare statement
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        $success = true;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Manage Orders</h2>

    <?php if ($success): ?>
        <div class="alert alert-success text-center">Order status updated successfully!</div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Price (₹)</th>
                <th>Status</th>
                <th>Address</th>
                <th>Placed On</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT orders.*, users.name 
                  FROM orders 
                  JOIN users ON orders.user_id = users.id 
                  ORDER BY orders.created_at DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
                $currentStatus = $row['status'];
        ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td>₹<?= number_format($row['total_price'], 2); ?></td>
                <td>
                    <form method="POST" class="d-flex">
                        <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                        <select name="status" class="form-select me-2">
                            <option value="Pending" <?= $currentStatus === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Delivered" <?= $currentStatus === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="Cancelled" <?= $currentStatus === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
                <td><?= htmlspecialchars($row['address']); ?></td>
                <td><?= $row['created_at']; ?></td>
            </tr>
        <?php
            endwhile;
        else:
        ?>
            <tr>
                <td colspan="6" class="text-center">No orders found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
