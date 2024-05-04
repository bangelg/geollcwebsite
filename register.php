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

            $name = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $pass = hash($_POST['password']);
           
            $select = " SELECT * FROM users WHERE email = '$email' && password = '$pass' ";
         
            $result = mysqli_query($conn, $select);
         
            if(mysqli_num_rows($result) > 0){
         
               $error[] = 'user already exist!';
         
            }else{
         
               if($pass != $cpass){
                  $error[] = 'password not matched!';
               }else{
                  $insert = "INSERT INTO user_form(name, email, password, user_type) VALUES('$name','$email','$pass','$user_type')";
                  mysqli_query($conn, $insert);
                  header('location:login_form.php');
               }
            }
         
         };
    }
?>