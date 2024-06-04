<?php

session_start();
include "../utils.php";
function isValidPhone($phone) {
    return filter_var($phone, FILTER_VALIDATE_EMAIL);
}

function generateVerificationCode() {
    return rand(100000, 999999);
}

function phoneExists($email){
    if (connectToDB()) {
        $result = executePlainSQL("SELECT * FROM CLIENT WHERE EMAIL = '{$email}'");
        oci_commit($db_conn);
        disconnectFromDB();
        return $result != null;
    }
}

function sendVerificationSms($phone, $code) {
    
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];

    if (isValidPhone($phone)) {
        
        if(phoneExists($phone)){  //this is currently not working because the database doesn't exist
            echo "Phone Number already exists, Please login with your credentials";
            header("Location: login.php");
            exit();
        }

        $verificationCode = generateVerificationCode();
        if (sendVerificationSms($phone, $verificationCode)) {
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['phone'] = $phone;
            header("Location: phone_authentication.php");
            exit();
        } else {
            echo "Failed to send verification email.";
        }
    } else {
        echo "Invalid phone number";
    }
}


?>

<html>

<head>
<title>MealMate | Enter your phone number</title>

<style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .top-bar {
            width: 100%;
            height: 60px;
            background-color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 0;
        }

        .top-bar .logo {
            height: 50px; 
            width: auto;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px; 
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container input[type="text"] {
            width: 300px;
            height: 40px;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            background-color: #d3d3d3;
            border-radius: 8px; 
            box-sizing: border-box;
        }

        .container input[type="submit"] {
            width: 300px; 
            height: 40px;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 8px; 
            cursor: pointer;
            font-size: 14px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

</style>        
</head>

<body>
<div class="top-bar">
    <img src="../diagrams/logo.png" alt="Logo" class="logo">
</div>

  <div class = "container">
    <p> Let's get started</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
        <input type="text" name="phone" placeholder="Enter your phone no. (required)">
        <input type="submit" value="Continue">
    </form>

    </div>

</body>

</html>