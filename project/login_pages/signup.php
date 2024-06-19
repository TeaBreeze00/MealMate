<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "../../utils.php";


function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateVerificationCode() {
    return rand(100000, 999999);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "Your verification code is: " . $code;
    $headers = "From: shamsetabriz00@gmail.com"; // Replace with your email

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

function emailExists($email){
    global $db_conn;
    if (connectToDB()) {
        echo "connect to DB success"; 
        $result = executePlainSQL("SELECT * FROM CUSTOMER WHERE EMAIL = '{$email}'");
        if ($result) {
            $row = oci_fetch_array($result, OCI_ASSOC);
            disconnectFromDB();
            return $row != false;
        } else {
            echo "Database query failed.";
            disconnectFromDB();
            return false;
        }
    } else {
        echo "Database connection failed.";
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email/phone'];

    if (isValidEmail($email)) {

        if (!emailExists($email)) {

            echo "Email does not exist.";
            
            $verificationCode = generateVerificationCode();

            if (sendVerificationEmail($email, $verificationCode)) {
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['email'] = $email;
            header("Location: authentication.php");
            exit();
            } else {
            echo "Failed to send verification email.";
            }

        } else {
            echo "Email is in our system already. Please login";
            header("Location: login.php");
        }
    } else {
        echo "Invalid email address.";
    }
}

?> 



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealMate</title>
    <link rel="stylesheet" href="../css/reset.css">

    <meta name="appleid-signin-client-id" content="[CLIENT_ID]">
    <meta name="appleid-signin-scope" content="[SCOPES]">
    <meta name="appleid-signin-redirect-uri" content="[REDIRECT_URI]">
    <meta name="appleid-signin-state" content="[STATE]">

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
            margin-top: 80px; /* Adjust to position below the top bar */
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .or-divider {
            width: 300px; /* Same width as buttons */
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .or-divider hr {
            flex: 1;
            border: none;
            border-top: 1px solid #ccc;
            margin: 0;
        }

        .or-divider span {
            padding: 0 10px;
            color: #999;
        }

        .container p {
            margin: 0 0 20px;
            text-align: left;
            width: 300px; /* Align text with input and button */
        }

        .container input[type="text"] {
            width: 300px;
            height: 40px;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            background-color: #d3d3d3;
            border-radius: 8px; /* Smoother border */
            box-sizing: border-box;
        }

        .container input[type="submit"] {
            width: 300px; /* Same width as the text box */
            height: 40px;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 8px; /* Smoother border */
            cursor: pointer;
            font-size: 14px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .gsi-material-button {
            width: 300px; /* Same width as the text box and submit button */
            height: 40px;
            padding: 0 10px;
            background-color: #d3d3d3;
            border: none;
            color: black;
            border-radius: 8px; /* Smoother border */
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            gap: 10px; /* Space between icon and text */
        }

        .gsi-material-button .gsi-material-button-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .apple-signin-button {
            width: 300px; /* Same width as other buttons */
            height: 40px; /* Same height as other buttons */
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            background-color: #d3d3d3;
            cursor: pointer;
            box-sizing: border-box;
            margin-top: 10px;
        }

    </style>
</head>
<body>

<div class="top-bar">
    <img src="../diagrams/logo.png" alt="Logo" class="logo">
</div>


<div class="container">
    <p>What's your phone number or email?</p>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
        <input type="text" name="email/phone" placeholder="Enter email">
        <input type="submit" value="Continue">
    </form>
    

    <div class="or-divider">
        <hr><span>or</span><hr>
    </div>
    

    <button class="gsi-material-button">
        <div class="gsi-material-button-icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block; height: 24px; width: 24px;">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                <path fill="none" d="M0 0h48v48H0z"></path>
            </svg>
        </div>
        <span class="gsi-material-button-contents">Continue with Google</span>
    </button>
    
    <div id="appleid-signin" class="apple-signin-button" data-color="#d3d3d3" data-border="false" data-type="sign-in"></div>
    </div>
    <script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>

</div>

</body>
</html>









