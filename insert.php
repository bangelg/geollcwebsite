<!DOCTYPE html>
<html>

<head>
	<title>Yeehaw</title>
</head>

<body>
	<center>
		<?php

        $dsn = 'dblib:host=aws-endpoint.rds.amazonaws.com:1433;dbname=Test;';
        $user = 'Admin';
        $password = 'Lester1809nine';
        
        try {
        
        $dbh = new PDO($dsn, $user, $password);
        
        $result = $dbh->query("SELECT * 
            FROM table-name;");
        
        foreach ($result as $row) {
            echo '<pre>';
            print_r($row);
            echo '</pre>';
        }
        
        } catch (PDOException $e) {
        
        echo 'Connection failed: ' . $e->getMessage();
        
        }
		?>
	</center>
</body>

</html>
