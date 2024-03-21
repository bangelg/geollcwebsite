
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
    $conn = mysqli($host, $user, $pass, $dbName);
    if (mysqli_connect_errer()) {
        die('Connect Error(' . mysqli_connect_error(). ')'. mysqli_connect_error());
    } else{
        // Performing insert query execution
        // here our table name is Samples

            // Taking all 8 values from the form data(input)
            $select = "SELECT Boring_ID From Samples Where Boring_ID = ? Limit 1";
            $query = "INSERT INTO Samples (Project_Name, Boring_ID, Sample_Number, Depth, Bag/Tube_Number, Test_Name, Notes, Progress)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param('i', $Boring_ID);
            $stmt-> execute();
            $stmt->bind_result($Boring_ID);
            $stmt->store_result();
            $rnum = $stmt->num_rows;

            if($rnum ==0) {
                $stmt->close();

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param('siisisss',$Project_Name, $Boring_ID, $Sample_Number,
                $Depth, $Bag_Tube_Number, $Test_Name, $Notes, $Progress);
                $stmt->execute();
                echo "New record inserted sucessfully";
            } else {
                echo "This Boring ID is already in the Database./n Please edit the exisitng query.";
            }
            $stmt->close();
            $conn->close();
    } else {
        echo("All field are required.");
        die();
    }

?>