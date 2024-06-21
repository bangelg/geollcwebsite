<?php
@include 'config.php';
session_start();

$unique_id = $_GET['Unique_ID'];
$stmt = $conn->prepare("SELECT * FROM Samples WHERE Unique_ID = ?");
$stmt->bind_param("i", $unique_id);
$stmt->execute();
$result = $stmt->get_result();
$sample = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Sample</title> 
</head>
<body>
    <h2>Sample Information</h2>
    <p><strong>Project Name:</strong> <?php echo $sample['Project_Name']; ?></p>
    <p><strong>Boring ID:</strong> <?php echo $sample['Boring_ID']; ?></p>
    <p><strong>Sample Number:</strong> <?php echo $sample['Sample_Number']; ?></p>
    <p><strong>Depth:</strong> <?php echo $sample['Depth']; ?></p>
    <p><strong>Bag Tube/Number:</strong> <?php echo $sample['Bag_Tube_Number']; ?></p>
    <p><strong>Test Name:</strong> <?php echo $sample['Test_Name']; ?></p>
    <p><strong>Notes:</strong> <?php echo $sample['Notes']; ?></p>
    <p><strong>Progress:</strong> <?php echo $sample['Progress']; ?></p>
    <p><strong>Unique ID:</strong> <?php echo $sample['Unique_ID']; ?></p>
    <a href="update.php?Unique_ID=<?php echo $sample['Unique_ID']; ?>">Edit</a>
</body>
</html>
