<?php
session_start();
include 'db.php';

// Make sure only logged-in admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied!'); window.location='login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = 'admin'; // Hardcoded role

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered'); window.location='create_admin.php';</script>";
        exit;
    }

    // Insert new admin
    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Admin created successfully!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error creating admin.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Create New Admin</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Create Admin</button>
    </form>
</div>
</body>
</html>
