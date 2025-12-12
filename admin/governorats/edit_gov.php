<?php
session_start();
include '../../config.php';
include '../../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM governorates WHERE G_ID = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['status'] = "No governorate found with this ID.";
        header("Location: manage_gov.php");
        exit();
    }
} else {
    $_SESSION['status'] = "No ID provided.";
    header("Location: manage_gov.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Governorate</title>
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
                    <a href="manage_gov.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to
                        List</a>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h4 class="text-primary m-0"><i class="fa-solid fa-pen-to-square"></i> Edit Governorate
                                </h4>
                            </div>
                            <div class="card-body">

                                <form action="gov_functions.php" method="POST" enctype="multipart/form-data">

                                    <input type="hidden" name="gov_id" value="<?php echo $row['G_ID']; ?>">
                                    <input type="hidden" name="old_image" value="<?php echo $row['image_url']; ?>">

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Governorate Name</label>
                                        <input type="text" name="governorate_name"
                                            value="<?php echo htmlspecialchars($row['G_name']); ?>" class="form-control"
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="governorate_desc" class="form-control"
                                            rows="4"><?php echo htmlspecialchars($row['G_description']); ?></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                                <p class="mb-2 text-muted small">Current Image</p>
                                                <?php $img_src = BASE_URL . "assets/uploads/governorates/" . $row['image_url']; ?>
                                                <img src="<?php echo $img_src; ?>" class="rounded border shadow-sm"
                                                    style="width: 120px; height: 120px; object-fit: cover;">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label fw-bold">Change Image (Optional)</label>
                                                <input type="file" name="governorate_image" class="form-control">
                                                <small class="text-muted d-block mt-1">Leave empty to keep current
                                                    image.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" name="update_gov_btn" class="btn btn-primary px-4">Update
                                            Data</button>
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