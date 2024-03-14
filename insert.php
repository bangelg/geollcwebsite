<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ini_set('log_errors', 'On');
    ini_set('error_log', '/path/to/php_errors.log');

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
    
    // Select Query
    $tsql = "SELECT @@Version AS SQL_VERSION";

    // Executes the query
    $stmt = sqlsrv_query($conn, $tsql);

    // Error handling
    if ($stmt === false) {
        die(formatErrors(sqlsrv_errors()));
    }
    // Taking all 8 values from the form data(input)
    $project_name = $_REQUEST['project_name'];
    $boring_id = $_REQUEST['boring_id'];
    $sample_number = $_REQUEST['sample_number'];
    $depth = $_REQUEST['depth'];
    $bag_tube_number = $_REQUEST['bag_tube_number'];
    $test_name = $_REQUEST['test_name'];
    $notes = $_REQUEST['notes'];
    $progress = $_REQUEST['progress'];

    // Performing insert query execution
    // here our table name is college
    try{
    $query = "INSERT INTO Samples (project_name, boring_id, sample_number, depth, bag_tube_number, test_name, notes, progress)
    VALUES (:project_name,:boring_id,:sample_number,:depth,:bag_tube_number,:test_name,:notes,:progress)";
    $statement = $connection->prepare($query);
    $statement->bindValue(':project_name',	$project_name );
    $statement->bindValue(':boring_id', $boring_id);
    $statement->bindValue(':sample_number', $sample_number);
    $statement->bindValue(':depth', $depth);
    $statement->bindValue(':bag_tube_number', $bag_tube_number);
    $statement->bindValue(':test_name', $test_name);
    $statement->bindValue(':notes', $notes);
    $statement->bindValue(':progress', $progress);
    $statement->execute();
    }catch(PDOException $e) {
        echo 'Insert Failed! Code: ' . $e->getMessage();
    }

    echo 'Inserted Data!'
    sqlsrv_close($conn);
    

?>