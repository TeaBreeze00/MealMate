<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<html>

<head>
    <title>CPSC 304 PHP/Oracle Demonstration</title>
</head>

<body>
<h2>Reset</h2>
<p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

<form method="POST" action="oracle-template.php">
    <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
    <p><input type="submit" value="Reset" name="reset"></p>
</form>

<hr />

<h2>Insert Values into DemoTable</h2>
<form method="POST" action="oracle-template.php">
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    Number: <input type="text" name="insNo"> <br /><br />
    Name: <input type="text" name="insName"> <br /><br />
    <input type="submit" value="Insert" name="insertSubmit"></p>
</form>

<hr />

<h2>Update Name in DemoTable</h2>
<p>The values are case-sensitive and if you enter the wrong case, the update statement will not do anything.</p>

<form method="POST" action="oracle-template.php">
    <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
    Old Name: <input type="text" name="oldName"> <br /><br />
    New Name: <input type="text" name="newName"> <br /><br />
    <input type="submit" value="Update" name="updateSubmit"></p>
</form>

<hr />

<h2>Count the Tuples in DemoTable</h2>
<form method="GET" action="oracle-template.php">
    <input type="hidden" id="countTupleRequest" name="countTupleRequest">
    <input type="submit" name="countTuples"></p>
</form>

<hr />

<h2>Display Tuples in DemoTable</h2>
<form method="GET" action="oracle-template.php">
    <input type="hidden" id="displayTuplesRequest" name="displayTuplesRequest">
    <input type="submit" name="displayTuples"></p>
</form>

<?php
$success = True;
$db_conn = 0;
$show_debug_alert_messages = False;

$config = array(
    "dbserver" => "127.0.0.1",
    "dbuser" => "root",
    "dbpassword" => "",
    "dbname" => "MealMate",
    "port" => 3306
);

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function connectToDB() {
    global $db_conn, $config;

    $db_conn = mysqli_connect(
        $config["dbserver"],
        $config["dbuser"],
        $config["dbpassword"],
        $config["dbname"],
        $config["port"]
    );

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return $db_conn;
    } else {
        debugAlertMessage("Cannot connect to Database");
        echo "Error: " . mysqli_connect_error();
        return null;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    mysqli_close($db_conn);
}

function executePlainSQL($cmdstr) {
    global $db_conn, $success;

    $result = mysqli_query($db_conn, $cmdstr);

    if (!$result) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $success = False;
        echo mysqli_error($db_conn);
    }

    return $result;
}

function executeBoundSQL($cmdstr, $list) {
    global $db_conn, $success;
    $stmt = mysqli_prepare($db_conn, $cmdstr);

    if (!$stmt) {
        echo "<br>Cannot prepare the following command: " . $cmdstr . "<br>";
        $success = False;
        echo mysqli_error($db_conn);
        return;
    }

    foreach ($list as $tuple) {
        $params = array();
        $types = "";
        foreach ($tuple as $key => $val) {
            $params[] = $val;
            if (is_int($val)) {
                $types .= "i";
            } elseif (is_float($val)) {
                $types .= "d";
            } else {
                $types .= "s";
            }
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $success = False;
            echo mysqli_error($db_conn);
        }
    }
    mysqli_stmt_close($stmt);
}

function printResult($result) {
    echo "<br>Retrieved data from table demoTable:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td></tr>";
    }

    echo "</table>";
}

function handleResetRequest() {
    global $db_conn;
    executePlainSQL("DROP TABLE IF EXISTS demoTable");
    echo "<br>Creating new table<br>";
    executePlainSQL("CREATE TABLE demoTable (id INT PRIMARY KEY, name VARCHAR(30))");
    mysqli_commit($db_conn);
    echo "Reset successful.";
}

function handleInsertRequest() {
    global $db_conn;
    $id = $_POST['insNo'];
    $name = $_POST['insName'];
    $query = "INSERT INTO demoTable (id, name) VALUES (?, ?)";
    $list = array(array($id, $name));
    executeBoundSQL($query, $list);
    mysqli_commit($db_conn);
    echo "Insertion successful: ID=$id, Name=$name.";
}

function handleUpdateRequest() {
    global $db_conn;
    $oldName = $_POST['oldName'];
    $newName = $_POST['newName'];
    $query = "UPDATE demoTable SET name=? WHERE name=?";
    $list = array(array($newName, $oldName));
    executeBoundSQL($query, $list);
    mysqli_commit($db_conn);
    echo "Update successful: Old Name=$oldName, New Name=$newName.";
}

function handleCountRequest() {
    global $db_conn;
    $result = executePlainSQL("SELECT COUNT(*) AS count FROM demoTable");
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<br>The number of tuples in demoTable: " . $row['count'] . "<br>";
    }
}

function handleDisplayRequest() {
    global $db_conn;
    $result = executePlainSQL("SELECT * FROM demoTable");
    printResult($result);
}

function handlePOSTRequest() {
    if (isset($_POST['resetTablesRequest'])) {
        handleResetRequest();
    } elseif (isset($_POST['insertQueryRequest'])) {
        handleInsertRequest();
    } elseif (isset($_POST['updateQueryRequest'])) {
        handleUpdateRequest();
    }
}

function handleGETRequest() {
    if (isset($_GET['countTupleRequest'])) {
        handleCountRequest();
    } elseif (isset($_GET['displayTuplesRequest'])) {
        handleDisplayRequest();
    }
}

if (connectToDB()) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handlePOSTRequest();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        handleGETRequest();
    }
    disconnectFromDB();
}
?>
</body>
</html>
