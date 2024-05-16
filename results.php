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
    <link rel="stylesheet" href="css/results.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Lohit Devanagari:wght@400&display=swap"
    />
  </head>
  <body>
    <div class="sample-saved">
      <section class="sample-saved-child"></section>
      <div class="your-new-sample">
        Your new sample has been<br> saved successfully.
      </div>
      <button class="print-qr-sticker-wrapper" id="frameButton">
        <div class="print-qr-sticker">Print QR Sticker</div>
      </button>
      <button class="return-to-home-wrapper" id="frameButton1">
        <div class="return-to-home">Return to Home</div>
      </button>
    </div>
    <div class="qrCode">
      <?php
        echo '<img src="users/' . $user_id . '/recent/recent.png" alt="qrCode"/>'
        ?>
    </div>
    
    <script>
      
      var frameButton = document.getElementById("frameButton");
      if (frameButton) {
        frameButton.addEventListener("click", function (e) {
            window.print();
        });
      }
      
      var frameButton1 = document.getElementById("frameButton1");
      if (frameButton1) {
        frameButton1.addEventListener("click", function (e) {
          window.location.href = "./index.php";
        });
      }

      </script>
  </body>
</html>
