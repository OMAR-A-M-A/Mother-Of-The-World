<?php
session_start();
include '../../config.php';
include '../../includes/db_connect.php';

// 2. Authentication Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

// ==========================================================
// 1. Logic for Adding a New Category (Create)
// ==========================================================
$add_message = '';
$show_modal = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    $category_desc = trim($_POST['category_desc']);

    // Image Upload Logic
    $image_name = $_FILES['category_image']['name'];
    $image_tmp = $_FILES['category_image']['tmp_name'];
    $new_image_name = "";

    if (!empty($category_name)) {

        // Handle Image Upload if exists
        if (!empty($image_name)) {
            $ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $new_image_name = time() . "_" . uniqid() . "." . $ext;
            // Ensure this folder exists: assets/uploads/categories/
            $upload_path = "../../assets/uploads/categories/" . $new_image_name;
            move_uploaded_file($image_tmp, $upload_path);
        }

        $stmt = $conn->prepare("INSERT INTO categories (C_name, C_description, C_image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $category_name, $category_desc, $new_image_name);

        if ($stmt->execute()) {
            header("Location: manage_category.php?msg=added");
            exit();
        } else {
            $add_message = "<div class='alert alert-danger'>Error adding category: " . $stmt->error . "</div>";
            $show_modal = true;
        }

        $stmt->close();
    } else {
        $add_message = "<div class='alert alert-warning'>Category name cannot be empty.</div>";
        $show_modal = true;
    }
}

// ==========================================================
// 2. Fetch All Categories for Display (Read)
// ==========================================================
$categories = [];
$fetch_sql = "SELECT * FROM categories ORDER BY C_ID DESC";
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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="d-flex">

        <?php include '../includes/sidebar.php'; ?>

        <div class="flex-grow-1 bg-light">

            <?php include '../includes/navbar.php'; ?>

            <div class="container-fluid p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-secondary fw-bold">Categories List</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addCategoryModal">
                        <i class="fa-solid fa-plus"></i> Add New Category
                    </button>
                </div>

                <?php
                if (isset($_GET['msg']) && $_GET['msg'] == 'added') {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            Category added successfully!
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                            </div>";
                }
                if (isset($_GET['status'])) {
                    $status = htmlspecialchars($_GET['status']);
                    if ($status == 'success')
                        echo "<div class='alert alert-success alert-dismissible fade show'>Deleted successfully.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
                    elseif ($status == 'error')
                        echo "<div class='alert alert-danger alert-dismissible fade show'>Error deleting record.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
                }
                echo $add_message;
                ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#ID</th>
                                        <th>Image</th>
                                        <th>Category Info</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($categories) > 0): ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td class="text-muted">#<?php echo $i; ?></td>
                                                <td>
                                                    <?php
                                                    $img_src = !empty($category['C_image'])
                                                        ? BASE_URL . "assets/uploads/categories/" . $category['C_image']
                                                        : "https://via.placeholder.com/60?text=No+Img";
                                                    ?>
                                                    <img src="<?php echo $img_src; ?>" alt="Cat Img" class="rounded"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($category['C_name']); ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?php echo substr(htmlspecialchars($category['C_description']), 0, 50) . '...'; ?>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <a href="edit_category.php?id=<?php echo $category['C_ID']; ?>"
                                                        class="btn btn-sm btn-outline-success me-2">
                                                        <i class="fa-solid fa-pen"></i> Edit
                                                    </a>

                                                    <a href="delete_category.php?id=<?php echo $category['C_ID']; ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure? This might affect related places.');">
                                                        <i class="fa-solid fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $i++; endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No categories found.</td>
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

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="manage_category.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="category_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="category_desc" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="category_image" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_category" class="btn btn-primary">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>

    <?php if ($show_modal): ?>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
            myModal.show();
        </script>
    <?php endif; ?>
</body>

</html>