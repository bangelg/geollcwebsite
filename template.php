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
        <a href="update.php?sample_id=<?php echo $sample['unique_id']; ?>" class="edit-button">Edit</a>
      </div>
    </main>
    </body>
</div>
</html>
