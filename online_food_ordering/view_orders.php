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

// Query to fetch orders, user details, and related menu item details with order_id in ascending order
$query = "
    SELECT 
        orders.order_id, 
        users.user_name AS user_name, 
        orders.total_amount, 
        orders.order_date, 
        orders.status, 
        order_details.item_id, 
        menu.name AS item_name, 
        order_details.quantity, 
        order_details.price
    FROM 
        orders
    JOIN 
        users ON orders.user_id = users.user_id
    JOIN 
        order_details ON orders.order_id = order_details.order_id
    JOIN 
        menu ON order_details.item_id = menu.item_id
    ORDER BY 
        orders.order_id ASC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 34px; /* Slightly reduced font size */
            margin: 0;
            font-weight: 500; /* Reduced boldness */
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        h2 {
            font-size: 26px; /* Adjusted size for consistency */
            margin-bottom: 20px;
            color: #2c3e50;
            font-weight: 500; /* Reduced boldness */
        }

        .order-card {
            background-color: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
        }

        .order-card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .order-card-header h3 {
            font-size: 20px; /* Slightly reduced size */
            margin: 0;
            color: #2c3e50;
            font-weight: 500; /* Reduced boldness */
        }

        .order-card-header span {
            font-size: 16px; /* Reduced font size */
            color: #e67e22;
            font-weight: 400; /* Normal weight */
        }

        .order-details {
            margin-top: 10px;
        }

        .order-details div {
            margin-bottom: 10px;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #2980b9;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            font-weight: 500; /* Reduced boldness */
        }

        .back-btn:hover {
            background-color: #3498db;
        }

        footer {
            text-align: center;
            padding: 15px 0;
            background-color: #2c3e50;
            color: white;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            font-weight: 400; /* Reduced boldness */
        }

        th {
            background-color: #2980b9;
            color: white;
            font-weight: 500; /* Slightly bold for headers */
        }

        td {
            background-color: #ecf0f1;
        }

        td:hover {
            background-color: #bdc3c7;
            cursor: pointer;
        }

    </style>
</head>
<body>

<header>
    <h1>View Orders</h1>
</header>

<div class="container">
    <h2>Order Details</h2>

    <?php
    if ($result->num_rows > 0) {
        // Display orders in a table
        echo "<table>
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['user_name']}</td>
                    <td>{$row['total_amount']}</td>
                    <td>{$row['order_date']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No orders found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>

    <!-- Back Button to Admin Dashboard -->
    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

<footer>
    <p>&copy; 2024 Online Food Ordering System | Admin Panel</p>
</footer>

</body>
</html>
