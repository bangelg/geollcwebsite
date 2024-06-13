<?php
    function exception_handler($exception) {
        echo "<h1>Failure</h1>";
        echo "Uncaught exception: " , $exception->getMessage();
        echo "<h1>PHP Info for troubleshooting</h1>";
        phpinfo();
        }

        set_exception_handler('exception_handler');

    // Establishes the connection
    @include 'config.php';
    session_start();
    $user_id = $_SESSION['user_id'];  

    
    if ($conn->connect_error) {
        die('Connect Error: ' . $conn->connect_error);
    } else {
        // Performing insert query execution
        // here our table name is Samples

        // Taking all 8 values from the form data(input)
    
        $stmt = $conn->prepare("INSERT INTO Samples (Project_Name, Boring_ID, Sample_Number, Depth, Bag_Tube_Number, Test_Name, Notes, Progress)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisisss",$project_name, $boring_id, $sample_number,
        $depth, $bag_tube_number, $test_name, $notes, $progress);

        $project_name = $_REQUEST['project_name'];
        $boring_id = $_REQUEST['boring_id'];
        $sample_number = $_REQUEST['sample_number'];
        $depth = $_REQUEST['depth'];
        $bag_tube_number = $_REQUEST['bag_tube_number'];
        $test_name = $_REQUEST['test_name'];
        $notes = $_REQUEST['notes'];
        $progress = $_REQUEST['progress'];
       

        if ($stmt->execute() == TRUE) {
            $unique_id = $conn->insert_id;
            $_SESSION['unique_id'] = $unique_id;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
       $directoryPath = "/var/www/html/users/{$user_id}/{$unique_id}";
        
        mkdir($directoryPath, 0755, false);
        
        $sample_page = "users/$user_id/$unique_id/$unique_id.html";
        $sample_content = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Sample $unique_id</title>
            <link rel='stylesheet' href='./global.css'>
            <link rel='stylesheet' href='./template.css'>
        </head>
        <body>
            <h2>Sample Information</h2>
            <p><strong>Project Name:</strong> $project_name</p>
            <p><strong>Boring ID:</strong> $boring_id</p>
            <p><strong>Sample Number:</strong> $sample_number</p>
            <p><strong>Depth:</strong> $depth</p>
            <p><strong>Bag Tube/Number:</strong> $bag_tube_number</p>
            <p><strong>Test Name:</strong> $test_name</p>
            <p><strong>Notes:</strong> $notes</p>
            <p><strong>Progress:</strong> $progress</p>
            <a href='update.php?unique_id=$unique_id' class='edit-button'>Edit</a>
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
        $recent = "users/{$user_id}/recent/" . "recent.png";

        // Generate QR code
        QRcode::png($url, $qrCodeFile);

        copy($qrCodeFile, $recent);
     
        
        $stmt->close();
        $conn->close();
        header("Location: results.php");
        
    }
?>
