<?php
session_start();
include "../../utils.php";

function isValidVerificationCode($verification_code) {
    if (!isset($_SESSION['verification_code'])) {
        return false;
    }
    $hashed_code = $_SESSION['verification_code'];
    return password_verify($verification_code, $hashed_code);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the verification code and email are set
    if (!isset($_SESSION['email'])) {
        echo "Session expired or email not set. Please try again.";
        exit;
    }

    $verification_code = $_POST['verification_code'];

    if (isValidVerificationCode($verification_code)) {
        // Redirect to signup form if the verification code is correct
        header("Location: signup_form.php");
        exit;
    } else {
        // Error message for incorrect verification code
        $error_message = "Incorrect! Please enter a valid verification code.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Code</title>
</head>
<body>
<h1>Enter Verification Code</h1>
<form method="POST" action="">
    <label for="verification_code">Verification Code:</label>
    <input type="text" name="verification_code" id="verification_code" required>
    <button type="submit">Verify</button>
</form>
<?php
// Display error message if it exists
if (isset($error_message)) {
    echo "<p style='color: red;'>" . htmlspecialchars($error_message) . "</p>";
}
?>
</body>
</html>


<html>

<head>
<title>MealMate</title>

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
    <p> Enter the 6 digit verification code sent to you at your email</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
        <input type="text" name="verification_code" placeholder="Enter verification code">
        <input type="submit" value="Continue">
    </form>
    </div>
</body>

</html>