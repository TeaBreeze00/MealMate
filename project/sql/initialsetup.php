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
        "DROP TABLE Customer CASCADE CONSTRAINTS",
        "DROP TABLE Orders CASCADE CONSTRAINTS",
        "DROP TABLE MenuItem CASCADE CONSTRAINTS",
        "DROP TABLE Vendor CASCADE CONSTRAINTS",
        "DROP TABLE DeliveryPersonnel CASCADE CONSTRAINTS",
        "DROP TABLE Vehicle CASCADE CONSTRAINTS",
        "DROP TABLE OrderPlaced CASCADE CONSTRAINTS",
        "DROP TABLE OrderItem CASCADE CONSTRAINTS",
        "DROP TABLE HasItems CASCADE CONSTRAINTS",
        "DROP TABLE Handles CASCADE CONSTRAINTS",
        "DROP TABLE Assigned CASCADE CONSTRAINTS",
        
        "CREATE TABLE Customer (
            custID INT PRIMARY KEY,
            Name VARCHAR2(100),
            Address VARCHAR2(100),
            Email VARCHAR2(100),
            password VARCHAR(255) NOT NULL,
            PhoneNo VARCHAR2(15)
        )",
        "CREATE TABLE Orders (
            orderID INT PRIMARY KEY,
            OrderDate DATE,
            DeliveryAddress VARCHAR2(255)
        )",
        "CREATE TABLE MenuItem (
            itemID INT PRIMARY KEY,
            name VARCHAR2(100),
            description VARCHAR2(4000),
            price NUMBER(10, 2)
        )",
        "CREATE TABLE Vendor (
            vendorID INT PRIMARY KEY,
            name VARCHAR2(100),
            address VARCHAR2(255),
            Email VARCHAR2(100),
            cuisineType VARCHAR2(50),
            password VARCHAR(255) NOT NULL,
            contactNo VARCHAR2(15)
        )",
        "CREATE TABLE DeliveryPersonnel (
            DeliveryPersonId INT PRIMARY KEY,
            name VARCHAR2(100),
            Email VARCHAR2(100),
            password VARCHAR(255) NOT NULL,
            contactNo VARCHAR2(15)
        )",
        "CREATE TABLE Vehicle (
            vehicleId INT PRIMARY KEY,
            type VARCHAR2(50),
            registrationNo VARCHAR2(20),
            availabilityStatus NUMBER(1)
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
            amount NUMBER(10, 2) NOT NULL,
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
        "INSERT INTO Customer (custID, Name, Address, Email, password, PhoneNo) VALUES (1, 'Alice', '123 Main St', 'alice@example.com', 'hashed_password1', '1234567890')",
        "INSERT INTO Customer (custID, Name, Address, Email, password, PhoneNo) VALUES (2, 'Bob', '456 Elm St', 'bob@example.com', 'hashed_password2', '0987654321')",
        "INSERT INTO Customer (custID, Name, Address, Email, password, PhoneNo) VALUES (3, 'Carol', '789 Oak St', 'carol@example.com', 'hashed_password3', '1231231234')",
        "INSERT INTO Customer (custID, Name, Address, Email, password, PhoneNo) VALUES (4, 'David', '321 Pine St', 'david@example.com', 'hashed_password4', '9879879876')",
        "INSERT INTO Customer (custID, Name, Address, Email, password, PhoneNo) VALUES (5, 'Eve', '654 Cedar St', 'eve@example.com', 'hashed_password5', '4564564567')",

        // Orders
        "INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (1, TO_DATE('2023-06-01', 'YYYY-MM-DD'), '123 Main St')",
        "INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (2, TO_DATE('2023-06-02', 'YYYY-MM-DD'), '456 Elm St')",
        "INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (3, TO_DATE('2023-06-03', 'YYYY-MM-DD'), '789 Oak St')",
        "INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (4, TO_DATE('2023-06-04', 'YYYY-MM-DD'), '321 Pine St')",
        "INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (5, TO_DATE('2023-06-05', 'YYYY-MM-DD'), '654 Cedar St')",

        // MenuItem
        "INSERT INTO MenuItem (itemID, name, description, price) VALUES (1, 'Burger', 'A delicious burger', 5.99)",
        "INSERT INTO MenuItem (itemID, name, description, price) VALUES (2, 'Pizza', 'A cheesy pizza', 8.99)",
        "INSERT INTO MenuItem (itemID, name, description, price) VALUES (3, 'Salad', 'A healthy salad', 4.99)",
        "INSERT INTO MenuItem (itemID, name, description, price) VALUES (4, 'Pasta', 'A classic pasta dish', 7.99)",
        "INSERT INTO MenuItem (itemID, name, description, price) VALUES (5, 'Soda', 'A refreshing soda', 1.99)",

        // Vendor
        "INSERT INTO Vendor (vendorID, name, address, Email, cuisineType, password, contactNo) VALUES (1, 'Fast Food Inc', '123 Burger Ln', 'fastfood@example.com', 'Fast Food', 'hashed_password1', '1112223333')",
        "INSERT INTO Vendor (vendorID, name, address, Email, cuisineType, password, contactNo) VALUES (2, 'Pizza Place', '456 Pizza St', 'pizzaplace@example.com', 'Italian', 'hashed_password2', '2223334444')",
        "INSERT INTO Vendor (vendorID, name, address, Email, cuisineType, password, contactNo) VALUES (3, 'Healthy Eats', '789 Salad Blvd', 'healthyeats@example.com', 'Health Food', 'hashed_password3', '3334445555')",
        "INSERT INTO Vendor (vendorID, name, address, Email, cuisineType, password, contactNo) VALUES (4, 'Pasta Palace', '321 Pasta Rd', 'pastapalace@example.com', 'Italian', 'hashed_password4', '4445556666')",
        "INSERT INTO Vendor (vendorID, name, address, Email, cuisineType, password, contactNo) VALUES (5, 'Drink Depot', '654 Soda Ave', 'drinkdepot@example.com', 'Beverages', 'hashed_password5', '5556667777')",

        // DeliveryPersonnel
        "INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, Email, password, contactNo) VALUES (1, 'John', 'john@example.com', 'hashed_password1', '9998887777')",
        "INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, Email, password, contactNo) VALUES (2, 'Jane', 'jane@example.com', 'hashed_password2', '8887776666')",
        "INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, Email, password, contactNo) VALUES (3, 'Jake', 'jake@example.com', 'hashed_password3', '7776665555')",
        "INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, Email, password, contactNo) VALUES (4, 'Jill', 'jill@example.com', 'hashed_password4', '6665554444')",
        "INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, Email, password, contactNo) VALUES (5, 'Joe', 'joe@example.com', 'hashed_password5', '5554443333')",

        // Vehicle
        "INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (1, 'Car', 'ABC123', 1)",
        "INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (2, 'Bike', 'XYZ789', 1)",
        "INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (3, 'Truck', 'LMN456', 1)",
        "INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (4, 'Scooter', 'PQR234', 1)",
        "INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (5, 'Van', 'STU678', 1)",

        // OrderPlaced
        "INSERT INTO OrderPlaced (orderID, custID) VALUES (1, 1)",
        "INSERT INTO OrderPlaced (orderID, custID) VALUES (2, 2)",
        "INSERT INTO OrderPlaced (orderID, custID) VALUES (3, 3)",
        "INSERT INTO OrderPlaced (orderID, custID) VALUES (4, 4)",
        "INSERT INTO OrderPlaced (orderID, custID) VALUES (5, 5)",

        // OrderItem
        "INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (1, 1, 2, 11.98)",
        "INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (2, 2, 1, 8.99)",
        "INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (3, 3, 3, 14.97)",
        "INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (4, 4, 2, 15.98)",
        "INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (5, 5, 5, 9.95)",

        // HasItems
        "INSERT INTO HasItems (itemID, vendorID) VALUES (1, 1)",
        "INSERT INTO HasItems (itemID, vendorID) VALUES (2, 2)",
        "INSERT INTO HasItems (itemID, vendorID) VALUES (3, 3)",
        "INSERT INTO HasItems (itemID, vendorID) VALUES (4, 4)",
        "INSERT INTO HasItems (itemID, vendorID) VALUES (5, 5)",

        // Handles
        "INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (1, 1)",
        "INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (2, 2)",
        "INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (3, 3)",
        "INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (4, 4)",
        "INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (5, 5)",

        // Assigned
        "INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (1, 1)",
        "INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (2, 2)",
        "INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (3, 3)",
        "INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (4, 4)",
        "INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (5, 5)"
    ];

    foreach ($insertCommands as $sql) {
        executePlainSQL($sql);
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


