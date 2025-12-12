<?php
session_start();
include '../../includes/db_connect.php';

// ==========================================
// 1. Add New Governorate (Create)
// ==========================================
if (isset($_POST['save_gov_btn'])) {

    $name = mysqli_real_escape_string($conn, $_POST['governorate_name']);
    // New: Get Description
    $desc = mysqli_real_escape_string($conn, $_POST['governorate_desc']);

    $image = $_FILES['governorate_image']['name'];
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_extension;

    // Modified Query: Added G_description
    $query = "INSERT INTO governorates (G_name, G_description, image_url) VALUES ('$name', '$desc', '$filename')";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
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

    $id = $_POST['gov_id'];
    $name = mysqli_real_escape_string($conn, $_POST['governorate_name']);
    // New: Get Description
    $desc = mysqli_real_escape_string($conn, $_POST['governorate_desc']);

    $old_image = $_POST['old_image'];
    $new_image = $_FILES['governorate_image']['name'];

    $update_filename = "";

    if ($new_image != "") {
        $image_extension = pathinfo($new_image, PATHINFO_EXTENSION);
        $update_filename = time() . '.' . $image_extension;
    } else {
        $update_filename = $old_image;
    }

    // Modified Query: Added G_description update
    $query = "UPDATE governorates SET G_name='$name', G_description='$desc', image_url='$update_filename' WHERE G_ID='$id' ";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        if ($new_image != "") {
            move_uploaded_file($_FILES['governorate_image']['tmp_name'], '../../assets/uploads/governorates/' . $update_filename);
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