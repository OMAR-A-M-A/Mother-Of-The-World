<?php
include "config.php";
include "includes/db_connect.php";

$query = "SELECT * FROM governorates ORDER BY G_ID DESC LIMIT 3";
$result = $conn->query($query);

$places_query = "SELECT * FROM places ORDER BY p_ID DESC LIMIT 3";
$places_result = $conn->query($places_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <title>Mother Of The World</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <!------------ hero section ------------>
        <section class="hero-section">
            <div class="container hero-content">
                <h1 class="hero-title">Find Next Place To Visit</h1>
                <p class="hero-subtitle">Discover amazing places at exclusive deals</p>
            </div>
        </section>
        <!------------ info section ------------>
        <section class="info-bar-container">
            <div class="info-bar">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fa-solid fa-earth-americas"></i>
                            </div>
                            <div class="feature-text">
                                <h5>700+ govinations</h5>
                                <p>Our expert team handpicked all govinations in this site</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Best Price Guarantee</h5>
                                <p>Price match within 48 hours of order confirmation</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Top Notch Support</h5>
                                <p>We are here to help, before, during, and even after your trip.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- popular governorates -->
        <section class="popular-gov py-5">
            <div class="container">
                <!-- change it with fatime code -->
                <div class="section-header text-center mb-5">
                    <h2 class="section-title">Popular governorates</h2>
                    <a href="gov.php" class="view-all-link">
                        View All governorates <i class="fa-solid fa-arrow-right-long ms-2"></i>
                    </a>
                </div>
                <div class="row g-4">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="gov-card">

                                <span class="tour-badge">New</span>

                                <div class="image-wrapper">
                                    <img src="assets/uploads/governorates/<?php echo $row['image_url']; ?>"
                                        alt="<?php echo $row['G_name']; ?>">
                                </div>

                                <div class="overlay"></div>

                                <div class="card-content">
                                    <h3 class="gov-name"><?php echo $row['G_name']; ?></h3>
                                    <a href="gov-details.php?id=<?php echo $row['G_ID']; ?>" class="gov-link">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- highlight section -->
        <section class="video-highlight-section">
            <div class="video-overlay"></div>
            <div class="container position-relative z-2 text-center">
                <h2 class="highlight-title">Traveling Highlights</h2>

                <p class="highlight-subtitle">Your New Traveling Idea</p>

                <a href="https://www.youtube.com/watch?v=D0UnqGm_miA" target="_blank" class="play-btn-wrapper">
                    <i class="fa-solid fa-play"></i>
                </a>
            </div>
        </section>
        <!-- popular places -->
        <section class="latest-places-section py-5">
            <div class="container">

                <div class="section-header text-center mb-5">
                    <h2 class="section-title">Latest Places to Visit</h2>
                    <p class="text-muted">Discover the newest attractions added to our list</p>
                </div>

                <div class="row g-4">
                    <?php while ($place = mysqli_fetch_assoc($places_result)) { ?>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="place-card-style">

                                <div class="place-img-top">
                                    <img src="assets/uploads/places/<?php echo $place['main_image']; ?>"
                                        alt="<?php echo $place['p_name']; ?>">
                                </div>

                                <div class="place-body">
                                    <h3 class="place-title"><?php echo $place['p_name']; ?></h3>

                                    <p class="place-desc">
                                        <?php
                                        echo substr($place['description'], 0, 100) . '...';
                                        ?>
                                    </p>

                                    <a href="place_details.php?id=<?php echo $place['P_ID']; ?>" class="btn-teal-custom">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- start category section -->
        <section class="cat container">
            <h2 class="spacial-heading">Categories</h2>
            <p>Popular Categories</p>
            <div class="text-center mb-4">
                <a href="#" class="main_link">View All Categories
                    <i class="gdlr-core-pos-right fa fa-long-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
            <div class="cards row">
                <?php if (!empty($categories)) { ?>
                    <?php foreach ($categories as $category) { ?>
                        <div class="card text-white col-md-4 p-0 overflow-hidden shadow-sm position-relative my_card">
                            <img class="card-img h-100 w-100 object-cover"
                                src="<?php echo BASE_URL; ?>assets/uploads/categories/<?php echo $category['C_image']; ?>">
                            <div
                                class="card-img-overlay d-flex flex-column justify-content-start align-items-center p-5 content">
                                <h5>
                                    <?php echo htmlspecialchars($category['C_name']); ?>
                                </h5>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($category['C_description']); ?>
                                </p>
                                <a href="category.php?category_id=<?php echo $category['C_ID']; ?>">VIEW ALL TOURS</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-center">No categories found.</p>
                <?php } ?>
            </div>
        </section>
        <!-- end category section -->
        <!-- start newsletter section -->
        <section class="newsletter container">
            <div class="d-flex justify-content-center">
                <div
                    class="content d-flex flex-column justify-content-space-between align-items-center text-center w-50 p-5">
                    <p>Enjoy Summer Deals</p>
                    <h3>Up to 40% Discount!</h3>
                    <a href="#" class="butn">LEARN MORE</a>
                    <a href="<?php echo BASE_URL; ?>admin/dashboard.php"
                        class="d-flex align-items-center mb-3 mb-md-0 text-white text-decoration-none px-2">
                        <img src="<?php echo BASE_URL; ?>assets/images/motw-logo.png" alt="logo" width="35" class="m-2">
                        <span class="fs-5 m-1">M-Dashboard</span>
                    </a>
                    <span>Terms applied</span>
                </div>
                <img src="assets/images/login.jpg" class="w-50 object-cover" alt="newsletter image">
            </div>
        </section>
        <!-- end newsletter section -->
        <!-- start testimonial section -->
        <section class="testimonial-section py-5">
            <h2 class="spacial-heading">Testimonial</h2>
            <p>Customer Reviews</p>
            <div class="container cards">
                <div class="row justify-content-center">

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="testimonial-card">
                            <div class="quote-icon fa fa-quote-left"></div>
                            <p class="testimonial-text">
                                Donec ullamcorper nulla non metus auctor fringilla. Sed posuere consectetur est at
                                lobortis.
                                Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna.
                            </p>
                            <div class="d-flex align-items-center mt-4">
                                <img src="assets/images/avatar-03.png" class="profile-img rounded-circle me-3"
                                    alt="Luaka Smith">
                                <div>
                                    <p class="name mb-0">LUAKA SMITH</p>
                                    <div class="stars mb-1">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <p class="role mb-0">Solo Traveler</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="testimonial-card">
                            <div class="quote-icon fa fa-quote-left"></div>
                            <p class="testimonial-text">
                                Donec ullamcorper nulla non metus auctor fringilla. Sed posuere consectetur est at
                                lobortis.
                                Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna.
                            </p>
                            <div class="d-flex align-items-center mt-4">
                                <img src="assets/images/avatar-04.png" class="profile-img rounded-circle me-3"
                                    alt="Jane Doe">
                                <div>
                                    <p class="name mb-0">JANE DOE</p>
                                    <div class="stars mb-1">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <p class="role mb-0">Solo Traveler</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="testimonial-card">
                            <div class="quote-icon fa fa-quote-left"></div>
                            <p class="testimonial-text">
                                Donec ullamcorper nulla non metus auctor fringilla. Sed posuere consectetur est at
                                lobortis.
                                Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna.
                            </p>
                            <div class="d-flex align-items-center mt-4">
                                <img src="assets/images/avatar-05.png" class="profile-img rounded-circle me-3"
                                    alt="John Smith">
                                <div>
                                    <p class="name mb-0">JOHN SMITH</p>
                                    <div class="stars mb-1">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <p class="role mb-0">Solo Traveler</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-center mt-3">
                    <span class="pagination-dot active-dot me-2"></span>
                    <span class="pagination-dot me-2"></span>
                    <span class="pagination-dot"></span>
                </div>
            </div>
        </section>
        <!-- end testimonial section -->
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>