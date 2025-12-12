<?php
session_start();
include '../../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = intval($_GET['id']);

    // 1. Get Image Name First
    $query_img = "SELECT C_image FROM categories WHERE C_ID = $category_id";
    $result_img = mysqli_query($conn, $query_img);
    $row_img = mysqli_fetch_assoc($result_img);

    // 2. Delete from DB
    $stmt = $conn->prepare("DELETE FROM categories WHERE C_ID = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        // 3. Delete File from Server if exists
        if ($row_img && !empty($row_img['C_image'])) {
            $file_path = "../../assets/uploads/categories/" . $row_img['C_image'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $message = "success";
    } else {
        $message = "error";
    }
    $stmt->close();
} else {
    $message = "invalid_id";
}

$conn->close();
header("Location: manage_category.php?status={$message}");
exit();
?>