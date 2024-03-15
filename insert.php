<?php
    $serverName = "wesbitedb.cv2im0m26jl5.us-west-1.rds.amazonaws.com";
    $connectionOptions = array(
        "database" => "Main",
        "uid" => "Admin",
        "pwd" => "Lester1809nine"
    );

    function exception_handler($exception) {
        echo "<h1>Failure</h1>";
        echo "Uncaught exception: " , $exception->getMessage();
        echo "<h1>PHP Info for troubleshooting</h1>";
        phpinfo();
        }

        set_exception_handler('exception_handler');

    // Establishes the connection
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(formatErrors(sqlsrv_errors()));
    }
        

    // Performing insert query execution
    // here our table name is Samples
    try{

    // Taking all 8 values from the form data(input)
    $project_name = $_REQUEST['project_name'];
    $boring_id = $_REQUEST['boring_id'];
    $sample_number = $_REQUEST['sample_number'];
    $depth = $_REQUEST['depth'];
    $bag_tube_number = $_REQUEST['bag_tube_number'];
    $test_name = $_REQUEST['test_name'];
    $notes = $_REQUEST['notes'];
    $progress = $_REQUEST['progress'];
    
    $query = "INSERT INTO Samples (project_name, boring_id, sample_number, depth, bag_tube_number, test_name, notes, progress)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($project_name, $boring_id, $sample_number, $depth, $bag_tube_number, $test_name, $notes, $progress);

    $stmt = sqlsrv_query($conn, $query, $params);

    } catch(PDOException $e) {
        echo 'Insert Failed! Code: ' . $e->getMessage();
    }
    if ($stmt == false) {
        die(print_r(sqlsrv_errors(), true)); 
    } else {
        echo 'Inserted Data!';
    }

    sqlsrv_close($conn);
    

?>