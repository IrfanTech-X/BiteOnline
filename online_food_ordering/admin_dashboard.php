<?php
// Include the database connection
include 'db_connect.php';

// Start session
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not an admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            color: #fff;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 40px;
            margin: 0;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
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
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-align: center;
            padding: 30px;
            position: relative;
            background: linear-gradient(145deg, #f1f1f1, #ffffff);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card a {
            text-decoration: none;
            color: inherit;
            display: block;
            padding: 15px;
        }

        .card h3 {
            font-size: 28px;
            color: #2C3E50;
            margin: 0;
            font-weight: 600;
        }

        .card p {
            color: #7F8C8D;
            font-size: 16px;
            margin-top: 10px;
        }

        .card .icon {
            font-size: 40px;
            color: #E74C3C;
            margin-bottom: 20px;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #2C3E50;
            color: #fff;
            margin-top: 40px;
            font-size: 14px;
        }

        footer p {
            font-size: 14px;
        }

        .card a:hover {
            background-color: #2980b9;
            color: #fff;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

    </style>
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
</header>

<div class="container">
    <div class="welcome-message">
        <h2>Welcome, Admin!</h2>
        <p>Manage your system with ease using the options below.</p>
    </div>

    <div class="dashboard">
        <div class="card">
            <a href="manage_menu.php">
                <div class="icon">🍽️</div>
                <h3>Manage Menu</h3>
                <p>Add, edit, or remove menu items</p>
            </a>
        </div>
        <div class="card">
            <a href="view_orders.php">
                <div class="icon">📦</div>
                <h3>View Orders</h3>
                <p>Check customer orders and their status</p>
            </a>
        </div>
        <div class="card">
            <a href="analytics.php">
                <div class="icon">📊</div>
                <h3>Analytics</h3>
                <p>View sales and performance data</p>
            </a>
        </div>
        <div class="card">
    <a href="user_management.php">
        <div class="icon">🧑‍💼</div> <!-- Professional person icon -->
        <h3>User Management</h3>
        <p>Manage users and their roles</p>
    </a>
</div>

        <div class="card">
            <a href="logout.php">
                <div class="icon">🚪</div>
                <h3>Logout</h3>
                <p>Securely exit the dashboard</p>
            </a>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 Online Food Ordering System | Admin Panel</p>
</footer>

</body>
</html>
