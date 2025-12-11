<?php
session_start();

// Include database connection
include '../../includes/db_connect.php';

// ==========================================
// 1. Add New Governorate (Create)
// ==========================================
if (isset($_POST['save_gov_btn'])) {

    // 1. Sanitize Input
    $name = mysqli_real_escape_string($conn, $_POST['governorate_name']);

    // 2. Handle Image Upload
    $image = $_FILES['governorate_image']['name'];

    // Rename image to ensure uniqueness (e.g., 1684354.jpg)
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_extension;

    // 3. Insert into Database
    $query = "INSERT INTO governorates (G_name, image_url) VALUES ('$name', '$filename')";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // 4. Move uploaded file to server folder
        // Note: We use relative paths (../../) for file system operations, not BASE_URL
        move_uploaded_file($_FILES['governorate_image']['tmp_name'], '../../assets/uploads/governorates/' . $filename);

        $_SESSION['status'] = "Governorate added successfully";
        header('Location: manage_gov.php');
    } else {
        $_SESSION['status'] = "Error adding governorate: " . mysqli_error($conn);
        header('Location: manage_gov.php');
    }
}

// ==========================================
// 2. Update Data (Edit)
// ==========================================
if (isset($_POST['update_gov_btn'])) {

    // 1. Get Form Data
    $id = $_POST['gov_id'];
    $name = mysqli_real_escape_string($conn, $_POST['governorate_name']);
    $old_image = $_POST['old_image']; // Hidden input containing old image name
    $new_image = $_FILES['governorate_image']['name'];

    $update_filename = "";

    // 2. Check if user uploaded a new image
    if ($new_image != "") {
        // User uploaded a new image -> Rename it
        $image_extension = pathinfo($new_image, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $image_extension;
    } else {
        // User kept the old image -> Use old name
        $update_filename = $old_image;
    }

    // 3. Update Database
    $query = "UPDATE governorates SET G_name='$name', image_url='$update_filename' WHERE G_ID='$id' ";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // 4. Handle File Movement if Image Changed
        if ($new_image != "") {
            // Upload new image
            move_uploaded_file($_FILES['governorate_image']['tmp_name'], '../../assets/uploads/governorates/' . $update_filename);

            // Delete old image to save space
            if (file_exists('../../assets/uploads/governorates/' . $old_image)) {
                unlink('../../assets/uploads/governorates/' . $old_image);
            }
        }

        $_SESSION['status'] = "Governorate updated successfully";
        header('Location: manage_gov.php');
    } else {
        $_SESSION['status'] = "Error updating governorate: " . mysqli_error($conn);
        header('Location: manage_gov.php');
    }
}

?>