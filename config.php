<?php
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

    $secret_name = 'prod/DB';

    try {
        $result = $client->getSecretValue([
            'SecretId' => $secret_name,
        ]);

        if (isset($result['SecretString'])) {
            $secret = $result['SecretString'];
        }
        $secretArray = json_decode($secret, true);
        $username = $secretArray['username'];
        $password = $secretArray['password'];
    } catch (AwsException $e) {
        // For a list of exceptions thrown, see
        // https://<<{{DocsDomain}}>>/secretsmanager/latest/apireference/API_GetSecretValue.html
        throw $e;
    }

    $host = "igtdb.cv2im0m26jl5.us-west-1.rds.amazonaws.com";
    $user = $username;
    $pass = $password;
    $dbName = 'Main';
    $conn = mysqli_connect($host, $user, $pass, $dbName);
?>