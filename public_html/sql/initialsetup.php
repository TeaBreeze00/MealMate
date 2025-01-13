<?php
// This page is intended for setup/testing purposes only. This has to be omitted from the final version
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "../../utils.php";

function handleGenerateTables() {
    // SQL statements to drop and create tables
    $sqlCommands = [
        "DROP TABLE IF EXISTS Assigned",
        "DROP TABLE IF EXISTS Handles",
        "DROP TABLE IF EXISTS HasItems",
        "DROP TABLE IF EXISTS OrderItem",
        "DROP TABLE IF EXISTS OrderPlaced",
        "DROP TABLE IF EXISTS Vehicle",
        "DROP TABLE IF EXISTS DeliveryPersonnel",
        "DROP TABLE IF EXISTS Vendor",
        "DROP TABLE IF EXISTS MenuItem",
        "DROP TABLE IF EXISTS Orders",
        "DROP TABLE IF EXISTS Customer",

        "CREATE TABLE Customer (
            custID INT PRIMARY KEY,
            Name VARCHAR(100),
            Address VARCHAR(100),
            Email VARCHAR(100),
            password VARCHAR(255) NOT NULL,
            PhoneNo VARCHAR(15)
        )",
        "CREATE TABLE Orders (
            orderID INT PRIMARY KEY,
            OrderDate DATE,
            DeliveryAddress VARCHAR(255)
        )",
        "CREATE TABLE MenuItem (
            itemID INT PRIMARY KEY,
            name VARCHAR(100),
            description TEXT,
            price DECIMAL(10, 2)
        )",
        "CREATE TABLE Vendor (
            vendorID INT PRIMARY KEY,
            name VARCHAR(100),
            address VARCHAR(255),
            Email VARCHAR(100),
            cuisineType VARCHAR(50),
            password VARCHAR(255) NOT NULL,
            contactNo VARCHAR(15)
        )",
        "CREATE TABLE DeliveryPersonnel (
            DeliveryPersonId INT PRIMARY KEY,
            name VARCHAR(100),
            Email VARCHAR(100),
            password VARCHAR(255) NOT NULL,
            contactNo VARCHAR(15)
        )",
        "CREATE TABLE Vehicle (
            vehicleId INT PRIMARY KEY,
            type VARCHAR(50),
            registrationNo VARCHAR(20),
            availabilityStatus TINYINT(1)
        )",
        "CREATE TABLE OrderPlaced (
            orderID INT PRIMARY KEY,
            custID INT NOT NULL,
            FOREIGN KEY (custID) REFERENCES Customer(custID) ON DELETE CASCADE,
            FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE
        )",
        "CREATE TABLE OrderItem (
            orderID INT,
            itemID INT NOT NULL,
            quantity INT NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            PRIMARY KEY (orderID, itemID),
            FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
            FOREIGN KEY (itemID) REFERENCES MenuItem(itemID) ON DELETE CASCADE
        )",
        "CREATE TABLE HasItems (
            itemID INT PRIMARY KEY,
            vendorID INT NOT NULL,
            FOREIGN KEY (itemID) REFERENCES MenuItem(itemID) ON DELETE CASCADE,
            FOREIGN KEY (vendorID) REFERENCES Vendor(vendorID) ON DELETE CASCADE
        )",
        "CREATE TABLE Handles (
            orderID INT PRIMARY KEY,
            DeliveryPersonId INT NOT NULL,
            FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
            FOREIGN KEY (DeliveryPersonId) REFERENCES DeliveryPersonnel(DeliveryPersonId) ON DELETE CASCADE
        )",
        "CREATE TABLE Assigned (
            DeliveryPersonId INT PRIMARY KEY,
            vehicleId INT NOT NULL,
            FOREIGN KEY (DeliveryPersonId) REFERENCES DeliveryPersonnel(DeliveryPersonId) ON DELETE CASCADE,
            FOREIGN KEY (vehicleId) REFERENCES Vehicle(vehicleId) ON DELETE CASCADE
        )"
    ];

    foreach ($sqlCommands as $sql) {
        executePlainSQL($sql);
    }
}

function handlePrintTables() {
    $tables = [
        "Customer",
        "Orders",
        "MenuItem",
        "Vendor",
        "DeliveryPersonnel",
        "Vehicle",
        "OrderPlaced",
        "OrderItem",
        "HasItems",
        "Handles",
        "Assigned"
    ];

    foreach ($tables as $table) {
        $result = executePlainSQL("SELECT * FROM " . $table);
        if (printQueryResult($result, $table . " Table") == 0) {
            echo "<p>No data found in " . $table . " table.</p>";
        }
    }
}

function handleResetTables() {
    // Calls generate tables to drop and recreate the tables
    handleGenerateTables();
}

