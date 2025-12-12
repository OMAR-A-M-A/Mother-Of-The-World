<?php
session_start();
include '../../config.php';
include '../../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch place data
    $query = "SELECT * FROM places WHERE P_ID = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $place = mysqli_fetch_assoc($result);

    // Fetch additional images for the place
    $gallery_query = "SELECT * FROM place_images WHERE P_num = '$id'";
    $gallery_result = mysqli_query($conn, $gallery_query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Place</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="d-flex">
        <?php include '../includes/sidebar.php'; ?>
        <div class="flex-grow-1 bg-light">
            <?php include '../includes/navbar.php'; ?>

            <div class="container p-5">

                <?php if (isset($_SESSION['status'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['status'];
                        unset($_SESSION['status']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <a href="manage_places.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h4 class="text-primary">Edit Place</h4>
                    </div>
                    <div class="card-body">
                        <form action="place_functions.php" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="place_id" value="<?php echo $place['P_ID']; ?>">
                            <input type="hidden" name="old_image" value="<?php echo $place['main_image']; ?>">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Place Name</label>
                                    <input type="text" name="p_name"
                                        value="<?php echo htmlspecialchars($place['p_name']); ?>" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Price</label>
                                    <input type="number" name="ticket_price"
                                        value="<?php echo $place['ticket_price']; ?>" class="form-control" required>
                                </div>
                                <div class="col-md-3 mb-3 text-center">
                                    <label>Current Main Image</label><br>
                                    <img src="<?php echo BASE_URL . 'assets/uploads/places/' . $place['main_image']; ?>"
                                        width="60" class="rounded">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"
                                    rows="3"><?php echo htmlspecialchars($place['description']); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Governorate</label>
                                    <select name="g_num" class="form-select">
                                        <?php
                                        $govs = mysqli_query($conn, "SELECT * FROM governorates");
                                        while ($g = mysqli_fetch_assoc($govs)) {
                                            $selected = ($g['G_ID'] == $place['g_num']) ? 'selected' : '';
                                            echo "<option value='{$g['G_ID']}' $selected>{$g['G_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Category</label>
                                    <select name="C_num" class="form-select">
                                        <?php
                                        $cats = mysqli_query($conn, "SELECT * FROM categories");
                                        while ($c = mysqli_fetch_assoc($cats)) {
                                            $selected = ($c['C_ID'] == $place['C_num']) ? 'selected' : '';
                                            echo "<option value='{$c['C_ID']}' $selected>{$c['C_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Opening Hours</label>
                                    <input type="text" name="opening_hours"
                                        value="<?php echo htmlspecialchars($place['opening_hours']); ?>"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Location URL</label>
                                    <input type="text" name="location_url"
                                        value="<?php echo htmlspecialchars($place['location_url']); ?>"
                                        class="form-control">
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Change Main Image (Optional)</label>
                                    <input type="file" name="main_image" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Add More Gallery Images</label>
                                    <input type="file" name="additional_images[]" class="form-control" multiple>
                                </div>
                            </div>

                            <button type="submit" name="update_place_btn" class="btn btn-primary w-100">Update Place
                                Data</button>
                        </form>

                        <div class="mt-5">
                            <h5 class="text-secondary border-bottom pb-2">Current Gallery Images</h5>
                            <div class="row g-3">
                                <?php if (mysqli_num_rows($gallery_result) > 0): ?>
                                    <?php while ($img = mysqli_fetch_assoc($gallery_result)): ?>
                                        <div class="col-6 col-md-3">
                                            <div class="card h-100 border-0 shadow-sm position-relative">
                                                <img src="<?php echo BASE_URL . 'assets/uploads/places/' . $img['image_url']; ?>"
                                                    class="card-img-top rounded" style="height: 150px; object-fit: cover;">

                                                <form action="place_functions.php" method="POST"
                                                    class="position-absolute top-0 end-0 m-1">
                                                    <input type="hidden" name="img_id" value="<?php echo $img['I_ID']; ?>">
                                                    <input type="hidden" name="place_id_ref"
                                                        value="<?php echo $place['P_ID']; ?>">
                                                    <button type="submit" name="delete_gallery_image_btn"
                                                        class="btn btn-danger btn-sm rounded-circle shadow"
                                                        onclick="return confirm('Delete this image?');"
                                                        style="width: 30px; height: 30px; padding: 0;">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted">No additional images found.</p>
                                <?php endif; ?>
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