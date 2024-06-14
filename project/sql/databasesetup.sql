/* Drop Statements */

DROP TABLE IF EXISTS Assigned;
DROP TABLE IF EXISTS Handles;
DROP TABLE IF EXISTS HasItems;
DROP TABLE IF EXISTS OrderItem;
DROP TABLE IF EXISTS OrderPlaced;
DROP TABLE IF EXISTS Vehicle;
DROP TABLE IF EXISTS DeliveryPersonnel;
DROP TABLE IF EXISTS Vendor;
DROP TABLE IF EXISTS MenuItem;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Customer;

/* Entities */

CREATE TABLE Customer (
    custID INT PRIMARY KEY,
    Name VARCHAR(100),
    Address CHAR(100),
    Email VARCHAR(100),
    PhoneNo VARCHAR(15)
);

CREATE TABLE Orders (
    orderID INT PRIMARY KEY,
    OrderDate DATE,
    DeliveryAddress VARCHAR(255)
);

CREATE TABLE MenuItem (
    itemID INT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2)
);

CREATE TABLE Vendor (
    vendorID INT PRIMARY KEY,
    name VARCHAR(100),
    address VARCHAR(255),
    cuisineType VARCHAR(50),
    contactNo VARCHAR(15)
);

CREATE TABLE DeliveryPersonnel (
    DeliveryPersonId INT PRIMARY KEY,
    name VARCHAR(100),
    contactNo VARCHAR(15)
);

CREATE TABLE Vehicle (
    vehicleId INT PRIMARY KEY,
    type VARCHAR(50),
    registrationNo VARCHAR(20),
    availabilityStatus BOOLEAN
);

/* Relationships */

CREATE TABLE OrderPlaced (
    orderID INT PRIMARY KEY,
    custID INT NOT NULL,
    FOREIGN KEY (custID) REFERENCES Customer(custID) ON DELETE CASCADE,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE
);

CREATE TABLE OrderItem (
    orderID INT,
    itemID INT NOT NULL,
    quantity INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (orderID, itemID),
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (itemID) REFERENCES MenuItem(itemID) ON DELETE CASCADE
);

CREATE TABLE HasItems (
    itemID INT PRIMARY KEY,
    vendorID INT NOT NULL,
    FOREIGN KEY (itemID) REFERENCES MenuItem(itemID) ON DELETE CASCADE,
    FOREIGN KEY (vendorID) REFERENCES Vendor(vendorID) ON DELETE CASCADE
);

CREATE TABLE Handles (
    orderID INT PRIMARY KEY,
    DeliveryPersonId INT NOT NULL,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (DeliveryPersonId) REFERENCES DeliveryPersonnel(DeliveryPersonId) ON DELETE CASCADE
);

CREATE TABLE Assigned (
    DeliveryPersonId INT PRIMARY KEY,
    vehicleId INT NOT NULL,
    FOREIGN KEY (DeliveryPersonId) REFERENCES DeliveryPersonnel(DeliveryPersonId) ON DELETE CASCADE,
    FOREIGN KEY (vehicleId) REFERENCES Vehicle(vehicleId) ON DELETE CASCADE
);

/* Insert Statements */

-- Customer
INSERT INTO Customer (custID, Name, Address, Email, PhoneNo) VALUES (1, 'Alice', '123 Main St', 'alice@example.com', '1234567890');
INSERT INTO Customer (custID, Name, Address, Email, PhoneNo) VALUES (2, 'Bob', '456 Elm St', 'bob@example.com', '0987654321');
INSERT INTO Customer (custID, Name, Address, Email, PhoneNo) VALUES (3, 'Carol', '789 Oak St', 'carol@example.com', '1231231234');
INSERT INTO Customer (custID, Name, Address, Email, PhoneNo) VALUES (4, 'David', '321 Pine St', 'david@example.com', '9879879876');
INSERT INTO Customer (custID, Name, Address, Email, PhoneNo) VALUES (5, 'Eve', '654 Cedar St', 'eve@example.com', '4564564567');

-- Orders
INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (1, '2023-06-01', '123 Main St');
INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (2, '2023-06-02', '456 Elm St');
INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (3, '2023-06-03', '789 Oak St');
INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (4, '2023-06-04', '321 Pine St');
INSERT INTO Orders (orderID, OrderDate, DeliveryAddress) VALUES (5, '2023-06-05', '654 Cedar St');

