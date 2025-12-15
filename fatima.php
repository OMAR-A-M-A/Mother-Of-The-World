<?php include 'config.php';
include 'includes/db_connect.php';

//FetchCategories for Display
$categories = [];
$fetch_sql = "SELECT * FROM categories ORDER BY C_ID DESC LIMIT 3";
$result = $conn->query($fetch_sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    $result->free();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- font awesome -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/all.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>






    <!-- start footer section -->
    <!-- end footer section -->

    <!-- bootstrap js -->
    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>