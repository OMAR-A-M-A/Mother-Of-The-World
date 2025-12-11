<?php
session_start();
include '../../config.php';
// Include the database connection file.
include '../../includes/db_connect.php'; 

// 2. Authentication Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

$category_data = null;
$edit_message = '';

// Check for ID in URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_category.php?status=invalid_id");
    exit();
}

$category_id = intval($_GET['id']);

// ==========================================================
// 1. Handle Form Submission (UPDATE logic)
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {
    $new_name = trim($_POST['category_name']);
    $category_id_post = intval($_POST['category_id']); // Ensure ID is taken from POST hidden field

    if (!empty($new_name) && $category_id_post == $category_id) {
        // Prepare UPDATE statement
        $stmt = $conn->prepare("UPDATE categories SET C_name = ? WHERE C_ID = ?");
        $stmt->bind_param("si", $new_name, $category_id); 

        if ($stmt->execute()) {
            $edit_message = "<div class='alert alert-success'>Category updated successfully!</div>";
        } else {
            $edit_message = "<div class='alert alert-danger'>Error updating category: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $edit_message = "<div class='alert alert-warning'>Category name cannot be empty.</div>";
    }
    // After update attempt, re-fetch data to reflect changes
}


// ==========================================================
// 2. Fetch current category data (READ logic)
// ==========================================================
// Prepare statement to avoid SQL injection on initial ID load
$stmt = $conn->prepare("SELECT C_ID, C_name FROM categories WHERE C_ID = ? LIMIT 1");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $category_data = $result->fetch_assoc();
} else {
    // Category not found
    header("Location: manage_category.php?status=not_found");
    exit();
}
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - <?php echo htmlspecialchars($category_data['C_name']); ?></title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/all.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>

    <div class="w-100 d-flex flex-column">
        <?php include '../includes/navbar.php'; ?>

        <div class="container-fluid p-4 bg-light h-100">
            <h3 class="mb-4 text-secondary fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Category</h3>

            <?php echo $edit_message; ?>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Editing Category ID: <?php echo $category_data['C_ID']; ?></div>
                <div class="card-body">
                    <form method="POST" action="edit_category.php?id=<?php echo $category_id; ?>">
                        
                        <input type="hidden" name="category_id" value="<?php echo $category_data['C_ID']; ?>">

                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" name="category_name" id="category_name" class="form-control" 
                                value="<?php echo htmlspecialchars($category_data['C_name']); ?>" required>
                        </div>
                        
                        <button type="submit" name="update_category" class="btn btn-success">
                            <i class="fa-solid fa-save me-1"></i> Save Changes
                        </button>
                        <a href="manage_category.php" class="btn btn-secondary">
                             <i class="fa-solid fa-arrow-left me-1"></i> Back to Categories
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
<!-- bootstrap js -->
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>