function handleInsertAll() {
    $insertCommands = [
        // Customer
        "INSERT INTO Customer (custID, Name, Address, Email, password, PhoneNo) 
         VALUES (1, 'Alice', '123 Main St', 'alice@example.com', 'hashed_password1', '1234567890')
         ON DUPLICATE KEY UPDATE 
         Name = VALUES(Name), Address = VALUES(Address), Email = VALUES(Email), 
         password = VALUES(password), PhoneNo = VALUES(PhoneNo)",
        // ... (repeat for other Customer entries)

        // Orders
        "INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) 
         VALUES (1, '2023-06-01', '123 Main St')
         ON DUPLICATE KEY UPDATE 
         OrderDate = VALUES(OrderDate), DeliveryAddress = VALUES(DeliveryAddress)",
        // ... (repeat for other Orders entries)

        // MenuItem
        "INSERT INTO MenuItem (itemID, name, description, price) 
         VALUES (1, 'Burger', 'A delicious burger', 5.99)
         ON DUPLICATE KEY UPDATE 
         name = VALUES(name), description = VALUES(description), price = VALUES(price)",
        // ... (repeat for other MenuItem entries)

        // Vendor
        "INSERT INTO Vendor (vendorID, name, address, Email, cuisineType, password, contactNo) 
         VALUES (1, 'Fast Food Inc', '123 Burger Ln', 'fastfood@example.com', 'Fast Food', 'hashed_password1', '1112223333')
         ON DUPLICATE KEY UPDATE 
         name = VALUES(name), address = VALUES(address), Email = VALUES(Email), 
         cuisineType = VALUES(cuisineType), password = VALUES(password), contactNo = VALUES(contactNo)",
        // ... (repeat for other Vendor entries)

        // DeliveryPersonnel
        "INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, Email, password, contactNo) 
         VALUES (1, 'John', 'john@example.com', 'hashed_password1', '9998887777')
         ON DUPLICATE KEY UPDATE 
         name = VALUES(name), Email = VALUES(Email), password = VALUES(password), contactNo = VALUES(contactNo)",
        // ... (repeat for other DeliveryPersonnel entries)

        // Vehicle
        "INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) 
         VALUES (1, 'Car', 'ABC123', 1)
         ON DUPLICATE KEY UPDATE 
         type = VALUES(type), registrationNo = VALUES(registrationNo), availabilityStatus = VALUES(availabilityStatus)",
        // ... (repeat for other Vehicle entries)

        // OrderPlaced
        "INSERT INTO OrderPlaced (orderID, custID) 
         VALUES (1, 1)
         ON DUPLICATE KEY UPDATE custID = VALUES(custID)",
        // ... (repeat for other OrderPlaced entries)

        // OrderItem
        "INSERT INTO OrderItem (orderID, itemID, quantity, amount) 
         VALUES (1, 1, 2, 11.98)
         ON DUPLICATE KEY UPDATE quantity = VALUES(quantity), amount = VALUES(amount)",
        // ... (repeat for other OrderItem entries)

        // HasItems
        "INSERT INTO HasItems (itemID, vendorID) 
         VALUES (1, 1)
         ON DUPLICATE KEY UPDATE vendorID = VALUES(vendorID)",
        // ... (repeat for other HasItems entries)

        // Handles
        "INSERT INTO Handles (orderID, DeliveryPersonId) 
         VALUES (1, 1)
         ON DUPLICATE KEY UPDATE DeliveryPersonId = VALUES(DeliveryPersonId)",
        // ... (repeat for other Handles entries)

        // Assigned
        "INSERT INTO Assigned (DeliveryPersonId, vehicleId) 
         VALUES (1, 1)
         ON DUPLICATE KEY UPDATE vehicleId = VALUES(vehicleId)",
        // ... (repeat for other Assigned entries)
    ];

    foreach ($insertCommands as $sql) {
        $result = executePlainSQL($sql);
        if (!$result) {
            echo "Error executing SQL: " . $sql . "<br>";
            echo "MySQL Error: " . mysqli_error($GLOBALS['db_conn']) . "<br>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (connectToDB()) {
        if (isset($_POST['generateTables'])) {
            handleGenerateTables();
        } elseif (isset($_POST['printTables'])) {
            handlePrintTables();
        } elseif (isset($_POST['resetTables'])) {
            handleResetTables();
        } elseif (isset($_POST['insertAll'])) {
            handleInsertAll();
        }

        disconnectFromDB();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initial Setup</title>
</head>
<body>
<h2>Initial Setup</h2>
<form method="POST" action="initialsetup.php">
    <button type="submit" name="generateTables">Generate Tables</button>
</form>
<form method="POST" action="initialsetup.php">
    <button type="submit" name="printTables">Print Tables</button>
</form>
<form method="POST" action="initialsetup.php">
    <button type="submit" name="resetTables">Reset</button>
</form>
<form method="POST" action="initialsetup.php">
    <button type="submit" name="insertAll">Insert All</button>
</form>
</body>
</html>

