<?php
session_start();
include '../../config.php';
include '../../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

$category_data = null;
$edit_message = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_category.php?status=invalid_id");
    exit();
}

$category_id = intval($_GET['id']);

// ==========================================================
// 1. UPDATE Logic
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {
    $new_name = trim($_POST['category_name']);
    $new_desc = trim($_POST['category_desc']);
    $old_image = $_POST['old_image'];

    // Image Handling
    $image_name = $_FILES['category_image']['name'];
    $final_image_name = $old_image; // Default to old image

    if (!empty($new_name)) {
        // If a new image is uploaded
        if (!empty($image_name)) {
            $image_tmp = $_FILES['category_image']['tmp_name'];
            $ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $final_image_name = time() . "_" . uniqid() . "." . $ext;

            // Upload new
            move_uploaded_file($image_tmp, "../../assets/uploads/categories/" . $final_image_name);

            // Delete old if exists
            if (!empty($old_image) && file_exists("../../assets/uploads/categories/" . $old_image)) {
                unlink("../../assets/uploads/categories/" . $old_image);
            }
        }

        $stmt = $conn->prepare("UPDATE categories SET C_name = ?, C_description = ?, C_image = ? WHERE C_ID = ?");
        $stmt->bind_param("sssi", $new_name, $new_desc, $final_image_name, $category_id);

        if ($stmt->execute()) {
            $edit_message = "<div class='alert alert-success'>Category updated successfully!</div>";
            // Refresh old image variable for display
            $old_image = $final_image_name;
        } else {
            $edit_message = "<div class='alert alert-danger'>Error updating: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $edit_message = "<div class='alert alert-warning'>Category name cannot be empty.</div>";
    }
}

// ==========================================================
// 2. Fetch Data
// ==========================================================
$stmt = $conn->prepare("SELECT * FROM categories WHERE C_ID = ? LIMIT 1");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $category_data = $result->fetch_assoc();
} else {
    header("Location: manage_category.php?status=not_found");
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="d-flex">

        <?php include '../includes/sidebar.php'; ?>

        <div class="flex-grow-1 bg-light">

            <?php include '../includes/navbar.php'; ?>

            <div class="container-fluid p-4">

                <div class="mb-4">
                    <a href="manage_category.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <?php echo $edit_message; ?>

                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h4 class="text-primary m-0"><i class="fa-solid fa-pen-to-square"></i> Edit Category
                                </h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="" enctype="multipart/form-data">

                                    <input type="hidden" name="old_image"
                                        value="<?php echo $category_data['C_image']; ?>">

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Category Name</label>
                                        <input type="text" name="category_name" class="form-control"
                                            value="<?php echo htmlspecialchars($category_data['C_name']); ?>" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="category_desc" class="form-control"
                                            rows="4"><?php echo htmlspecialchars($category_data['C_description']); ?></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                                <p class="mb-2 text-muted small">Current Image</p>
                                                <?php
                                                $img_src = !empty($category_data['C_image'])
                                                    ? BASE_URL . "assets/uploads/categories/" . $category_data['C_image']
                                                    : "https://via.placeholder.com/150?text=No+Image";
                                                ?>
                                                <img src="<?php echo $img_src; ?>" alt="Current Image"
                                                    class="rounded border shadow-sm"
                                                    style="width: 120px; height: 120px; object-fit: cover;">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label fw-bold">Change Image (Optional)</label>
                                                <input type="file" name="category_image" class="form-control">
                                                <small class="text-muted d-block mt-1">Leave empty to keep current
                                                    image.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" name="update_category" class="btn btn-primary px-4">
                                            Update Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>