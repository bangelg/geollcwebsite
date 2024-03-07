<!DOCTYPE html>
<html>

<head>
	<title>Yeehaw</title>
</head>

<body>
	<center>
		<?php

		// servername => localhost
		// username => root
		// password => empty
		// database name => staff
		$conn = mysqli_connect("wesbitedb.cv2im0m26jl5.us-west-1.rds.amazonaws.com", "admin", "Lester1809nine", "Main");
		
		// Check connection
		if($conn === false){
			die("ERROR: Could not connect. "
				. mysqli_connect_error());
		}
		
		// Taking all 5 values from the form data(input)
		$project_name = $_REQUEST['project_name'];
		$boring_id = $_REQUEST['boring_id$boring_id'];
		$sample_number = $_REQUEST['sample_number'];
		$depth = $_REQUEST['depth'];
		$bag_tube_number = $_REQUEST['bag_tube_number'];
        $test_name = $_REQUEST['test_name'];
		$notes = $_REQUEST['notes'];
		$progress = $_REQUEST['progress'];
		
		// Performing insert query execution
		// here our table name is college
		$sql = "INSERT INTO Samples VALUES ('$project_name', 
			'$boring_id','$sample_number','$depth','$bag_tube_number','$test_name','$notes','$progress')";
		
		if(mysqli_query($conn, $sql)){
			echo "<h3>data stored in a database successfully."
				. " Please browse your localhost php my admin"
				. " to view the updated data</h3>"; 

			echo nl2br("\n$project_name\n $boring_id\n "
				. "$sample_number\n $depth\n $bag_tube_number\n$test_name\n"
                . "$notes\n $progress");
		} else{
			echo "ERROR: Hush! Sorry $sql. "
				. mysqli_error($conn);
		}
		
		// Close connection
		mysqli_close($conn);
		?>
	</center>
</body>

</html>
