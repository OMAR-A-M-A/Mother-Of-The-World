<?php
// 1. Start the session to access current data
session_start();

// 2. Unset all session variables (removes admin_id, admin_name from memory)
session_unset();

// 3. Destroy the session completely from the server
session_destroy();

// 4. Redirect the user back to the Login page
header("Location: index.php");

// 5. Stop script execution to ensure security
exit();
?>