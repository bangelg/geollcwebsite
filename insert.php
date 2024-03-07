<!DOCTYPE html>
<html>

<head>
	<title>Yeehaw</title>
</head>

<body>
	<center>
		<?php

        $server = 'wesbitedb.cv2im0m26jl5.us-west-1.rds.amazonaws.com';
        $database = 'Main';
        $username = 'admin';
        $password = 'Lester1809nine';

		$conn = new PDO("sqlsrv:server=$server;Database=$database", $username, $password);
		
		// Check connection
		if($conn === false){
			die("ERROR: Could not connect. "
				. mysqli_connect_error());
		}
		
		// Taking all 5 values from the form data(input)
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
		

		?>
	</center>
</body>

</html>
