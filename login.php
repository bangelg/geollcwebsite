<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $username = mysqli_real_escape_string($conn, $_POST['usernameInp']);
   $pass = hash('sha256',$_POST['passwordInp']);
   $cpass = hash('sha256',$_POST['cpasswordInp']);

   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$username' AND passwrd = '$pass'") or die('query failed');

   if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['id'];
      
      $row = mysqli_fetch_array($result);

      header('location:../index.php');
     
   } elseif($pass != $cpass) {
        $error[] = 'password not matched!';
   }
    else{
      $error[] = 'incorrect email or password!';
   }

};
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/global.css" />
    <link rel="stylesheet" href="../css/login.css" />
    <title>Login Page</title>
    
    </head>
    <body>
    <div class="wrapper">
        <div class="form-wrapper">
            <h1>Login Page</h1>
            <form action="" method="post">
                <?php
                    if(isset($error)){
                        foreach($error as $error){
                            echo '<span class="error-msg">'.$error.'</span>';
                        };
                    };
                ?>
                <div class="input">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <label for="username">Username: </label>
                    <input type="string" name="usernameInp" required placeholder="Enter your username"><br><br>
                </div>
                <div class="password">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <label for="password">Password: </label>
                    <input type="password" name="passwordInp" required placeholder="Enter your password"><br><br>
                </div>
                <div class="input">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                  
                    <label for="cpassword">Confirm Password: </label>
                    <input type="password" name="cpasswordInp" required placeholder="Confirm your password"><br><br>
                </div>
                <div class="remember-forgot">
                    <label>Remember Me</label>
                    <input type="checkbox"><br><br>

                    <a href="#"> Forgot Password?</a>
                </div>

                <div class="login-register">
                    <p>Don't have an account?
                        <a href="register.php"> Register</a>
                    </p>
                </div>
                <input type="submit" name="submit" value="Log In">
            </form>
        </div>
    </div>
    </body>
</html>

