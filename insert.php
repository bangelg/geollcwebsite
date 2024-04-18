<!DOCTYPE html>
<html lang="en">
    <title> QR Code </title>
    <head>Product Information:</head>
    <body>
    <?php
        function exception_handler($exception) {
            echo "<h1>Failure</h1>";
            echo "Uncaught exception: " , $exception->getMessage();
            echo "<h1>PHP Info for troubleshooting</h1>";
            phpinfo();
            }

            set_exception_handler('exception_handler');

        // Establishes the connection
        $host = "igtdb.cv2im0m26jl5.us-west-1.rds.amazonaws.com";
        $user = 'admin';
        $pass = 'Lester1809nine';
        $dbName = 'Main';
        $conn = mysqli_connect($host, $user, $pass, $dbName);
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
            
            echo "New records created successfully";
            
        
            $directoryPath = "/var/www/html/samples/{$boring_id}";

            if (file_exists($directoryPath)) {
                echo "file exists";
            } else {
                mkdir($directoryPath, 0755, false);
            }
            
        
            $stmt->close();
            $conn->close();

            
        } 
    ?>
    <div id = "results"></div>
    <a href="/QRGen.html">Back to Form</a><br>
    <button onclick="generateQRCode()">Generate Code</button>
    <div id = "qrcode"></div>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/qrcode.min.js"></script>
    <script>
        function generateQRCode() {
            // Get form data
            
            $templatePath = "template.html"; // Path to your existing HTML template
            $htmlContent = file_get_contents($templatePath); // Read the content of the template file

            // Replace placeholders in the template with dynamic data
            $htmlContent = str_replace("{projectName}", $projectName, $htmlContent);
            $htmlContent = str_replace("{boringId}", $boringId, $htmlContent);

            // Write the HTML content to a new file
            $file = fopen("samples/$boringId.html", "w");
            fwrite($file, $htmlContent);
            fclose($file);

            
            // Generate QR code
            var qr = new QRCode(document.getElementById("qrcode"), {
                text: htmlContent,
                width: 256,
                height: 256,
            });
        }
    </script>
</body>
</html>