<?php
include "../utils.php"; // Ensure this file contains the connectToDB() and disconnectFromDB() functions

function testDatabaseConnection() {
    global $db_conn;
    if (connectToDB()) {
        echo "Connected to DB successfully.<br>"; // Debug message
        $query = "SELECT * FROM CUSTOMER WHERE EMAIL = :email";
        $email = 'test@example.com'; // Replace with a test email that exists in your database
        $escaped_email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $statement = oci_parse($db_conn, $query);
        oci_bind_by_name($statement, ':email', $escaped_email);

        if (oci_execute($statement)) {
            if ($row = oci_fetch_array($statement, OCI_ASSOC)) {
                echo "Email found: " . $row['EMAIL'] . "<br>"; // Debug message
                oci_free_statement($statement);
                disconnectFromDB();
                return true;
            } else {
                echo "Email not found.<br>"; // Debug message
                oci_free_statement($statement);
                disconnectFromDB();
                return false;
            }
        } else {
            echo "Database query execution failed.<br>"; // Debug message
            oci_free_statement($statement);
            disconnectFromDB();
            return false;
        }
    } else {
        echo "Database connection failed.<br>"; // Debug message
        return false;
    }
}

testDatabaseConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealMate</title>
</head>
<body>
 <p> This is a test page</p>
</body>
</html>








