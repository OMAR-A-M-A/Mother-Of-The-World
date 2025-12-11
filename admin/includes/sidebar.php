<head>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/sidebar.css">
</head>
<div class="sidebar p-4 d-flex flex-column">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2">
        <i class="fa-solid fa-fire text-primary me-2 fs-4"></i>
        <span class="fs-4 fw-bold">V-Dashboard</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="nav-link "><i class="fa-solid fa-chart-pie me-2"></i> Dashboard</a>
        </li>
        <li><a href="" class="nav-link"><i class="fa-solid fa-users me-2"></i> Admins</a></li>
        <li><a href="<?php echo BASE_URL; ?>admin/governorats/manage_gov.php" class="nav-link"><i class="fa-solid fa-city me-2"></i> Governorates</a></li>
        <li><a href="" class="nav-link"><i class="fa-solid fa-tag me-2"></i> Categories</a></li>
        <li><a href="" class="nav-link"><i class="fa-solid fa-images me-2"></i> Place Images</a></li>
    </ul>
</div>

