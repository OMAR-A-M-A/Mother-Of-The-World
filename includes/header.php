<?php
// define the name of the current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="logo-icon me-2">
                <img src="assets/images/motw-logo.png" alt="" width="40">
            </div>
            <div class="logo-text">
                <span class="text-travel">MOTHER</span><span class="text-tour">WORLD</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'category.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>category.php">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'places.php') || ($current_page == 'place_details.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>places.php">Tour Places</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'gov.php') || ($current_page == 'gov-details.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>gov.php">Governorates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php#about-us')  ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>#about-us">Blog</a>
                </li>

            </ul>
        </div>
    </div>
</nav>
