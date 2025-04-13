<?php
// Include the database connection
include 'db_connect.php';

// Start session
session_start();

// Check if user is logged in and is a customer
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php"); // Redirect to login if not logged in or not a customer
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>

    <!-- Link to Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        header {
            background-color: #2C3E50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 36px;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .background-section {
            background-image: url('pic4.jpg'); /* Add the image path here */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 50px 0;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome-message h2 {
            color: #2C3E50;
            font-size: 36px;
            font-weight: 700;
        }

        .welcome-message p {
            color: #7F8C8D;
            font-size: 18px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .card {
            background-color: #FF6347;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            position: relative;
            background: linear-gradient(145deg, #FF6347, #E74C3C);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .card a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        .card a:hover {
            text-decoration: underline;
            color: #FF6347;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #2C3E50;
            color: white;
            margin-top: 40px;
            font-size: 14px;
        }

        footer p {
            font-size: 14px;
        }

        .back-btn {
            background-color: #2C3E50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #FF6347;
        }

    </style>
</head>
<body>

<header>
    <h1>Customer Dashboard</h1>
</header>

<!-- Background Section -->
<div class="background-section">
    <div class="container">
        <div class="welcome-message">
            <h2>Welcome to Your Dashboard!</h2>
            <p>Manage your orders, explore the menu, and have a delightful experience!</p>
        </div>

        <div class="dashboard">
            <div class="card">
                <span style="font-size: 40px;">🍽</span>
                <h3>View Menu</h3>
                <p>Explore a wide variety of dishes.</p>
                <a href="menu.php">View Menu</a>
            </div>
            <div class="card">
                <span style="font-size: 40px;">🍔</span> <!-- Changed icon for Order Food -->
                <h3>Order Food</h3>
                <p>Order your favorite meals now.</p>
                <a href="order.php">Order Now</a>
            </div>
            <div class="card">
                <span style="font-size: 40px;">📑</span> <!-- Changed icon for My Orders -->
                <h3>My Orders</h3>
                <p>Track your past orders and their status.</p>
                <a href="my_orders.php">View Orders</a>
            </div>
        </div>

        <a href="logout.php" class="back-btn">Logout</a>
    </div>
</div>

<footer>
    <p>&copy; 2024 Online Food Ordering System | All Rights Reserved</p>
</footer>

</body>
</html>
