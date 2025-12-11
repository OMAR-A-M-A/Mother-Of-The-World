<?php
// Include the database connection file.
include '<?php echo BASE_URL; ?> includes/db_connect.php'; 
 
// Check if the connection was successful
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ==========================================================
// 1. Logic for Adding a New Category (Create)
// ==========================================================
$add_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        // Use Prepared Statements for security against SQL Injection
        $stmt = $conn->prepare("INSERT INTO categories (C_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            $add_message = "<div class='alert alert-success'>Category '{$category_name}' added successfully!</div>";
        } else {
            $add_message = "<div class='alert alert-danger'>Error adding category: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        $add_message = "<div class='alert alert-warning'>Category name cannot be empty.</div>";
    }
}

// ==========================================================
// 2. Fetch All Categories for Display (Read)
// ==========================================================
$categories = [];
$fetch_sql = "SELECT C_ID, C_name FROM categories ORDER BY C_ID DESC";

$result = $conn->query($fetch_sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    $result->free();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .action-icon { font-size: 1.1rem; margin: 0 5px; }
    </style>
</head>
<body>

<div class="d-flex">
    
    <?php
    // Include sidebar navigation
    include '<?php echo BASE_URL; ?>includes/sidebar.php';
    ?>

    <div class="w-100 d-flex flex-column">
        
        <?php
        // Include top navigation bar
        include '<?php echo BASE_URL; ?>includes/navbar.php';
        ?>

        <div class="container-fluid p-4 bg-light h-100">
            <h3 class="mb-4 text-secondary fw-bold"><i class="fa-solid fa-list-ul me-2"></i> Manage Categories</h3>

            <?php 
            echo $add_message; // For Add Category Form messages

            if (isset($_GET['status'])) {
                $status = htmlspecialchars($_GET['status']);
                if ($status == 'success') {
                    echo "<div class='alert alert-success'>Operation completed successfully.</div>";
                } elseif ($status == 'error') {
                    echo "<div class='alert alert-danger'>Error: Operation failed. Please check database logs.</div>";
                } elseif ($status == 'invalid_id') {
                    echo "<div class='alert alert-warning'>Error: Invalid ID provided.</div>";
                } elseif ($status == 'not_found') {
                    echo "<div class='alert alert-info'>Category not found.</div>";
                }
            }
            ?>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Add New Category</div>
                <div class="card-body">
                    <form method="POST" action="manage_category.php">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name (e.g., Historical Sites, Parks)" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="add_category" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-plus me-1"></i> Add Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-bold">Existing Categories (<?php echo count($categories); ?>)</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 10%">ID</th>
                                    <th class="py-3">Category Name</th>
                                    <th class="py-3 text-end pe-4" style="width: 25%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($categories) > 0): ?>
                                    <?php foreach($categories as $category): ?>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted small">#<?php echo htmlspecialchars($category['C_ID']); ?></td>
                                        <td><div class="fw-bold text-dark"><?php echo htmlspecialchars($category['C_name']); ?></div></td>
                                        <td class="text-end pe-4">
                                            <a href="edit_category.php?id=<?php echo $category['C_ID']; ?>" class="text-info text-decoration-none action-icon" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="delete_category.php?id=<?php echo $category['C_ID']; ?>" class="text-danger text-decoration-none action-icon" title="Delete" onclick="return confirm('Are you sure you want to delete this category?');">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No categories found. Please add a new one.</td>
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