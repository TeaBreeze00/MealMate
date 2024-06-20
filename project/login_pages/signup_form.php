<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "../../utils.php";

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to generate a unique customer ID (this is a simplified example, consider using an auto-increment field instead)
function generateCustomerId($db_conn) {
    $query = "SELECT MAX(custID) AS maxID FROM Customer";
    $result = executePlainSQL($query);
    $row = oci_fetch_array($result, OCI_ASSOC);
    return $row['MAXID'] + 1;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phoneNo = trim($_POST['phoneNo']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $error = "";

    // Validate inputs
    if (empty($name) || empty($address) || empty($phoneNo) || empty($email) || empty($password)) {
        $error = "Please fill out all required fields.";
    } elseif (!isValidEmail($email)) {
        $error = "Invalid email format.";
    }

    if (empty($error)) {
        if (connectToDB()) {
            $custID = generateCustomerId($db_conn);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO Customer (custID, Name, Address, Email, password, PhoneNo) VALUES (:custID, :name, :address, :email, :password, :phoneNo)";
            $statement = oci_parse($db_conn, $query);
            oci_bind_by_name($statement, ':custID', $custID);
            oci_bind_by_name($statement, ':name', $name);
            oci_bind_by_name($statement, ':address', $address);
            oci_bind_by_name($statement, ':email', $email);
            oci_bind_by_name($statement, ':password', $hashed_password);
            oci_bind_by_name($statement, ':phoneNo', $phoneNo);

            if (oci_execute($statement)) {
                $success = "Customer registered successfully.";
            } else {
                $error = "Database error: " . oci_error($statement)['message'];
            }

            oci_free_statement($statement);
            disconnectFromDB();
        } else {
            $error = "Database connection failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div class="container">
        <p>Let's get started</p>
        <?php
        if (!empty($error)) {
            echo "<p style='color:red;'>$error</p>";
        } elseif (!empty($success)) {
            echo "<p style='color:green;'>$success</p>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
            <input type="text" name="name" placeholder="Enter your name (required)" value="<?php echo htmlspecialchars($name ?? ''); ?>">
            <input type="text" name="address" placeholder="Enter your address (required)" value="<?php echo htmlspecialchars($address ?? ''); ?>">
            <input type="text" name="phoneNo" placeholder="Enter your number (required)" value="<?php echo htmlspecialchars($phoneNo ?? ''); ?>">
            <input type="text" name="email" placeholder="Enter your email (required)" value="<?php echo htmlspecialchars($email ?? ''); ?>">
            <input type="text" name="password" placeholder="Enter your password (required)" value="<?php echo htmlspecialchars($password ?? ''); ?>">
            <input type="submit" value="Continue">
        </form>
    </div>
</body>
</html>
