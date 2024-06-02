<?php
session_start();
include "../utils.php";

session_start();

function isValidVerificationCode($verification_code) {
  if (!isset($_SESSION['verification_code'])) {
    return false; // No code was generated
  }
  $generated_code = $_SESSION['verification_code'];
  return $generated_code == $verification_code;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $verification_code = $_POST['verification_code']; // Corrected the typo

  if (isValidVerificationCode($verification_code)) {
    header("Location: ../user_pages/user_homepage.php");
    exit; // Ensure the script stops execution after the redirect
  } else {
    echo "Incorrect! Please enter the valid verification code";
  }
}
?>

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
            height: 50px; /* Adjust the height as needed */
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