<?php
session_start();

include '../../includes/db_connect.php';

// 2. Authentication Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "admin/index.php");
    exit();
}
// Check if the category ID is provided in the URL (GET request)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = intval($_GET['id']);
    
    // Use Prepared Statements for secure deletion
    $stmt = $conn->prepare("DELETE FROM categories WHERE C_ID = ?");
    $stmt->bind_param("i", $category_id); // 'i' means the parameter is an integer

    if ($stmt->execute()) {
        $message = "success";
    } else {
        // Deletion failed (e.g., foreign key constraint error if places reference this category)
        $message = "error";
    }

    $stmt->close();
} else {
    // ID was not provided or was invalid
    $message = "invalid_id";
}

// Close the database connection
$conn->close();

// Redirect back to the manage categories page with a status message
header("Location: manage_category.php?status={$message}");
exit();
?>