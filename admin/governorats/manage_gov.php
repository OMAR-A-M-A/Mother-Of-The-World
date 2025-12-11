<?php
session_start();

include '../../includes/db_connect.php';

// 2. Authentication Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

// 3. Delete Logic
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // sanitize id

    // Fetch image name before deleting record
    $query_img = "SELECT image_url FROM governorates WHERE G_ID = $id";
    $result_img = mysqli_query($conn, $query_img);
    $row_img = mysqli_fetch_assoc($result_img);

    // Delete from Database
    $delete_query = "DELETE FROM governorates WHERE G_ID = $id";
    if (mysqli_query($conn, $delete_query)) {
        // Delete Image from Server if exists
        // FIX: Path should be relative to file system, going back 2 levels to root
        if ($row_img && !empty($row_img['image_url'])) {
            $image_path = "../../assets/uploads/governorates/" . $row_img['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        // Redirect with success message
        header("Location: manage_gov.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// 4. Fetch Data
$sql = "SELECT * FROM governorates ORDER BY G_ID DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Governorates</title>

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
                    <h2 class="text-dark">Governorates List</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGovModal">
                        <i class="fa-solid fa-plus"></i> Add New Governorate
                    </button>
                </div>

                <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Governorate deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Note:</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#ID</th>
                                        <th>Image</th>
                                        <th>Governorate Name</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <?php
                                                    $img_name = $row['image_url'];
                                                    // Construct absolute URL for the image
                                                    $img_src = BASE_URL . "assets/uploads/governorates/" . $img_name;
                                                    ?>
                                                    <img src="<?php echo $img_src; ?>" alt="Gov Image" class="rounded"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                </td>
                                                <td class="fw-bold"><?php echo htmlspecialchars($row['G_name']); ?></td>
                                                <td class="text-center">
                                                    <a href="edit_gov.php?id=<?php echo $row['G_ID']; ?>"
                                                        class="btn btn-sm btn-outline-success me-2">
                                                        <i class="fa-solid fa-pen"></i> Edit
                                                    </a>

                                                    <a href="manage_gov.php?delete_id=<?php echo $row['G_ID']; ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure? All places inside this governorate will be deleted!');">
                                                        <i class="fa-solid fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $i++; endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No governorates found.</td>
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

    <?php include 'add_gov.php'; ?>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>