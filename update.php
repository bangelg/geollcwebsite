<?php
@include 'config.php';
date_default_timezone_set('America/Chicago');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

if (isset($_GET['Unique_ID'])) {
    $unique_id = $_GET['Unique_ID'];
    $query = "SELECT * FROM Samples WHERE Unique_ID = '$unique_id'";
    $result = mysqli_query($conn, $query);
    $sample = mysqli_fetch_assoc($result);
    $created_user = $sample['User'];
    $igl = $sample['IGL'];
}

if (isset($_POST['update'])) {
    $project_name = $_POST['project_name'];
    $boring_id = $_POST['boring_id'];
    $location = $_POST['location'];
    $sample_number = $_POST['sample_number'];
    $depth = $_POST['depth'];
    $bag_tube_number = $_POST['bag_tube_number'];
    $test_name = $_POST['test_name'];
    $notes = $_POST['notes'];
    $progress = $_POST['progress'];
    $discard = isset($_POST['discard']) ? $_POST['discard'] : '';

    // Update the sample information
    $update_query = "UPDATE Samples SET 
                    Project_Name='$project_name', 
                    Boring_ID='$boring_id', 
                    S_Location='$location',
                    Sample_Number='$sample_number', 
                    Depth='$depth', 
                    Bag_Tube_Number='$bag_tube_number', 
                    Test_Name='$test_name',
                    Notes='$notes',
                    Progress='$progress'
                    WHERE Unique_ID='$unique_id'";

    if (mysqli_query($conn, $update_query)) {
        if ($discard) {
            // Insert into Discarded table with the current timestamp and store the timestamp
            $discarded_at = date('Y-m-d H:i:s');
            $discard_query = "INSERT INTO Discarded (Unique_ID, Project_Name, Boring_ID, S_Location, Sample_Number, Depth, Bag_Tube_Number, Test_Name, Notes, Progress, User, IGL, Discarded)
                              VALUES ('$unique_id', '$project_name', '$boring_id', '$location', '$sample_number', '$depth', '$bag_tube_number', '$test_name', '$notes', '$progress', '$created_user', '$igl', '$discarded_at')";
            if (!mysqli_query($conn, $discard_query)) {
                echo "Error inserting record into Discarded table: " . mysqli_error($conn);
                exit;
            }

            // Store the current timestamp in a variable
            // Remove from Samples table
            $remove_query = "DELETE FROM Samples WHERE Unique_ID='$unique_id'";
            if (!mysqli_query($conn, $remove_query)) {
                echo "Error deleting record from Samples table: " . mysqli_error($conn);
                exit;
            }

            $discarded = true;
        } else {
            $discarded = false;
        }

        // Update or create the sample page
        $sample_page = "users/{$created_user}/{$unique_id}/{$unique_id}.html";
        $sample_content = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Sample $unique_id</title>
            <link rel='stylesheet' href='/css/global.css'>
            <link rel='stylesheet' href='/css/template.css'>
        </head>
        <body>
        <header class='rectangle-group'>
            <div class='frame-item'></div>
            <a href='https://www.inngeotech.com'>
                <img class='frame-inner'
                     loading='lazy'
                     alt=''
                     src='/igtech-logo-transparent.png'/>
            </a>
        </header>
        <main>
            <div class='soil-sample'>
                <h2>Sample Information</h2>
                <p><strong>IGL:</strong> $igl</p>
                <p><strong>Project name:</strong> $project_name</p>
                <p><strong>Boring ID:</strong> $boring_id</p>
                <p><strong>Sample number:</strong> $sample_number</p>
                <p><strong>Depth:</strong> $depth</p>
                <p><strong>Bag/Tube number:</strong> $bag_tube_number</p>
                <p><strong>Test name:</strong> $test_name</p>
                <p><strong>Storage location:</strong> $location</p>
                <p><strong>Notes:</strong> $notes</p>
                <p><strong>Progress:</strong> $progress</p>
                <p><strong>Unique ID:</strong> $unique_id</p>
                <p><strong>Discarded:</strong> " . ($discarded ? 'Yes' : 'No');
                if ($discarded) {
                    $sample_content .= " at: $discarded_at CST </p>";
                } else {
                    $sample_content .= "</p>";
                }
                $sample_content .= "
                <a href='/update.php?Unique_ID=$unique_id' class='edit'>Edit</a>
            </div>
        </main>
        </body>
        </html>";

        if (file_put_contents($sample_page, $sample_content) === false) {
            echo "Error writing to file: {$sample_page}";
        } else {
            header("Location:users/{$created_user}/{$unique_id}/{$unique_id}.html");
            exit();
        }
    } else {
        echo "Error updating record: " . mysqli_error($conn);
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sample</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/template.css">
    <script>
        function confirmDiscard(event) {
            var discardCheckbox = document.getElementById('discard');
            if (discardCheckbox.checked) {
                var confirmation = confirm("Are you sure you want to discard this sample?");
                if (!confirmation) {
                    event.preventDefault(); // Prevent form submission if the user cancels
                }
            }
        }
    </script>
</head>
<body>
<header class="rectangle-group">
        <div class="frame-item"></div> 
        <a href = "https://www.inngeotech.com" >
            <img
                class="frame-inner"
                loading="lazy"
                alt=""
                src="/igtech-logo-transparent.png"
            />
        </a>
    </header>
    <main>
    <div class="soil-sample">

        <h2>Edit Sample</h2>
        <form action="" method="POST" onsubmit="confirmDiscard(event)">
        <div class="labels">
          <label id="name-label" for="projectName"> Project name: </label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="projectName" name="project_name" placeholder="Enter the project name."
           value="<?php echo $sample['Project_Name']; ?>"required autofocus></div>
        <div class="labels">
          <label id="number-label" for="boringId"> Boring ID: </label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="boringId" name="boring_id"placeholder="1500..." 
          value="<?php echo $sample['Boring_ID']; ?>"required></div>
        <div class="labels">
          <label id="name-label" for="sampleNumber"> Sample number: </label></div>
        <div class="input-tab">              
          <input class="input-field" type="text" id="sampleNumber" name="sample_number" placeholder="Enter the sample number." 
          value="<?php echo $sample['Sample_Number']; ?>"required autofocus></div>
        <div class="labels">
          <label id="name-label" for="LDepth"> Depth: </label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LDepth" name="depth" placeholder="Enter the depth."
          value="<?php echo $sample['Depth']; ?>" required autofocus></div>
        <div class="labels">
          <label id="name-label" for="bag/tubeNumber"> Bag/Tube number: </label></div>
        <div class="input-tab">
          <input class="input-field" type="number" id="bag/tubeNumber" name="bag_tube_number" placeholder="Enter the bag/tube number." 
          value="<?php echo $sample['Bag_Tube_Number']; ?>"required autofocus></div>
        <div class="labels">
          <label id="name-label" for="testName"> Test name: </label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="testName" name="test_name" placeholder="Enter the test name." 
          value="<?php echo $sample['Test_Name']; ?>"required autofocus></div>
        <div class="labels">
          <label id="name-label" for="LLocation"> Storage location: </label></div>
        <div class="input-tab">              
          <input class="input-field" type="text" id="LLocation" name="location" placeholder="..." 
          value="<?php echo $sample['S_Location']; ?>"required autofocus></div>
        <div class="labels">
          <label id="name-label" for="LNotes"> Notes: </label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LNotes" name="notes" placeholder="Enter any notes." 
          value="<?php echo $sample['Notes']; ?>"required autofocus></div>
        <div class="labels">
          <label>Progress?</label></div>
        <div class="input-tab">
            <input type="radio" name="progress" value="Not Started" class="radio-button" <?php echo ($sample['Progress'] == 'Not Started') ? 'checked' : ''; ?> required>Not Started<br>
            <input type="radio" name="progress" value="In Progress" class="radio-button" <?php echo ($sample['Progress'] == 'In Progress') ? 'checked' : ''; ?> required>In Progress<br>
            <input type="radio" name="progress" value="Completed" class="radio-button" <?php echo ($sample['Progress'] == 'Completed') ? 'checked' : ''; ?> required>Completed<br>
        </div>
        <div class="input-tab">
            <input type="checkbox" id="discard" name="discard" class="radio-button"> Discard Sample
        </div>
            <button type="submit" name="update">Update</button>
        </form>
    
    </div>
    </main>
</body>
</html>