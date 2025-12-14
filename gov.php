<?php
include "includes/db_connect.php";
if (!$conn) {
    die("DB not connected");
}

$query = "SELECT * FROM governorates";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Governorates</title>
    <link rel="stylesheet" href="assets/css/gov.css">
</head>
<body>


<!-- Header Image with Page Title -->
<div class="header-image">
    <img src="assets/images/hero.jpg" alt="Hurghada" class="header-img">
    <h1 class="page-title">Egypt Governorates</h1>
</div>


<div class="cards-container">
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="card">
           <img src="assets/uploads/governorates/<?php echo $row['image_url']; ?>" 
                alt="<?php echo $row['G_name']; ?>">
            <div class="card-content">
                <h3><?php echo $row['G_name']; ?></h3>
                <p>
                    <?php echo substr($row['G_description'], 0, 120); ?>...
                </p>
                <a href="gov-details.php?id=<?php echo $row['G_ID']; ?>" class="btn">
                    View Details
                </a>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>
