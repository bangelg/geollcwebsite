<?php
    @include 'config.php';
    function exception_handler($exception) {
        echo "<h1>Failure</h1>";
        echo "Uncaught exception: " , $exception->getMessage();
        echo "<h1>PHP Info for troubleshooting</h1>";
        phpinfo();
        }

        set_exception_handler('exception_handler');
        
    if ($conn->connect_error) {
        die('Connect Error: ' . $conn->connect_error);
    } else {
        if(isset($_POST['submit'])){

         $username = mysqli_real_escape_string($conn, $_POST['usernameInp']);
         $email = mysqli_real_escape_string($conn, $_POST['emailInp']);
         $pass = hash('sha256', $_POST['passwordInp']);
         $cpass = hash('sha256', $_POST['cpasswordInp']);
      
         $select = " SELECT * FROM users WHERE email = '$email' && passwrd = '$pass' ";
      
         $result = mysqli_query($conn, $select);
      
         if(mysqli_num_rows($result) > 0){
      
            $error[] = 'user already exist!';
      
         }else{
            if($pass != $cpass){
               $error[] = 'password not matched!';
            }else{
               $insert = "INSERT INTO users (username, email, passwrd) VALUES('$username','$email','$pass')";
               mysqli_query($conn, $insert);
               $Path = "/var/www/html/users/{$username}";
               mkdir($Path, 0755, false);
               header('location:login.php');
            } 
         }
      }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/global.css" />
    <link rel="stylesheet" href="../css/login.css" />
    <title>Register Page</title>
</head>
    <body>
    <div class="wrapper">
        <div class="form-wrapper">
            <h1>Register Page</h1>
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
                    <label for="email">Email: </label>
                    <input type="email" name="emailInp" required placeholder="Enter a valid email"><br><br>
                </div>
                <div class="input">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <label for="username">Username: </label>
                    <input type="string" name="usernameInp" required placeholder="Enter your desired username"><br><br>
                </div>
                <div class="input">
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

                <div class="login-register">
                    <p>
                        Already Have An Account?
                        <a href="login.php"> Login</a>
                    </p>
                </div>
                <input type="submit" name="submit" value="Register">
            </form>
        </div>
    </div>
    </body>
</html>