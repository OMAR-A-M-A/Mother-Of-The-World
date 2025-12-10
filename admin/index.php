<?php
session_start();
include '../includes/db_connect.php'; // Include database connection

// Check if user is already logged in -> Redirect to Dashboard directly
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

// Handle Login Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent basic SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 1. Check if username exists
    $sql = "SELECT * FROM admins WHERE user_name = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // 2. Verify Password
        if ($password == $row['password']) { 
            // 3. Set Session Variables
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['user_name'];
            
            // 4. Redirect to Dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect Password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Admin Panel 🔒</h3>
            <p class="text-muted">Please sign in to continue</p>
        </div>

        <?php if($error != ""): ?>
            <div class="alert alert-danger p-2 text-center text-small">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label text-secondary" for="username">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" id="username" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-secondary" for="pass">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required id="pass">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
        </form>
        
        <div class="text-center mt-4">
            <a href="../index.php" class="text-decoration-none text-muted small">Back to Website</a>
        </div>
    </div>
    <!-- bootstrap js -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>