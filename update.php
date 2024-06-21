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
    if (isset($sample['Parent_Boring_ID'])) {
      $parent_boring_id = $sample['Unique_ID'];
    }
} 

if (isset($_POST['update'])) {
    $location = $_POST['location'];
    $depth = $_POST['depth'];
    $test_name = $_POST['test_name'];
    $notes = $_POST['notes'];
    $progress = $_POST['progress'];
    $discard = isset($_POST['discard']) ? $_POST['discard'] : '';

    // Update the sample information
    $update_query = "UPDATE Samples SET 
                    S_Location='$location',
                    Depth='$depth', 
                    Test_Name='$test_name',
                    Notes='$notes',
                    Progress='$progress'
                    WHERE Unique_ID='$unique_id'";

    if (mysqli_query($conn, $update_query)) {
        if ($discard) {
            // Insert into Discarded table with the current timestamp and store the timestamp
            $discarded_at = date('Y-m-d H:i:s') . ' CST';
            $discard_query = "INSERT INTO Discarded (Unique_ID, Project_Name, Boring_ID, S_Location, Sample_Number, Depth, Bag_Tube_Number, Test_Name, Notes, Progress, User, IGL, Discarded)
                              VALUES ('$unique_id', '{$sample['Project_Name']}', '{$sample['Boring_ID']}', '$location', '{$sample['Sample_Number']}', '$depth', '{$sample['Bag_Tube_Number']}', '$test_name', '$notes', '$progress', '$created_user', '$igl', '$discarded_at')";
            if (!mysqli_query($conn, $discard_query)) {
                echo "Error inserting record into Discarded table: " . mysqli_error($conn);
                exit;
            }

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
        $parent_link = getParentLinkHTML($sample['Boring_ID'], $created_user, $igl);
        $children_html = getChildrenHTML($unique_id, $created_user, $sample['Boring_ID'], $igl);

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
                <p><strong>Project name:</strong> {$sample['Project_Name']}</p>
                <p><strong>Boring ID:</strong> {$sample['Boring_ID']}</p>
                <p><strong>Sample number:</strong> {$sample['Sample_Number']}</p>
                <p><strong>Depth:</strong> $depth</p>
                <p><strong>Bag/Tube number:</strong> {$sample['Bag_Tube_Number']}</p>
                <p><strong>Test name:</strong> $test_name</p>
                <p><strong>Storage location:</strong> $location</p>
                <p><strong>Notes:</strong> $notes</p>
                <p><strong>Progress:</strong> $progress</p>
                <p><strong>Unique ID:</strong> $unique_id</p>
                <p><strong>Discarded:</strong> " . ($discarded ? 'Yes' : 'No');
                if ($discarded) {
                    $sample_content .= " at: $discarded_at </p>";
                } else {
                    $sample_content .= "</p>";
                }
                $sample_content .= "
                $parent_link
                $children_html
                <a href='/update.php?Unique_ID=$unique_id' class='edit'>Edit</a>
            </div>
        </main>
        </body>
        </html>";

        if (file_put_contents($sample_page, $sample_content) === false) {
            echo "Error writing to file: {$sample_page}";
        } else {
            header("Location: users/{$created_user}/{$unique_id}/{$unique_id}.html");
            exit();
        }
    } else {
        echo "Error updating record: " . mysqli_error($conn);
        exit();
    }
}

function getParentLinkHTML($boring_id, $user_id, $igl) {
    global $conn;
    $parent_boring_id = substr($boring_id, 0, strrpos($boring_id, '-'));
    $parent_html = '';
    $query = "SELECT * FROM Samples WHERE Boring_ID = '$parent_boring_id' AND IGL = '$igl'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $parent_sample = mysqli_fetch_assoc($result);
        $parent_unique_id = $parent_sample['Unique_ID'];
        $parent_html .= "<p><strong>Parent:</strong> <a href='/users/$user_id/$parent_unique_id/$parent_unique_id.html'>$parent_boring_id</a></p>";
    }
    return $parent_html;
}

function getChildrenHTML($unique_id, $user_id, $boring_id, $igl) {
  global $conn;
  $children_html = '';
  $query = "SELECT * FROM Samples WHERE Parent_Boring_ID = '$boring_id' AND IGL = '$igl'";
  $result = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($result) > 0) {
      while ($child_sample = mysqli_fetch_assoc($result)) {
          $child_boring_id = $child_sample['Boring_ID'];
          $child_unique_id = $child_sample['Unique_ID'];
          if ($child_unique_id != $unique_id) {
              $children_html .= "<p><strong>Children:</strong> <a href='/users/$user_id/$child_unique_id/$child_unique_id.html'>$child_boring_id</a></p>";
          }
      }
  } 
  return $children_html;
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
        <a href="https://www.inngeotech.com">
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
          <label>Project name:</label></div>
        <div class="input-tab">
          <p class="static-field"><?php echo $sample['Project_Name']; ?></p></div>
        <div class="labels">
          <label>Boring ID:</label></div>
        <div class="input-tab">
          <p class="static-field"><?php echo $sample['Boring_ID']; ?></p></div>
        <div class="labels">
          <label>Sample number:</label></div>
        <div class="input-tab">
          <p class="static-field"><?php echo $sample['Sample_Number']; ?></p></div>
        <div class="labels">
          <label>Bag/Tube number:</label></div>
        <div class="input-tab">
          <p class="static-field"><?php echo $sample['Bag_Tube_Number']; ?></p></div>
        <div class="labels">
          <label for="LDepth">Depth:</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LDepth" name="depth" placeholder="Enter the depth."
          value="<?php echo $sample['Depth']; ?>" required autofocus></div>
        <div class="labels">
          <label for="testName">Test name:</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="testName" name="test_name" placeholder="Enter the test name." 
          value="<?php echo $sample['Test_Name']; ?>" required autofocus></div>
        <div class="labels">
          <label for="LLocation">Storage location:</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LLocation" name="location" placeholder="Enter the storage location." 
          value="<?php echo $sample['S_Location']; ?>" required autofocus></div>
        <div class="labels">
          <label for="LNotes">Notes:</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LNotes" name="notes" placeholder="Enter any notes." 
          value="<?php echo $sample['Notes']; ?>" required autofocus></div>
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
