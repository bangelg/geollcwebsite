<?php

@include 'config.php';

session_start();
if(isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}

if(isset($_GET['logout'])) {
  unset($user_id);
  session_destroy();
  header('location:login.php');
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./index.css" />
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
        
         if($user_id) {
            echo"<h3> $user_id </h3>";
            echo'<div class="logBox">
                  <a href="index.php?logout=<?php echo $user_id; ?>" class="logout">logout</a>
                </div>';
          
          }else{
            echo"<h3> $user_id </h3>";
            echo'<div class="logBox">
                  <a href="index.php?logout=<?php echo $user_id; ?>" class="logout">logout</a>
                </div>';
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
      </main>
    </div>
  </body>
</html>
