<?php
session_start();
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/includes/db_connect.php';

if (!$conn) {
    die("Database connection failed");
}

$selected_gov_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$selected_gov_name = '';
$selected_gov_image = '';

if ($selected_gov_id > 0) {
    $stmt = $conn->prepare("SELECT G_name, image_url, G_description FROM governorates WHERE G_ID = ?");
    $stmt->bind_param("i", $selected_gov_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $selected_gov_name = $row['G_name'];
        $selected_gov_image = $row['image_url'];
        $selected_gov_description = $row['G_description'];
    }
    $stmt->close();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $selected_gov_name ? htmlspecialchars($selected_gov_name) . " - Mother of the World" : "Govrnorate - Mother of the World"; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/gov-details.css">
</head>
<body>
    <?php
    $gov_bg_image = '';
    if (!empty($selected_gov_image)) {
        if (file_exists(dirname(__FILE__) . '/assets/uploads/governorates/' . $selected_gov_image)) {
            $gov_bg_image = BASE_URL . 'assets/uploads/governorates/' . $selected_gov_image;
        } else if (filter_var($selected_gov_image, FILTER_VALIDATE_URL)) {
            $gov_bg_image = $selected_gov_image;
        }
    }
    ?>
    
    <div class="category-hero" style="<?php echo !empty($gov_bg_image) ? "background-image: url('$gov_bg_image');" : "background: linear-gradient(135deg, #4a5568, #718096);"; ?>">
        <div class="container">
            <h1 class="category-title"><?php echo htmlspecialchars($selected_gov_name); ?></h1>
            
            <a href="gov.php" class="back-button">
                <i class="fas fa-arrow-left me-2"></i>Back to All Governorate
            </a>
        </div>
    </div>
    
    <div class="container">
        <section>
            <div class="row mb-3">
                <div class="col-12">
                    <h2 class="section-title-description section-title">Description</h2>
                    <p class = "text-muted fs-5"><?php echo htmlspecialchars($selected_gov_description); ?></p> 
                </div>
            </div>
        </section>

        <section>
            <div class="row mb-3">
                <div class="col-12">
                    <h2 class="section-title">Places</h2>
                </div>
            </div>
            
            <div class="row">
                <?php
                $places_query = "SELECT p.*, g.G_name as governorate_name 
                                FROM places p 
                                LEFT JOIN governorates g ON p.g_num = g.G_ID 
                                WHERE p.g_num = $selected_gov_id 
                                ORDER BY p.P_ID DESC";
                $places_result = $conn->query($places_query);
                
                if (!$places_result) {
                    echo '<div class="col-12 alert alert-danger">Error loading places: ' . $conn->error . '</div>';
                } else {
                    if ($places_result->num_rows > 0) {
                        $place_counter = 0;
                        while($place = $places_result->fetch_assoc()) {
                            $place_id = $place['P_ID'];
                            $place_name = $place['p_name'];
                            $place_desc = $place['description'] ?? '';
                            $place_price = $place['ticket_price'];
                            $place_gov = $place['governorate_name'] ?? '';
                            $place_hours = $place['opening_hours'] ?? '';
                            $location_url = $place['location_url'] ?? '';
                            $main_image = $place['main_image'] ?? '';
                            
                            $place_image = '';
                            if (!empty($main_image)) {
                                if (filter_var($main_image, FILTER_VALIDATE_URL)) {
                                    $place_image = $main_image;
                                } else if (file_exists(dirname(__FILE__) . '/assets/uploads/places/' . $main_image)) {
                                    $place_image = BASE_URL . 'assets/uploads/places/' . $main_image;
                                }
                            }
                            
                            $place_counter++;
                ?>
                <div class="col-md-4 col-lg-4 fade-in">
                    <div class="place-card" onclick="window.location.href='place_details.php?id=<?php echo $place_id; ?>'">
                        <div class="place-image-wrapper">
                            <?php if (!empty($place_image)): ?>
                            <img src="<?php echo $place_image; ?>" alt="<?php echo htmlspecialchars($place_name); ?>" class="place-image">
                            <?php else: ?>
                            <div class="place-image" style="background: linear-gradient(135deg, #4a5568, #718096);"></div>
                            <?php endif; ?>
                            <div class="place-overlay"></div>
                        </div>
                        
                        <div class="place-content">
                            <h5 class="place-name"><?php echo htmlspecialchars($place_name); ?></h5>
                            
                            <?php if (!empty($place_price)): ?>
                            <span class="place-price">
                                <i class="fas fa-ticket-alt me-1"></i>
                                <?php echo number_format($place_price, 2); ?> EGP
                            </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($place_hours)): ?>
                            <div class="opening-hours">
                                <i class="fas fa-clock"></i>
                                <span><?php echo htmlspecialchars($place_hours); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($location_url) && filter_var($location_url, FILTER_VALIDATE_URL)): ?>
                            <div>
                                <a href="<?php echo htmlspecialchars($location_url); ?>" target="_blank" class="location-link" onclick="event.stopPropagation();">
                                    <i class="fas fa-map-marker-alt"></i> View on Map
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="place-description-container">
                            <?php if (!empty($place_desc)): ?>
                            <div class="place-description-hover">
                                <?php echo htmlspecialchars($place_desc); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    } else {
                    ?>
                    <div class="col-12 text-center py-5 fade-in">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h4>No places found in this category</h4>
                            <p class="mb-0">There are currently no places added to this category.</p>
                        </div>
                    </div>
                    <?php
                    }
                }
                ?>
            </div>
        </section>
    </div>
    
    <?php if (file_exists(dirname(__FILE__) . '/includes/footer.php')): ?>
        <?php include dirname(__FILE__) . '/includes/footer.php'; ?>
    <?php endif; ?>
    
    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                el.style.animationDelay = (index * 0.1) + 's';
            });
            
            document.querySelectorAll('.location-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
</body>
</html>