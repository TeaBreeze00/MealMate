<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "../../utils.php";

// Function to validate phone number
function isValidPhoneNumber($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

// Function to generate a unique delivery personnel ID
function generateDeliveryPersonnelId() {
    global $db_conn; // Use the global connection

    $query = "SELECT MAX(DeliveryPersonId) AS maxID FROM DeliveryPersonnel";
    $stmt = mysqli_query($db_conn, $query);

    if ($stmt) {
        $row = mysqli_fetch_assoc($stmt);
        return ($row['maxID'] ?? 0) + 1;
    } else {
        throw new Exception("Error in fetching max ID: " . mysqli_error($db_conn));
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $contactNumber = trim($_POST['contactNumber']);
    $password = trim($_POST['password']);
    $email = trim($_SESSION['email']);
    $error = "";

    // Validate inputs
    if (empty($name) || empty($contactNumber) || empty($password)) {
        $error = "Please fill out all required fields.";
    } elseif (!isValidPhoneNumber($contactNumber)) {
        $error = "Invalid phone number format. Please enter 10 digits.";
    }

    if (empty($error)) {
        $db_connected = connectToDB(); // This returns true if connected

        if ($db_connected) {
            global $db_conn; // Declare the global connection variable

            try {
                $delivery_personnel_id = generateDeliveryPersonnelId();

                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Prepare the SQL statement
                $query = "INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, Email, password, contactNo) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($db_conn, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "issss", $delivery_personnel_id, $name, $email, $hashedPassword, $contactNumber);

                    // Execute the statement
                    if (mysqli_stmt_execute($stmt)) {
                        $success = "Delivery personnel registered successfully.";
                        header("location: ./delivery_homepage.php");
                        exit();
                    } else {
                        throw new Exception("Database error: " . mysqli_error($db_conn));
                    }
                } else {
                    throw new Exception("Failed to prepare statement: " . mysqli_error($db_conn));
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            } finally {
                if (isset($stmt)) {
                    mysqli_stmt_close($stmt);
                }
                if (isset($db_conn)) {
                    mysqli_close($db_conn);
                }
            }
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
    <title>MealMate - Delivery Personnel Registration</title>
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
        .container input[type="text"], .container input[type="password"] {
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
    <p>Register as Delivery Personnel</p>
    <?php
    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    } elseif (!empty($success)) {
        echo "<p style='color:green;'>$success</p>";
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
        <input type="text" name="name" placeholder="Enter your name (required)" value="<?php echo htmlspecialchars($name ?? ''); ?>">
        <input type="text" name="contactNumber" placeholder="Enter your contact number (required)" value="<?php echo htmlspecialchars($contactNumber ?? ''); ?>">
        <input type="password" name="password" placeholder="Enter your password (required)">
        <input type="submit" value="Register">
    </form>
</div>
</body>
</html>
