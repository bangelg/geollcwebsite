<?php
function exception_handler($exception) {
    echo "<h1>Failure</h1>";
    echo "Uncaught exception: " , $exception->getMessage();
    echo "<h1>PHP Info for troubleshooting</h1>";
    phpinfo();
}

set_exception_handler('exception_handler');

// Establish the connection
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if ($conn->connect_error) {
    die('Connect Error: ' . $conn->connect_error);
} else {
    // Determine if this boring ID is a subsidiary
    $parent_boring_id = null;
    $boring_id = $_REQUEST['boring_id'];
    if (strpos($boring_id, '-') !== false) {
        $parent_boring_id = explode('-', $boring_id)[0];
    }

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO Samples (IGL, Project_Name, Boring_ID, S_Location, Sample_Number, Depth, Bag_Tube_Number, Test_Name, Notes, Progress, User, Parent_Boring_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssssi", $igl, $project_name, $boring_id, $location, $sample_number, $depth, $bag_tube_number, $test_name, $notes, $progress, $user_id, $parent_boring_id);

    $igl = $_REQUEST['igl'];
    $project_name = $_REQUEST['project_name'];
    $location = $_REQUEST['location'];
    $sample_number = $_REQUEST['sample_number'];
    $depth = $_REQUEST['depth'];
    $bag_tube_number = $_REQUEST['bag_tube_number'];
    $test_name = $_REQUEST['test_name'];
    $notes = $_REQUEST['notes'];
    $progress = $_REQUEST['progress'];

    if ($stmt->execute() == TRUE) {
        $unique_id = $conn->insert_id;
        $_SESSION['unique_id'] = $unique_id;
        $directoryPath = "/var/www/html/users/{$user_id}/{$unique_id}";
        mkdir($directoryPath, 0755, true);

        $parent_link = '';

        if ($parent_boring_id) {
            // Fetch the parent sample to get the file path
            $query = "SELECT * FROM Samples WHERE Boring_ID = '$parent_boring_id' AND IGL = '$igl'";
            $result = mysqli_query($conn, $query);
            if ($parent_sample = mysqli_fetch_assoc($result)) {
                $parent_unique_id = $parent_sample['Unique_ID'];
                $parent_user = $parent_sample['User'];
                $parent_link = "<p><strong>Parent:</strong> <a href='/users/$parent_user/$parent_unique_id/$parent_unique_id.html'>$parent_boring_id</a></p>";
                
                // Update parent HTML file with link to this child
                updateParentHTML($parent_user, $parent_unique_id, $boring_id, $unique_id, $user_id);
            }
        }
        
        $sample_page = "users/$user_id/$unique_id/$unique_id.html";
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
                <img class='frame-inner' loading='lazy' alt='' src='/igtech-logo-transparent.png'/>
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
            <p><strong>Discarded:</strong> No</p>
            $parent_link
            <div id='children'style='display: none;'>
                <p>hidden</p>
            </div>
            <a href='/update.php?Unique_ID=$unique_id' class='edit'>Edit</a>
        </div>
        </main>
        </body>
        </html>
        ";

        file_put_contents($sample_page, $sample_content);

        include '/var/www/lib/phpqrcode/qrlib.php';
        // URL to encode in QR code
        $url = "http://inngeotech.com/users/{$user_id}/{$unique_id}/{$unique_id}.html";

        // Directory to save the generated QR code image
        $qrCodeDir = "users/{$user_id}/{$unique_id}/";

        // File name for the QR code image
        $qrCodeFile =  $qrCodeDir.$unique_id.".png";

        // File location for recent to refer to when printing
        $recent = "users/{$user_id}/recent/recent.png";

        // Generate QR code
        QRcode::png($url, $qrCodeFile);

        copy($qrCodeFile, $recent);

        updateGoogleSheet($unique_id, $igl, $project_name, $boring_id, $location, $sample_number, $depth, $bag_tube_number, $test_name, $notes, $progress, $user_id);

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: results.php");
}

function updateParentHTML($parent_user, $parent_unique_id, $boring_id, $unique_id, $user_id) {
    $parent_file_path = "/var/www/html/users/{$parent_user}/{$parent_unique_id}/{$parent_unique_id}.html";
    $child_link = "<p><a href='/users/{$user_id}/$unique_id/$unique_id.html'>$boring_id</a></p>";

    // Update the parent HTML file
    if (file_exists($parent_file_path)) {
        $parent_html = file_get_contents($parent_file_path);

        // Check if the child link already exists to prevent duplication
        if (strpos($parent_html, $child_link) === false) {
            if (strpos($parent_html, '<!-- CHILD LINKS -->') !== false) {
                // Add the child link before the closing comment
                $parent_html = str_replace('<!-- CHILD LINKS -->', $child_link . '<!-- CHILD LINKS -->', $parent_html);
                // Ensure the section is visible
            } else {
                // Add a new section for child links
                $parent_html = str_replace('<p>hidden</p>', "<strong>Children:</strong>$child_link<!-- CHILD LINKS -->", $parent_html);
                $parent_html = str_replace("id='children'style='display: none;'", 'id="children"', $parent_html);
            }
            file_put_contents($parent_file_path, $parent_html);
        }
    }
}
function updateGoogleSheet($unique_id, $igl, $project_name, $boring_id, $location, $sample_number, $depth, $bag_tube_number, $test_name, $notes, $progress, $user_id) {
    $url = 'https://script.google.com/macros/s/AKfycbyEuS2AxH_Zte_9-I5xsxi4Th8qbS0OjpWndf5jXBdBUdCMfULfEE09lO4RJ-Q_Go0v/exec'; // Replace with your web app URL

    $data = [
        'Unique_ID' => $unique_id,
        'IGL' => $igl,
        'Project_Name' => $project_name,
        'Boring_ID' => $boring_id,
        'S_Location' => $location,
        'Sample_Number' => $sample_number,
        'Depth' => $depth,
        'Bag_Tube_Number' => $bag_tube_number,
        'Test_Name' => $test_name,
        'Notes' => $notes,
        'Progress' => $progress,
        'User_ID' => $user_id,
        'Action' => 'insert' // Set action to insert
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        error_log("Error sending data to Google Sheets");
    }

    return $result;
}

?>
