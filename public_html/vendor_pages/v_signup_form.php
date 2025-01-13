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

// Function to generate a unique vendor ID
function generateVendorId() {
    global $db_conn; // Use the global connection
    $query = "SELECT MAX(vendorID) AS maxID FROM Vendor";
    $result = mysqli_query($db_conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return ($row['maxID'] ?? 0) + 1; // Use null coalescing operator to handle case where table is empty
    } else {
        die("Error fetching max ID: " . mysqli_error($db_conn));
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phoneNo = trim($_POST['phoneNo']);
    $email = trim($_POST['email']);
    $cuisineType = trim($_POST['cuisineType']);
    $password = trim($_POST['password']);
    $error = "";

    // Validate inputs
    if (empty($name) || empty($address) || empty($phoneNo) || empty($email) || empty($password) || empty($cuisineType)) {
        $error = "Please fill out all required fields.";
    } elseif (!isValidEmail($email)) {
        $error = "Invalid email format.";
    }

    if (empty($error)) {
        $db_connected = connectToDB(); // This returns true if connected

        if ($db_connected) {
            global $db_conn; // Declare the global connection variable

            $vendorID = generateVendorId();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement
            $query = "INSERT INTO Vendor (vendorID, Name, Address, Email, cuisineType, password, contactNo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $statement = mysqli_prepare($db_conn, $query);

            if ($statement) {
                // Bind parameters to the statement
                mysqli_stmt_bind_param($statement, "issssss", $vendorID, $name, $address, $email, $cuisineType, $hashed_password, $phoneNo);

                // Execute the statement
                if (mysqli_stmt_execute($statement)) {
                    $success = "Vendor registered successfully.";
                    header("location: ./vendor_homepage.php");
                } else {
                    $error = "Database error: " . mysqli_error($db_conn);
                }

                // Close the prepared statement
                mysqli_stmt_close($statement);
            } else {
                $error = "Failed to prepare statement: " . mysqli_error($db_conn);
            }

            // No need to close the connection as we're not managing it directly
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
        .container input[type="text"],
        .container input[type="password"] {
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
        <input type="text" name="cuisineType" placeholder="Enter your cuisine type (required)" value="<?php echo htmlspecialchars($cuisineType ?? ''); ?>">
        <input type="password" name="password" placeholder="Enter your password (required)" value="<?php echo htmlspecialchars($password ?? ''); ?>">
        <input type="submit" value="Continue">
    </form>
</div>
</body>
</html>
