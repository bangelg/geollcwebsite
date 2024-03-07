<!DOCTYPE html>
<html>

<head>
	<title>Yeehaw</title>
</head>

<body>
	<center>
		<?php
        $serverName = "serverName";
        $options = array(  "UID" => "0E44AAF9-75ED-4E6B-B0F6-FCF5F95C656D",  "PWD" => "Lester1809nine",  "Database" => "Test");
        $conn = sqlsrv_connect($serverName, $options);
        
        if( $conn === false )
             {
             echo "Could not connect.\n";
             die( print_r( sqlsrv_errors(), true));
             }
        
        $name = $_POST['name'];
        $age= $_POST['age'];
        
        $query = "INSERT INTO dbo.small
                (Name_,Age)
                VALUES(?, ?)";
        $params1 = array($name,$age);                       
        $result = sqlsrv_query($conn,$query,$params1);

        echo 'Inserted Data!'
        sqlsrv_close($conn);
		?>
	</center>
</body>

</html>
