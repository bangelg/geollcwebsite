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
               $Recent = "/var/www/html/users/{$username}/recent";
               mkdir($Recent, 0755, false);
               header('location:login.php');
            } 
         }
      }
    }
?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />

  <link rel="stylesheet" href="css/global.css" />
  <link rel="stylesheet" href="css/login.css" />
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap"
  />
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Lohit Devanagari:wght@400&display=swap"
  />
</head>
<form action="" method = 'POST'>
    <div class="container">
        <h1 id="title">Register</h1>
        <div class="labels">
            <span class="icon">
                <ion-icon name="mail"></ion-icon>
            </span>
            <label for="email">Email: </label>
        </div>
        <div class="input-tab">
            <input class="input-field"type="email" name="emailInp" required placeholder="Enter a valid email"><br><br>
        </div>
        <div class="labels">
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
            <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
            </span>
          
            <label for="cpassword">Confirm Password: </label>
        </div>
             <div class="input-tab">
            <input class="input-field"type="password" name="cpasswordInp" required placeholder="Confirm your password"><br><br>
        </div>

        <div class="labels">
            <label for="login">Already have an Account? </label> 
 
        </div> 
        <div class="login-btn">
            <a href="login.php"> Login</a>
        </div>
        <div class="btn">
            <button id="submit" type="submit">Submit</button>
        </div>
          
    </div>
</form>
</footer>
</html>
