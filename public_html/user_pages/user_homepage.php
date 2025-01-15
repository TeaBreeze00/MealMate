<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "../../utils.php";

// Establish database connection
if (!connectToDB()) {
    die("Failed to connect to the database. Please try again later.");
}

// Get all vendors
function getAllVendors() {
    global $db_conn; // Ensure $db_conn is accessible in this function

    if (!$db_conn) {
        throw new Exception("Database connection not established");
    }

    $query = "SELECT * FROM VENDOR";
    $result = mysqli_query($db_conn, $query);

    if (!$result) {
        throw new Exception("Query failed: " . mysqli_error($db_conn));
    }

    $vendors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $vendors[] = $row;
    }

    mysqli_free_result($result);
    return $vendors;
}

// Get menu items for a vendor
function getMenuItems($vendorId) {
    global $db_conn;
    $query = "SELECT * FROM MenuItem WHERE VendorID = ?";
    $stmt = mysqli_prepare($db_conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $vendorId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

try {
    $allVendors = getAllVendors();
    // Example: Display vendor names
    foreach ($allVendors as $vendor) {
        echo "<p>" . htmlspecialchars($vendor['name']) . "</p>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    error_log($e->getMessage());
} finally {
    if (isset($db_conn)) {
        mysqli_close($db_conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealMate - Home</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        /* Navbar styles */
        .navbar {
            background-color: white;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            height: 40px;
        }

        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #333;
        }

        /* Main content styles */
        .main-content {
            max-width: 1200px;
            margin: 80px auto 0;
            padding: 20px;
        }

        /* Vendor card styles */
        .vendors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .vendor-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .vendor-card h3 {
            margin-bottom: 10px;
            color: #333;
        }

        /* Cart styles */
        .cart-container {
            position: fixed;
            right: 20px;
            top: 80px;
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .button {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-content">
        <img src="../diagrams/logo.png" alt="MealMate Logo" class="logo">
        <div class="nav-links">
            <a href="user_homepage.php">Home</a>
            <a href="orders.php">My Orders</a>
            <a href="subscriptions.php">Subscriptions</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    <h1>All Vendors</h1>

    <!-- Vendors Grid -->
    <div class="vendors-grid">
        <?php foreach ($allVendors as $vendor): ?>
            <div class="vendor-card">
                <h3><?php echo htmlspecialchars($vendor['Name']); ?></h3>
                <p>Cuisine: <?php echo htmlspecialchars($vendor['CuisineType']); ?></p>
                <p>Address: <?php echo htmlspecialchars($vendor['Address']); ?></p>

                <!-- Menu Items -->
                <h4>Menu</h4>
                <?php
                $menuItems = getMenuItems($vendor['VendorID']);
                while ($item = mysqli_fetch_assoc($menuItems)):
                    ?>
                    <div class="menu-item">
                        <p><?php echo htmlspecialchars($item['Name']); ?> - $<?php echo htmlspecialchars($item['Price']); ?></p>
                        <button class="button" onclick="addToCart(<?php echo $item['ItemID']; ?>, '<?php echo htmlspecialchars($item['Name']); ?>', <?php echo $item['Price']; ?>)">
                            Add to Cart
                        </button>
                    </div>
                <?php endwhile; ?>

                <!-- Subscribe Button -->
                <button class="button" onclick="subscribe(<?php echo $vendor['VendorID']; ?>)">
                    Subscribe to Monthly Plan
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Shopping Cart -->
    <div class="cart-container">
        <h2>Your Cart</h2>
        <div id="cart-items"></div>
        <div id="cart-total">Total: $0.00</div>
        <button class="button" onclick="checkout()">Checkout</button>
    </div>
</div>

<script>
    // Cart functionality
    let cart = [];

    function addToCart(itemId, name, price) {
        cart.push({ itemId, name, price });
        updateCartDisplay();
    }

    function updateCartDisplay() {
        const cartItems = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');

        cartItems.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <p>${item.name} - $${item.price}</p>
                </div>
            `).join('');

        const total = cart.reduce((sum, item) => sum + item.price, 0);
        cartTotal.textContent = `Total: $${total.toFixed(2)}`;
    }

    function checkout() {
        if (cart.length === 0) {
            alert('Your cart is empty!');
            return;
        }

        // Send cart data to server
        fetch('process_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(cart)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order placed successfully!');
                    cart = [];
                    updateCartDisplay();
                } else {
                    alert('Failed to place order. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
    }

    function subscribe(vendorId) {
        fetch('subscribe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ vendorId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Successfully subscribed to monthly plan!');
                } else {
                    alert('Failed to subscribe. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
    }
</script>
</body>
</html>
