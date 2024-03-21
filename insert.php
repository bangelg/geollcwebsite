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


        if ($conn->query($insert) == TRUE){
            echo "New record inserted sucessfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        } 
    
        $stmt->close();
        $conn->close();
    } 
?>