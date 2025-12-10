<?php
// تضمين ملف الاتصال
include '../includes/db_connect.php'; 

// 1. جلب بيانات الإحصائيات ديناميكياً
$stats = [];

// استعلامات SQL لحساب الإجماليات
$queries = [
    'Total Governorates' => "SELECT COUNT(G_ID) AS count FROM governorates",
    'Total Categories' => "SELECT COUNT(C_ID) AS count FROM categories",
    'Total Places' => "SELECT COUNT(P_ID) AS count FROM places",
     'Total Admins' => "SELECT COUNT(ID) AS count FROM admins",
];

$icon_mapping = [
    'Total Governorates' => ['icon' => 'fa-city', 'bg_color' => 'bg-primary'],
    'Total Categories' => ['icon' => 'fa-list-ul', 'bg_color' => 'bg-info'],
    'Total Places' => ['icon' => 'fa-map-marker-alt', 'bg_color' => 'bg-danger'],
    'Total Admins' => ['icon' => 'fa-user-tie', 'bg_color' => 'bg-success'],
];


foreach ($queries as $title => $sql) {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stats[] = [
        'title' => $title,
        'count' => number_format($result['count']), // تنسيق الرقم
        'icon' => $icon_mapping[$title]['icon'],
        'bg_color' => $icon_mapping[$title]['bg_color'],
    ];
}

// 2. جلب بيانات الأماكن (الجدول الرئيسي)
// هنا نجلب الأماكن مع أسماء المحافظات والفئات المرتبطة بها باستخدام JOIN
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

$stmt = $conn->prepare($places_sql);
$stmt->execute();
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V-Dashboard - Dynamic Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* (تنسيقات CSS المخصصة نفسها كما في الإجابة السابقة) */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 300px; min-height: 100vh; background-color: #1a1c23; color: #fff; }
        .sidebar .nav-link { color: #a0aec0; padding: 12px 20px; border-radius: 5px; margin-bottom: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #2d3748; color: #fff; }
        .icon-box { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; }
        .badge-active { background-color: #d1fae5; color: #065f46; padding: 5px 12px; border-radius: 20px; font-weight: 600; }
        .table-img { width: 40px; height: 40px; object-fit: cover; border-radius: 5px;}
        *{
            box-sizing: border-box;
        }
    </style>
</head>
<body>

<div class="d-flex">
    
    <div class="sidebar p-4 d-flex flex-column">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2">
            <i class="fa-solid fa-fire text-primary me-2 fs-4"></i>
            <span class="fs-4 fw-bold">V-Dashboard</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active"><i class="fa-solid fa-chart-pie me-2"></i> Dashboard</a>
            </li>
            <li><a href="#" class="nav-link"><i class="fa-solid fa-layer-group me-2"></i> Admins</a></li>
            <li><a href="#" class="nav-link"><i class="fa-solid fa-table me-2"></i> Governorates</a></li>
            <li><a href="#" class="nav-link"><i class="fa-solid fa-pen-to-square me-2"></i> Categories</a></li>
            <li><a href="#" class="nav-link"><i class="fa-solid fa-credit-card me-2"></i> Place Images</a></li>
        </ul>
    </div>

    <div class="w-100 d-flex flex-column">
        
        <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
            <div class="d-flex w-100 justify-content-end align-items-center">
                <a href="logout.php" class="btn btn-outline-danger d-flex align-items-center gap-2">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Log out</span>
                </a>
            </div>
        </nav>

        <div class="container-fluid p-4 bg-light h-100">
            <h3 class="mb-4 text-secondary fw-bold">Dashboard Overview</h3>

            <div class="row g-4 mb-4">
                <?php foreach($stats as $stat): ?>
                <div class="col-md-6">
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
                                    <th class="ps-4 py-3 text-secondary text-uppercase" style="font-size:0.8rem">Image/ID</th>
                                    <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">Place Name</th>
                                    <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">Governorate</th>
                                    <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">Category</th>
                                    <th class="py-3 text-secondary text-uppercase" style="font-size:0.8rem">Price</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($places) > 0): ?>
                                    <?php foreach($places as $place): ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <?php $image_src = !empty($place['main_image']) ? $place['main_image'] : 'https://via.placeholder.com/40x40?text=P'; ?>
                                                <img src="<?php echo htmlspecialchars($image_src); ?>" class="table-img me-3" alt="Place Image">
                                                <div class="text-muted small">#<?php echo $place['P_ID']; ?></div>
                                            </div>
                                        </td>
                                        <td><div class="fw-bold text-dark"><?php echo htmlspecialchars($place['p_name']); ?></div></td>
                                        <td><span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($place['governorate_name'] ?? 'N/A'); ?></span></td>
                                        <td><span class="badge bg-secondary-subtle text-secondary"><?php echo htmlspecialchars($place['category_name'] ?? 'N/A'); ?></span></td>
                                        <td><div class="fw-normal"><?php echo number_format($place['ticket_price'], 2) . ' EGP'; ?></div></td>
                                        <td class="text-end pe-4">
                                            <a href="#" class="text-primary text-decoration-none fw-bold me-2">Edit</a>
                                            <a href="#" class="text-danger text-decoration-none fw-bold">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">لا توجد أماكن مُضافة حاليًا في قاعدة البيانات.</td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>