-- MenuItem
INSERT INTO MenuItem (itemID, name, description, price) VALUES (1, 'Burger', 'A delicious burger', 5.99);
INSERT INTO MenuItem (itemID, name, description, price) VALUES (2, 'Pizza', 'A cheesy pizza', 8.99);
INSERT INTO MenuItem (itemID, name, description, price) VALUES (3, 'Salad', 'A healthy salad', 4.99);
INSERT INTO MenuItem (itemID, name, description, price) VALUES (4, 'Pasta', 'A classic pasta dish', 7.99);
INSERT INTO MenuItem (itemID, name, description, price) VALUES (5, 'Soda', 'A refreshing soda', 1.99);

-- Vendor
INSERT INTO Vendor (vendorID, name, address, cuisineType, contactNo) VALUES (1, 'Fast Food Inc', '123 Burger Ln', 'Fast Food', '1112223333');
INSERT INTO Vendor (vendorID, name, address, cuisineType, contactNo) VALUES (2, 'Pizza Place', '456 Pizza St', 'Italian', '2223334444');
INSERT INTO Vendor (vendorID, name, address, cuisineType, contactNo) VALUES (3, 'Healthy Eats', '789 Salad Blvd', 'Health Food', '3334445555');
INSERT INTO Vendor (vendorID, name, address, cuisineType, contactNo) VALUES (4, 'Pasta Palace', '321 Pasta Rd', 'Italian', '4445556666');
INSERT INTO Vendor (vendorID, name, address, cuisineType, contactNo) VALUES (5, 'Drink Depot', '654 Soda Ave', 'Beverages', '5556667777');

-- DeliveryPersonnel
INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, contactNo) VALUES (1, 'John', '9998887777');
INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, contactNo) VALUES (2, 'Jane', '8887776666');
INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, contactNo) VALUES (3, 'Jake', '7776665555');
INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, contactNo) VALUES (4, 'Jill', '6665554444');
INSERT INTO DeliveryPersonnel (DeliveryPersonId, name, contactNo) VALUES (5, 'Joe', '5554443333');

-- Vehicle
INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (1, 'Car', 'ABC123', TRUE);
INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (2, 'Bike', 'XYZ789', TRUE);
INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (3, 'Truck', 'LMN456', TRUE);
INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (4, 'Scooter', 'PQR234', TRUE);
INSERT INTO Vehicle (vehicleId, type, registrationNo, availabilityStatus) VALUES (5, 'Van', 'STU678', TRUE);

-- OrderPlaced
INSERT INTO OrderPlaced (orderID, custID) VALUES (1, 1);
INSERT INTO OrderPlaced (orderID, custID) VALUES (2, 2);
INSERT INTO OrderPlaced (orderID, custID) VALUES (3, 3);
INSERT INTO OrderPlaced (orderID, custID) VALUES (4, 4);
INSERT INTO OrderPlaced (orderID, custID) VALUES (5, 5);

-- OrderItem
INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (1, 1, 2, 11.98);
INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (2, 2, 1, 8.99);
INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (3, 3, 3, 14.97);
INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (4, 4, 2, 15.98);
INSERT INTO OrderItem (orderID, itemID, quantity, amount) VALUES (5, 5, 5, 9.95);

-- HasItems
INSERT INTO HasItems (itemID, vendorID) VALUES (1, 1);
INSERT INTO HasItems (itemID, vendorID) VALUES (2, 2);
INSERT INTO HasItems (itemID, vendorID) VALUES (3, 3);
INSERT INTO HasItems (itemID, vendorID) VALUES (4, 4);
INSERT INTO HasItems (itemID, vendorID) VALUES (5, 5);

-- Handles
INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (1, 1);
INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (2, 2);
INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (3, 3);
INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (4, 4);
INSERT INTO Handles (orderID, DeliveryPersonId) VALUES (5, 5);

-- Assigned
INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (1, 1);
INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (2, 2);
INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (3, 3);
INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (4, 4);
INSERT INTO Assigned (DeliveryPersonId, vehicleId) VALUES (5, 5);
