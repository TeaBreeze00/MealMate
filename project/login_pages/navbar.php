<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MealMate | Tiffin Delivery Simplified</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/navbar.css"> <!-- Link to your CSS file for navbar -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            height: 60px; /* Adjust the height as needed */
            display: flex;
            align-items: center;
            justify-content: space-between; /* Ensure space between logo/menu and buttons */
            padding: 0 20px;
            transition: background-color 0.3s ease-in-out;
            z-index: 1000;
        }

        .navbar.transparent {
            background-color: rgba(255, 255, 255, 0);
        }

        .navbar.opaque {
            background-color: rgba(255, 255, 255, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .menu-logo-container {
            display: flex;
            align-items: center;
        }

        .menu-icon {
            width: 24px; /* Adjust the size of the menu icon */
            height: 24px; /* Adjust the size of the menu icon */
            margin-right: 10px; /* Adjust the margin */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: black;
        }

        .menu-icon div {
            width: 100%;
            height: 4px;
            background-color: #000000;
        }

        .logo-container {
            height: 100%;
            overflow: hidden; /* Hide the overflow part of the image */
        }

        .logo {
            height: 35px;
            width: auto; /* Ensure the logo scales proportionally */
            object-fit: cover; /* Ensures the logo covers the container */
        }

        .buttons {
            display: flex;
            gap: 10px; /* Space between buttons */
            margin-right: 25px; /* Move buttons a little bit to the left */
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 20px; /* Make buttons oval-shaped */
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-login {
            background-color: white;
            color: black;
        }

        .btn-signup {
            background-color: black;
            color: white;
        }

        .btn-login:hover {
            background-color: black;
            color: white;
        }

        .btn-signup:hover {
            background-color: white;
            color: black;
        }

        @media (max-width: 600px) {
            .navbar {
                height: 60px; /* Adjust the height for smaller screens */
                padding: 0 10px;
            }

            .menu-icon {
                width: 24px; /* Adjust the size of the menu icon for smaller screens */
                height: 24px; /* Adjust the size of the menu icon for smaller screens */
                margin-right: 10px; /* Adjust the margin for smaller screens */
            }

            .menu-icon div {
                height: 3px;
            }

            .logo-container {
                height: 100%;
                overflow: hidden; /* Hide the overflow part of the image */
            }

            .logo {
                height: 60% !important; /* Ensure this value to make the logo smaller for smaller screens */
                width: auto;
                object-fit: cover; /* Ensures the logo covers the container */
            }

            .buttons {
                display: none; /* Hide buttons on smaller screens */
            }
        }
    </style>
</head>
<body>

<div class="navbar transparent" id="navbar">
    <div class="menu-logo-container">
        <div class="menu-icon">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="logo-container">
            <img src="../diagrams/logo.png" alt="Logo" class="logo">
        </div>
    </div>
    <div class="buttons">
        <a href="login.php" class="btn btn-login" style="font-family: Arial">Login</a>
        <a href="signup.php" class="btn btn-signup" style="font-family: Arial">Sign Up</a>
    </div>
</div>

<script>
    window.addEventListener('scroll', function() {
        var navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.remove('transparent');
            navbar.classList.add('opaque');
        } else {
            navbar.classList.remove('opaque');
            navbar.classList.add('transparent');
        }
    });
</script>

</body>
</html>



