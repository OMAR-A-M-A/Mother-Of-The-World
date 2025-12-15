<?php include 'config.php'; 
include 'includes/db_connect.php';

//FetchCategories for Display
$categories = [];
$fetch_sql = "
    SELECT 
        c.*, 
        COUNT(p.P_ID) AS places_count 
    FROM 
        categories c
    LEFT JOIN 
        places p ON c.C_ID = p.C_num 
    GROUP BY 
        c.C_ID
    ORDER BY 
        c.C_ID DESC 
    LIMIT 3
";
$cat_result = $conn->query($fetch_sql);

if ($cat_result) {
    while ($cat_row = $cat_result->fetch_assoc()) {
        $categories[] = $cat_row;
    }
    $cat_result->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- font awesome -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/all.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
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
                     <div class="card text-white col-md-4 p-0 overflow-hidden shadow-sm position-relative my_card"
                      data-places="<?php echo $category['places_count']; ?>">
                         <img 
                            class="card-img h-100 w-100 object-cover"
                             src="<?php echo BASE_URL; ?>assets/uploads/categories/<?php echo $category['C_image']; ?>" 
                         > 
                         <div class="card-img-overlay d-flex flex-column justify-content-start align-items-center p-5 content">
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
            <div class="content d-flex flex-column justify-content-space-between align-items-center text-center w-50 p-5">
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
                            Donec ullamcorper nulla non metus auctor fringilla. Sed posuere consectetur est at lobortis. Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna.
                        </p>
                        <div class="d-flex align-items-center mt-4">
                            <img src="assets/images/avatar-03.png" class="profile-img rounded-circle me-3" alt="Luaka Smith">
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
                            Donec ullamcorper nulla non metus auctor fringilla. Sed posuere consectetur est at lobortis. Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna.
                        </p>
                        <div class="d-flex align-items-center mt-4">
                            <img src="assets/images/avatar-04.png" class="profile-img rounded-circle me-3" alt="Jane Doe">
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
                            Donec ullamcorper nulla non metus auctor fringilla. Sed posuere consectetur est at lobortis. Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna.
                        </p>
                        <div class="d-flex align-items-center mt-4">
                            <img src="assets/images/avatar-05.png" class="profile-img rounded-circle me-3" alt="John Smith">
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

    <!-- start footer section -->
    <?php include 'includes/footer.php'; ?>
    <!-- end footer section -->

    <!-- bootstrap js -->
    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>