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
        $stmt->execute();
        
        $directoryPath = "/var/www/html/users/{$user_id}/{$boring_id}";
        
        mkdir($directoryPath, 0755, false);
        
        // Get form data         
        $templatePath = "/var/www/html/template.html"; // Path to your existing HTML template
        $htmlContent = file_get_contents($templatePath); // Read the content of the template file

        // Replace placeholders in the template with dynamic data
        $htmlContent = str_replace("{Project_Name}", $project_name, $htmlContent);
        $htmlContent = str_replace("{Boring_ID}", $boring_id, $htmlContent);
        $htmlContent = str_replace("{Sample_Number}", $sample_number, $htmlContent);
        $htmlContent = str_replace("{Depth}", $depth, $htmlContent);
        $htmlContent = str_replace("{Bag_Tube_Number}", $bag_tube_number, $htmlContent);
        $htmlContent = str_replace("{Test_Name}", $test_name, $htmlContent);
        $htmlContent = str_replace("{Notes}", $notes, $htmlContent);
        $htmlContent = str_replace("{Progress}", $progress, $htmlContent);

        // Write the HTML content to a new file
        $file = fopen("users/{$user_id}/{$boring_id}/{$boring_id}.html", "w");
        fwrite($file, $htmlContent);
        fclose($file);

        include '/var/www/lib/php-qrcode/qrlib.php';
        // URL to encode in QR code
        $url = "http://inngeotech.com/users/{$user_id}/{$boring_id}/{$boring_id}.html";

        // Directory to save the generated QR code image
        $qrCodeDir = "users/{$user_id}/{$boring_id}/";
        
        // File name for the QR code image
        $qrCodeFile =  $qrCodeDir.$boring_id.".png";

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