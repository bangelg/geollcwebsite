<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $username = mysqli_real_escape_string($conn, $_POST['usernameInp']);
   $pass = hash('sha256',$_POST['passwordInp']);

   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$username' AND passwrd = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['username'];
      
      header('location:/index.php');
      exit();
   } else{
      $error[] = 'incorrect username or password!';
   }

};
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./login.css" />
    <title>Login Page</title>
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
<form action="" method = 'POST'>
    <div class="container">
        <h1 id="title">Login</h1>
        <div class="labels">
        <?php
        if (isset($error)) {
            foreach ($error as $err) {
                echo "<p style='color:red;'>$err</p>";
            }
        }
        ?>
            <span class="icon">
                <ion-icon name="mail"></ion-icon>
            </span>
            <label for="username">Username: </label>
        </div> 
        <div class="input-tab">
            <input class="input-field"type="string" name="usernameInp" required placeholder="Enter your username"><br><br>
        </div>
       <div class="labels">
            <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
            </span>
            <label for="password">Password: </label>    
       </div>
        <div class="input-tab">
            <input class="input-field"type="password" name="passwordInp" required placeholder="Enter your password"><br><br>    
        </div>
       

        <div class="labels">
            <label for="login">Don't have an Account? </label> 
 
        </div> 
        <div class="input-tab">
            <div class="input-field">
                <div class="login-btn">         
                    <a href="register.php"> Register</a>
            </div>
        </div>

        </div>
        <div class="btn">
            <input type="submit" class='sub'name="submit" value="Submit">
        </div>
       
    </div>
</form>
</body>
</html>

