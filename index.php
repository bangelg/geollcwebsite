<?php

@include 'config.php';

session_start();
$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="css/global.css" />
    <link rel="stylesheet" href="css/index.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
    />
    <title>Homepage</title>
  </head>
  <body>
  </head>
  <body >
    <div class="landing-page">
      <header class="rectangle-group">
        <div class="frame-item"></div>
        <img
          class="frame-inner"
          loading="lazy"
          alt=""
          src="./igtech-logo-transparent.png"
        />
        <?php 
         $select = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$user_id'") or die('query failed');
         
         if(mysqli_num_rows($select) > 0){
            echo '<h3> Not Logged in. </h3>';
          }else{
            echo'<h3> {$user_id} </h3>';
         }
        ?>
      </header>
      <main class="rectangle-container">
  
        <div class="rectangle-div"></div>
        <section class="frame-3default">
          <a href="QRGen.html">
            <b style='color:white' class="create-qr">Create QR</b>
          </a>
        </section>
        <section class="frame-4default">
          <a href="scan.html">
            <b style = "color:white"class="scan-qr">Scan QR</b>
          </a>
        </section>
        <section class="register">
          <a href="register.php">
            <b style = "color:white"class="scan-qr">Register</b>
          </a>
        </section>
      </main>
    </div>
  </body>
</html>
