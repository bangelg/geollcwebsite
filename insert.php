
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
    if (mysqli_connect_error()) {
        die('Connect Error');
    } else{
        // Performing insert query execution
        // here our table name is Samples

            // Taking all 8 values from the form data(input)
            $query = "INSERT INTO Samples (Project_Name, Boring_ID, Sample_Number, Depth, Bag_Tube_Number, Test_Name, Notes, Progress)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = $conn->prepare($query)){
                $stmt->bind_param("siisisss",$Project_Name, $Boring_ID, $Sample_Number,$Depth, $Bag_Tube_Number, $Test_Name, $Notes, $Progress);
                $stmt->execute();
                echo "New record inserted sucessfully";
            } else {
                echo "Failed to insert";
            }
            $stmt->close();
            $conn->close();
    } 
?>