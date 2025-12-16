<?php
session_start();
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/includes/db_connect.php';

if (!$conn) {
    die("Database connection failed");
}

$place_id = isset($_GET['id']) ? intval($_GET['id']) : 0;



$place = null;
$stmt = $conn->prepare("SELECT p.*, g.G_name as governorate_name 
                        FROM places p 
                        LEFT JOIN governorates g ON p.g_num = g.G_ID 
                        WHERE p.P_ID = ?");
$stmt->bind_param("i", $place_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $place = $result->fetch_assoc();
} else {
    echo "<div class='container pt-5'><h1>Place not found</h1></div>";
    exit();
}

$gallery_images = [];
$img_stmt = $conn->prepare("SELECT image_url FROM place_images WHERE P_num = ?");
$img_stmt->bind_param("i", $place_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();
while ($row = $img_result->fetch_assoc()) {
    $gallery_images[] = $row['image_url'];
}

$main_image_path = '';
if (!empty($place['main_image'])) {
    if (filter_var($place['main_image'], FILTER_VALIDATE_URL)) {
        $main_image_path = $place['main_image'];
    } elseif (file_exists(dirname(__FILE__) . '/assets/uploads/places/' . $place['main_image'])) {
        $main_image_path = BASE_URL . 'assets/uploads/places/' . $place['main_image'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($place['p_name']); ?> - Details</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/place_details.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="details-hero"
        style="background-image: url('<?php echo !empty($main_image_path) ? $main_image_path : 'assets/images/default-place.jpg'; ?>');">
        <div class="container">
            <div class="hero-content">
                <h1 class="place-title"><?php echo htmlspecialchars($place['p_name']); ?></h1>

                <div class="d-flex flex-wrap gap-2">
                    <?php if (!empty($place['governorate_name'])): ?>
                        <span class="info-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($place['governorate_name']); ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($place['opening_hours'])): ?>
                        <span class="info-badge" style="background-color: rgba(0,0,0,0.6);">
                            <i class="fas fa-clock"></i> <?php echo htmlspecialchars($place['opening_hours']); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-8">

                <div class="mb-5">
                    <h3 class="section-header">About this Place</h3>
                    <p class="lead fs-5 text-muted">
                        <?php echo nl2br(htmlspecialchars($place['description'])); ?>
                    </p>
                </div>

                <?php if (!empty($gallery_images)): ?>
                    <div class="mb-5">
                        <h3 class="section-header">Photo Gallery</h3>
                        <div class="gallery-grid">
                            <?php foreach ($gallery_images as $img):
                                $img_path = '';
                                if (filter_var($img, FILTER_VALIDATE_URL)) {
                                    $img_path = $img;
                                } elseif (file_exists(dirname(__FILE__) . '/assets/uploads/places/' . $img)) {
                                    $img_path = BASE_URL . 'assets/uploads/places/' . $img;
                                }

                                if ($img_path):
                                    ?>
                                    <div class="gallery-item">
                                        <img src="<?php echo $img_path; ?>" alt="Gallery Image">
                                    </div>
                                <?php endif; endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <iframe src="<?php echo htmlspecialchars($place['location_url']); ?>" width="860" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" height="400" class="rounded"></iframe>
            </div>
            <div class="col-lg-4">

                <div class="info-card sticky-top" style="top: 100px;">
                    <?php if ($place['ticket_price'] > 0): ?>
                        <div class="text-center mb-4">
                            <div class="price-tag">
                                <?php echo number_format($place['ticket_price'], 2); ?> EGP
                                <div style="font-size: 0.8rem; font-weight: normal; opacity: 0.9;">Per Person</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-details">
                        <h5 class="mb-3 text-uppercase" style="color: var(--logo-dark); letter-spacing: 1px;">
                            Information</h5>

                        <?php if (!empty($place['opening_hours'])): ?>
                            <div class="info-row">
                                <div class="info-icon"><i class="far fa-clock"></i></div>
                                <div>
                                    <strong>Opening Hours:</strong><br>
                                    <?php echo htmlspecialchars($place['opening_hours']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($place['location_url'])): ?>
                            <div class="mt-4">
                                <a href="<?php echo htmlspecialchars($place['location_url']); ?>" target="_blank"
                                    class="btn btn-primary w-100"
                                    style="background-color: var(--action-button); border: none; padding: 12px;">
                                    <i class="fas fa-location-arrow me-2"></i> Get Directions
                                </a>

                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>

    </div>

        <?php include 'includes/footer.php'; ?>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php
if (isset($conn)) {
    $conn->close();
}
?>