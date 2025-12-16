<?php
include_once "includes/db_connect.php";
include "config.php";
$places_query = "SELECT * FROM places ORDER BY p_ID DESC ";
$places_result = $conn->query($places_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>places - Mother Of The World</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/places.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="page-hero">
        <div class="container">
            <h1 class="hero-title">PLACES</h1>
            <p class="hero-subtitle">Discover amazing places to help you find exactly what
                you're looking for</p>
        </div>
    </div>
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
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>