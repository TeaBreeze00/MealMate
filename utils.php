<?php
include "personalconfig.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_conn = NULL;
$success = true;
$show_debug_alert_messages = false;

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) {
    global $db_conn, $success;

    $result = mysqli_query($db_conn, $cmdstr);

    if (!$result) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        echo "Error: " . mysqli_error($db_conn) . "<br>";
        $success = false;
    }

    return $result;
}

function executeBoundSQL($cmdstr, $list) {
    global $db_conn, $success;

    $stmt = mysqli_prepare($db_conn, $cmdstr);

    if (!$stmt) {
        echo "<br>Cannot prepare the following command: " . $cmdstr . "<br>";
        echo "Error: " . mysqli_error($db_conn) . "<br>";
        $success = false;
        return;
    }

    foreach ($list as $tuple) {
        $params = [];
        $types = "";

        foreach ($tuple as $val) {
            $params[] = $val;
            $types .= is_int($val) ? "i" : "s";
        }

        mysqli_stmt_bind_param($stmt, $types, ...$params);
        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            echo "Error: " . mysqli_stmt_error($stmt) . "<br>";
            $success = false;
        }
    }

    mysqli_stmt_close($stmt);
}

function connectToDB() {
    global $db_conn, $DB_USER, $DB_PASS, $DB_HOST, $DB_NAME, $DB_PORT;

    $db_conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    if ($db_conn) {
        logMessage("Connected to Database");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        echo "Error: " . mysqli_connect_error() . "<br>";
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    logMessage("Disconnected from Database");
    mysqli_close($db_conn);
}

function printQueryResult($result, $header = "") {
    $firstRow = mysqli_fetch_assoc($result);

    if (!$firstRow) {
        logMessage("No data found.");
        return 0;
    }

    if ($header) {
        echo "<h1>" . htmlspecialchars($header) . "</h1>";
    }

    echo "<table border='1'><tr>";
    foreach (array_keys($firstRow) as $header) {
        echo "<th>" . htmlspecialchars($header) . "</th>";
    }
    echo "</tr>";

    do {
        echo "<tr>";
        foreach ($firstRow as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    } while ($firstRow = mysqli_fetch_assoc($result));

    echo "</table>";
    return 1;
}

function logMessage($message, $logFile = 'application.log') {
    $logDirectory = __DIR__ . '/logs';

    if (!file_exists($logDirectory)) {
        mkdir($logDirectory, 0755, true);
    }

    $filePath = $logDirectory . '/' . $logFile;

    $formattedMessage = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;

    file_put_contents($filePath, $formattedMessage, FILE_APPEND);
}

function readSQLFile($path) {
    $SQLarr = [];
    $line = "";

    $myfile = fopen($path, "r") or die("Unable to open SQL file!");

    while (!feof($myfile)) {
        $char = fread($myfile, 1);
        if ($char === ";") {
            $SQLarr[] = $line;
            $line = "";
        } else if ($char != "\n") {
            $line .= $char;
        }
    }

    fclose($myfile);
    return $SQLarr;
}

function executeSQLFile($path) {
    global $db_conn;

    $arr = readSQLFile($path);

    foreach ($arr as $statement) {
        executePlainSQL($statement);
        mysqli_commit($db_conn);
    }
}

function generateRandomString($length) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function checkDataExists($query) {
    global $db_conn;

    $result = executePlainSQL($query);
    return mysqli_fetch_assoc($result) !== null;
}

function separateString($string, $separator) {
    return explode($separator, $string);
}

function sanitizeString($input) {
    $charactersToRemove = [";", "'", "WHERE", "SELECT", "FROM", "GROUP", "BY", "IN", "DROP", "CREATE", "TYPE"];
    return str_replace($charactersToRemove, "", $input);
}

function reducedSanitizeString($input) {
    $charactersToRemove = [";", "'"];
    return str_replace($charactersToRemove, "", $input);
}
?>
