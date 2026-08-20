<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
    $role     = 'customer'; // Force role as 'customer'

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered'); window.location='register.php';</script>";
        exit;
    }

    // Insert into users table
    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registered successfully!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error while registering! Please try again.'); window.location='register.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Gift Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Create a Customer Account</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="">

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                        </div>

                        <!-- No role selection for security -->

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>

                    <div class="mt-3 text-center">
                        Already have an account? <a href="login.php">Login here</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
