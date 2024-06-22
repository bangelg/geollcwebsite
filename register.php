<?php
    @include 'config.php';
    /**
    * Use this code snippet in your app.
    *
    * If you need more information about configurations or implementing the sample code, visit the AWS docs:
    * https://aws.amazon.com/developer/language/php/
    */

    require 'vendor/autoload.php';

    use Aws\SecretsManager\SecretsManagerClient;
    use Aws\Exception\AwsException;

    /**
        * This code expects that you have AWS credentials set up per:
        * https://<<{{DocsDomain}}>>/sdk-for-php/v3/developer-guide/guide_credentials.html
        */

    // Create a Secrets Manager Client
    $client = new SecretsManagerClient([
        'version' => '2017-10-17',
        'region' => 'us-west-1',
    ]);

    $secret_name = 'password';

    try {
        $result = $client->getSecretValue([
            'SecretId' => $secret_name,
        ]);

        if (isset($result['SecretString'])) {
            $secret = $result['SecretString'];
            $secretData = json_decode($secret, true);
            $adminPassword = $secretData['password'];
        }
    } catch (AwsException $e) {
        // For a list of exceptions thrown, see
        // https://<<{{DocsDomain}}>>/secretsmanager/latest/apireference/API_GetSecretValue.html
        throw $e;
    }

    // Decrypts secret using the associated KMS key.


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
        if(isset($_POST['submit']) && isset($_POST['admin_passwordInp'])){

            $username = mysqli_real_escape_string($conn, $_POST['usernameInp']);
            $email = mysqli_real_escape_string($conn, $_POST['emailInp']);
            $pass = hash('sha256', $_POST['passwordInp']);
            $cpass = hash('sha256', $_POST['cpasswordInp']);
            $admin_pass = hash('sha256', $_POST['admin_passwordInp']);
            $admin_password_hash = hash('sha256', $adminPassword);

            if ($admin_pass !== $admin_password_hash) {
                $error[] = 'Invalid admin password.';
            } else {
                $select = "SELECT * FROM users WHERE username = '$username'";

                $usercheck = mysqli_query($conn, $select) or die('Query Failed.');

                if (mysqli_num_rows($usercheck) > 0) {
                    $error[] = 'Username already exists. Choose a different one or reset your password.';
                } else {
                    if ($pass != $cpass) {
                        $error[] = 'Passwords not matched.';
                    } else {
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
    }
?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />

  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="./login.css" />
  <title>Register Page</title>
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
            <?php
                if(isset($error)){
                    foreach($error as $error){
                        echo '<span class="error-msg">'.$error.'</span>';
                    };
                };
            ?>
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
            <input class="input-field"type="string" name="usernameInp" required placeholder="Enter your username."><br><br>
        </div>
       <div class="labels">
            <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
            </span>
            <label for="password">Password: </label>    
       </div>
        <div class="input-tab">
            <input class="input-field"type="password" name="passwordInp" required placeholder="Enter your password."><br><br>    
        </div>
        <div class="labels">
            <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
            </span>
          
            <label for="cpassword">Confirm Password: </label>
        </div>
        <div class="input-tab">
            <input class="input-field"type="password" name="cpasswordInp" required placeholder="Confirm your password."><br><br>
        </div>
        <div class="labels">
            <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
            </span>
          
            <label for="cpassword">Admin Password: </label>
        </div>
        <div class="input-tab">
            <input class="input-field"type="password" name="admin_passwordInp" required placeholder="Confirm the admin password."><br><br>
        </div>

        <div class="labels">
            <label for="login">Already have an Account? </label> 
 
        </div> 
        <div class="input-tab">
            <div class="input-field">
                <div class="login-btn">         
                    <a href="login.php"> Login</a>
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
