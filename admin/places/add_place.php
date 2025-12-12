<?php
session_start();
include '../../config.php';
include '../../includes/db_connect.php';

// Fetch Governorates for the dropdown list
$gov_query = "SELECT * FROM governorates";
$gov_result = mysqli_query($conn, $gov_query);

// Fetch Categories for the dropdown list
$cat_query = "SELECT * FROM categories"; // Ensure the 'categories' table exists in your DB
$cat_result = mysqli_query($conn, $cat_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Place</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="d-flex">

        <?php include '../includes/sidebar.php'; ?>

        <div class="flex-grow-1 bg-light">

            <?php include '../includes/navbar.php'; ?>

            <div class="container p-5">

                <div class="mb-4">
                    <a href="manage_places.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h4 class="text-primary">Add New Place</h4>
                    </div>
                    <div class="card-body">
                        <form action="place_functions.php" method="POST" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Place Name</label>
                                    <input type="text" name="p_name" class="form-control" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Price (EGP)</label>
                                    <input type="number" name="ticket_price" class="form-control" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Main Image</label>
                                    <input type="file" name="main_image" class="form-control" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="">Additional Gallery Images </label>
                                    <input type="file" name="additional_images[]" class="form-control" multiple>
                                    <small class="text-muted">You can select multiple images at once .</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Governorate</label>
                                    <select name="g_num" class="form-select" required>
                                        <option value="">-- Select Governorate --</option>
                                        <?php while ($row = mysqli_fetch_assoc($gov_result)): ?>
                                            <option value="<?php echo $row['G_ID']; ?>"><?php echo $row['G_name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Category</label>
                                    <select name="C_num" class="form-select" required>
                                        <option value="">-- Select Category --</option>
                                        <?php while ($row = mysqli_fetch_assoc($cat_result)): ?>
                                            <option value="<?php echo $row['C_ID']; ?>"><?php echo $row['C_name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Opening Hours</label>
                                    <input type="text" name="opening_hours" class="form-control"
                                        placeholder="e.g. 9 AM - 5 PM">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Location URL (Google Maps)</label>
                                    <input type="text" name="location_url" class="form-control">
                                </div>
                            </div>

                            <button type="submit" name="save_place_btn" class="btn btn-primary w-100">Save
                                Place</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>