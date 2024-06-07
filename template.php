<?php
@include 'config.php';
session_start();

if(isset($_POST['submit'])) {
    $project_name = $_POST['project_name'];
    $boring_id = $POST['boring_id'];
    $sample_number = $_POST['sample_number'];
    $depth = $_POST['depth'];
    $bag_tube_number = $_POST['bag_tube_number'];
    $test_name = $_POST['test_name'];
    $notes = $_POST['notes'];
    $progress = $_POST['progress'];
    $unique_id = $_POST['$unique_id'];

    header("location:update.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Information</title>
    <link rel="stylesheet" href="./global.css">
    <link rel="stylesheet" href="./template.css">
  </head>

  <body>
  <script>
        function editSample() {
          window.location.href = "update.php";
      }
    </script>
    <header class="rectangle-group">
        <div class="frame-item"></div> 
        <a href = "https://www.inngeotech.com" >
            <img
                class="frame-inner"
                loading="lazy"
                alt=""
                src="./igtech-logo-transparent.png"
            />
        </a>
    </header>
    <main>
      <div class="soil-sample">
        <h2>Sample Information</h2>
        <p><strong>Project Name:</strong> {Project_Name}</p>
        <p><strong>Boring ID:</strong> {Boring_ID}</p>
        <p><strong>Sample Number:</strong> {Sample_Number}</p>
        <p><strong>Depth:</strong> {Depth}</p>
        <p><strong>Bag Tube/Number:</strong> {Bag_Tube_Number}</p>
        <p><strong>Test Name:</strong> {Test_Name}</p>
        <p><strong>Notes:</strong> {Notes}</p>
        <p><strong>Progress:</strong> {Progress}</p>
        <p><strong>Unique ID:</strong> {Unique_ID}</p>

        <button onclick ="editSample()"  name="edit" class="edit">Edit</button>
      </div>
    </main>
    
  </body>
</div>
</html>
