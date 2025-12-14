<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$display_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
?>

<head>
    <style>
        .welcome-div {
            /* border: 1px solid #000; */
            padding: 8px;
            border-radius: 5px;
            background-color: #1a1c23;
            color:#ffa00a;
        }
    </style>
</head>
<nav class="navbar navbar-light bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center welcome-div">
        <h5 class="mb-0 text-light me-2">
            Welcome,
        </h5>
        <span class="text-capitalize fs-5" >
            <?php echo $display_name; ?>
        </span>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>admin/logout.php" class="btn btn-outline-danger d-flex align-items-center gap-2">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Log out</span>
        </a>
    </div>
</nav>