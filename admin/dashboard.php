<?php
session_start();

// 1. Include Configuration File
include '../config.php';

// 2. Include Database Connection
include "../includes/db_connect.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

// Verify connection success
if (!isset($conn) || $conn->connect_error) {
    die("Database connection error.");
}

// ---------------------------------------------------------
// 1. Fetch Statistics Data Dynamically
// ---------------------------------------------------------
$stats = [];

$queries = [
    'Total Governorates' => "SELECT COUNT(G_ID) AS count FROM governorates",
    'Total Categories' => "SELECT COUNT(C_ID) AS count FROM categories",
    'Total Places' => "SELECT COUNT(P_ID) AS count FROM places",
    'Total Admins' => "SELECT COUNT(ID) AS count FROM admins",
];


// Mapping for icons and background colors
$icon_mapping = [
    'Total Governorates' => ['icon' => 'fa-city', 'bg_color' => 'bg-primary'],
    'Total Categories' => ['icon' => 'fa-list-ul', 'bg_color' => 'bg-info'],
    'Total Places' => ['icon' => 'fa-map-marker-alt', 'bg_color' => 'bg-danger'],
    'Total Admins' => ['icon' => 'fa-user-tie', 'bg_color' => 'bg-success'],
];

foreach ($queries as $title => $sql) {
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $result->free();
    } else {
        $count = 0;
    }

    $stats[] = [
        'title' => $title,
        'count' => number_format($count),
        'icon' => $icon_mapping[$title]['icon'],
        'bg_color' => $icon_mapping[$title]['bg_color'],
    ];
}

// ---------------------------------------------------------
// 2. Fetch Recent Places (Main Table)
// ---------------------------------------------------------
$places_sql = "
    SELECT 
        p.P_ID, 
        p.p_name, 
        p.ticket_price, 
        g.G_name AS governorate_name, 
        c.C_name AS category_name,
        p.main_image
    FROM 
        places p
    LEFT JOIN 
        governorates g ON p.g_num = g.G_ID
    LEFT JOIN 
        categories c ON p.C_num = c.C_ID
    ORDER BY p.P_ID DESC 
    LIMIT 10
";

$result_places = $conn->query($places_sql);
$places = [];

if ($result_places) {
    while ($row = $result_places->fetch_assoc()) {
        $places[] = $row;
    }
    $result_places->free();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V-Dashboard - Dynamic Admin</title>

    <!-- bootstrap css -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .badge-active {
            background-color: #d1fae5;
            color: #065f46;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .table-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
        }

        * {
            box-sizing: border-box;
        }

    </style>
</head>

<body>

<div class="d-flex">
    
    <?php
    // Include sidebar navigation
    include 'includes/sidebar.php';
    ?>

        <div class="w-100 d-flex flex-column">

            <?php include 'includes/navbar.php'; ?>

            <div class="container-fluid p-4 bg-light h-100">
                <h3 class="mb-4 text-secondary fw-bold">Dashboard Overview</h3>

                <div class="row g-4 mb-4">
                    <?php foreach ($stats as $stat): ?>
                        <div class="col-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box <?php echo $stat['bg_color']; ?> me-3">
                                        <i class="fa-solid <?php echo $stat['icon']; ?>"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0"><?php echo $stat['count']; ?></h4>
                                        <span class="text-muted"><?php echo $stat['title']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h3 class="mb-3 mt-5 text-secondary fw-bold">Recent Places</h3>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-secondary text-uppercase" style="font-size:0.8rem">
                                            Image/ID</th>
                                        <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">Place
                                            Name</th>
                                        <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">
                                            Governorate</th>
                                        <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">Category
                                        </th>
                                        <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">Price
                                        </th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($places) > 0): ?>
                                        <?php $i=1; ?>
                                        <?php foreach ($places as $place): ?>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <?php
                                                        // --- Image Path Logic ---
                                                        // If database has an image name, append it to BASE_URL and places folder path
                                                        if (!empty($place['main_image'])) {
                                                            $image_src = BASE_URL . 'assets/uploads/places/' . htmlspecialchars($place['main_image']);
                                                        } else {
                                                            // Fallback placeholder if no image exists
                                                            $image_src = 'https://via.placeholder.com/40x40?text=P';
                                                        }
                                                        ?>
                                                        <img src="<?php echo $image_src; ?>" class="table-img me-3"
                                                            alt="Place Image">
                                                        <div class="text-muted small">
                                                            #<?php echo $i ?></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark">
                                                        <?php echo htmlspecialchars($place['p_name']); ?></div>
                                                </td>
                                                <td><span
                                                        class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($place['governorate_name'] ?? 'N/A'); ?></span>
                                                </td>
                                                <td><span
                                                        class="badge bg-secondary-subtle text-secondary"><?php echo htmlspecialchars($place['category_name'] ?? 'N/A'); ?></span>
                                                </td>
                                                <td>
                                                    <div class="fw-normal">
                                                        <?php echo number_format($place['ticket_price'], 2) . ' EGP'; ?></div>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="#" class="text-primary text-decoration-none fw-bold me-2">Edit</a>
                                                    <a href="#" class="text-danger text-decoration-none fw-bold">Delete</a>
                                                </td>
                                            </tr>
                                        <?php $i++ ;endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No places currently added in
                                                the database.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
<!-- bootstrap js -->
<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>