<?php
// define the name of the current page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<head>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/sidebar.css">
</head>

<div class="sidebar p-4 d-flex flex-column">

    <a href="<?php echo BASE_URL; ?>admin/dashboard.php"
        class="d-flex align-items-center mb-3 mb-md-0 text-white text-decoration-none px-2">
        <img src="<?php echo BASE_URL; ?>assets/images/motw-logo.png" alt="logo" width="40" class="m-2">
        <span class="fs-4 fw-bold m-1">M-Dashboard</span>
    </a>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>admin/dashboard.php"
                class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>admin/governorats/manage_gov.php"
                class="nav-link <?php echo ($current_page == 'manage_gov.php' || $current_page == 'edit_gov.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-city me-2"></i> Governorates
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>admin/places/manage_places.php"
                class="nav-link <?php echo ($current_page == 'manage_places.php' || $current_page == 'edit_place.php' || $current_page == 'add_place.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-images me-2"></i> Places
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>admin/category/manage_category.php"
                class="nav-link <?php echo ($current_page == 'manage_category.php' || $current_page == 'edit_category.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-tag me-2"></i> Categories
            </a>
        </li>

    </ul>
</div>