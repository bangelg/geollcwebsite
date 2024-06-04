<?php
session_start();
@include("config.php");
@include("checkSession.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
	<link rel="stylesheet" href="./global.css">
	<link rel="stylesheet" href="./scan.css">
    
</head>
<body>
    <header class="rectangle-group">
        <div class="frame-item"></div> 
        <a href = "index.php" >
            <img
                class="frame-inner"
                loading="lazy"
                alt=""
                src="./igtech-logo-transparent.png"
            />
        </a>
    </header>

    <div id="scanner-container">
        <div id="reader"></div>
        <button id="start-scan">Start Scan</button>
        <button id="stop-scan">Stop Scan</button>
    </div>

	<script
		src="https://unpkg.com/html5-qrcode">
	</script>
    <script src="scan.js"></script>
</body>
</html>

