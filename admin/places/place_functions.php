<?php
session_start();
include '../../includes/db_connect.php';

// ==========================================
// 1. Add New Place + Multiple Images
// ==========================================
if (isset($_POST['save_place_btn'])) {

    // Basic Input Data
    $name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['ticket_price'];
    $hours = mysqli_real_escape_string($conn, $_POST['opening_hours']);
    $loc = mysqli_real_escape_string($conn, $_POST['location_url']);
    $g_num = $_POST['g_num'];
    $c_num = $_POST['C_num'];

    // 1. Upload Main Image (Cover)
    $main_image = $_FILES['main_image']['name'];
    $main_filename = time() . '_main.' . pathinfo($main_image, PATHINFO_EXTENSION);

    // Insert Place first to generate the ID
    $query = "INSERT INTO places (p_name, description, ticket_price, opening_hours, location_url, main_image, g_num, C_num) 
              VALUES ('$name', '$desc', '$price', '$hours', '$loc', '$main_filename', '$g_num', '$c_num')";

    if (mysqli_query($conn, $query)) {

        // Get the ID of the newly created place
        $place_id = mysqli_insert_id($conn);

        // Upload Main Image to server
        move_uploaded_file($_FILES['main_image']['tmp_name'], '../../assets/uploads/places/' . $main_filename);

        // 2. Upload Additional Images (Multiple Images)
        // Check if any additional images are selected
        if (!empty(array_filter($_FILES['additional_images']['name']))) {

            $count = count($_FILES['additional_images']['name']);

            for ($i = 0; $i < $count; $i++) {
                $img_name = $_FILES['additional_images']['name'][$i];
                $img_tmp = $_FILES['additional_images']['tmp_name'][$i];

                // Generate unique name for each gallery image
                $sub_filename = time() . '_' . $i . '.' . pathinfo($img_name, PATHINFO_EXTENSION);

                // Insert into 'place_images' table
                $sub_query = "INSERT INTO place_images (image_url, P_num) VALUES ('$sub_filename', '$place_id')";

                if (mysqli_query($conn, $sub_query)) {
                    move_uploaded_file($img_tmp, '../../assets/uploads/places/' . $sub_filename);
                }
            }
        }

        $_SESSION['status'] = "Place and Gallery added successfully!";
        header("Location: manage_places.php");
    } else {
        $_SESSION['status'] = "Error: " . mysqli_error($conn);
        header("Location: manage_places.php");
    }
}

// ==========================================
// 2. Update Data + Add New Images
// ==========================================
if (isset($_POST['update_place_btn'])) {

    $id = $_POST['place_id'];
    $name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['ticket_price'];
    $hours = mysqli_real_escape_string($conn, $_POST['opening_hours']);
    $loc = mysqli_real_escape_string($conn, $_POST['location_url']);
    $g_num = $_POST['g_num'];
    $c_num = $_POST['C_num'];

    // Handle Main Image (Logic remains the same)
    $old_image = $_POST['old_image'];
    $new_image = $_FILES['main_image']['name'];

    if ($new_image != "") {
        $filename = time() . '_main.' . pathinfo($new_image, PATHINFO_EXTENSION);
    } else {
        $filename = $old_image;
    }

    $query = "UPDATE places SET 
                p_name='$name', description='$desc', ticket_price='$price', 
                opening_hours='$hours', location_url='$loc', 
                g_num='$g_num', C_num='$c_num', main_image='$filename' 
              WHERE P_ID='$id'";

    if (mysqli_query($conn, $query)) {
        // Upload new main image if selected
        if ($new_image != "") {
            move_uploaded_file($_FILES['main_image']['tmp_name'], '../../assets/uploads/places/' . $filename);
            // Delete old image if exists
            if (file_exists('../../assets/uploads/places/' . $old_image)) {
                unlink('../../assets/uploads/places/' . $old_image);
            }
        }

        // ==========================================
        //  Add New Images to Gallery (Update Gallery)
        // ==========================================
        if (!empty(array_filter($_FILES['additional_images']['name']))) {
            $count = count($_FILES['additional_images']['name']);
            for ($i = 0; $i < $count; $i++) {
                $img_name = $_FILES['additional_images']['name'][$i];
                $img_tmp = $_FILES['additional_images']['tmp_name'][$i];
                $sub_filename = time() . '_extra_' . $i . '.' . pathinfo($img_name, PATHINFO_EXTENSION);

                $sub_query = "INSERT INTO place_images (image_url, P_num) VALUES ('$sub_filename', '$id')";
                if (mysqli_query($conn, $sub_query)) {
                    move_uploaded_file($img_tmp, '../../assets/uploads/places/' . $sub_filename);
                }
            }
        }

        $_SESSION['status'] = "Place updated successfully!";
        header("Location: edit_place.php?id=" . $id); // Return to the same page to see changes
    } else {
        $_SESSION['status'] = "Error Updating: " . mysqli_error($conn);
        header("Location: manage_places.php");
    }
}

// ==========================================
// 3. Delete Single Gallery Image
// ==========================================
if (isset($_POST['delete_gallery_image_btn'])) {
    $img_id = $_POST['img_id'];
    $place_id_ref = $_POST['place_id_ref']; // Needed to redirect back to the edit page

    // Fetch image name to delete from server
    $q = "SELECT image_url FROM place_images WHERE I_ID='$img_id'";
    $res = mysqli_query($conn, $q);
    $row = mysqli_fetch_assoc($res);

    if ($row) {
        $path = '../../assets/uploads/places/' . $row['image_url'];
        if (file_exists($path)) {
            unlink($path); // Delete file from folder
        }

        // Delete record from database
        mysqli_query($conn, "DELETE FROM place_images WHERE I_ID='$img_id'");

        $_SESSION['status'] = "Image deleted from gallery.";
    }
    header("Location: edit_place.php?id=" . $place_id_ref);
}

?>