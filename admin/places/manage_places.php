<?php
session_start();
include '../../config.php';
include '../../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

// --- delete ---
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // delete photo from the server
    $query_img = "SELECT main_image FROM places WHERE P_ID = $id";
    $result_img = mysqli_query($conn, $query_img);
    $row_img = mysqli_fetch_assoc($result_img);

    $delete_query = "DELETE FROM places WHERE P_ID = $id";
    if (mysqli_query($conn, $delete_query)) {
        if ($row_img && !empty($row_img['main_image'])) {
            $path = "../../assets/uploads/places/" . $row_img['main_image'];
            if (file_exists($path))
                unlink($path);
        }
        header("Location: manage_places.php?msg=deleted");
        exit();
    }
}

// --- request the data from sercver (place data, gov, category) ---
$sql = "SELECT p.*, g.G_name, c.C_name 
        FROM places p 
        LEFT JOIN governorates g ON p.g_num = g.G_ID 
        LEFT JOIN categories c ON p.C_num = c.C_ID 
        ORDER BY p.P_ID DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Places</title>
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
                    <h2 class="text-dark">Places List</h2>
                    <a href="add_place.php" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Add New Place
                    </a>
                </div>

                <?php if (isset($_SESSION['status'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['status'];
                        unset($_SESSION['status']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#ID</th>
                                        <th>Image</th>
                                        <th>Place Name</th>
                                        <th>Governorate</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php $i = 1;?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td class="text-muted">#<?php echo $i; ?></td>
                                                <td>
                                                    <img src="<?php echo BASE_URL . 'assets/uploads/places/' . $row['main_image']; ?>"
                                                        class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                </td>
                                                <td class="fw-bold"><?php echo htmlspecialchars($row['p_name']); ?></td>
                                                <td><span
                                                        class="badge bg-info text-dark"><?php echo $row['G_name'] ?? 'N/A'; ?></span>
                                                </td>
                                                <td><span
                                                        class="badge bg-secondary"><?php echo $row['C_name'] ?? 'N/A'; ?></span>
                                                </td>
                                                <td><?php echo number_format($row['ticket_price']); ?> EGP</td>
                                                <td>
                                                    <a href="edit_place.php?id=<?php echo $row['P_ID']; ?>"
                                                        class="btn btn-sm btn-outline-success"><i
                                                            class="fa-solid fa-pen"></i> </a>
                                                    <a href="manage_places.php?delete_id=<?php echo $row['P_ID']; ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this place?');"><i
                                                            class="fa-solid fa-trash"></i> </a>
                                                </td>
                                            </tr>
                                        <?php $i++;endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No places found.</td>
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
    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>