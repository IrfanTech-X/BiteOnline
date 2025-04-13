<?php
// Include the database connection
include 'db_connect.php';

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Query to fetch orders for the logged-in user, ordered by order_id in ascending order
$query = "
    SELECT orders.order_id, orders.total_amount, orders.order_date, orders.status,
           order_details.item_id, order_details.quantity, order_details.price, 
           menu.name AS item_name
    FROM orders
    JOIN order_details ON orders.order_id = order_details.order_id
    JOIN menu ON order_details.item_id = menu.item_id
    WHERE orders.user_id = ?
    ORDER BY orders.order_id ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #34495e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 20px;
            text-align: center;
        }

        th {
            background-color: #2980b9;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 500;
        }

        td {
            background-color: #ecf0f1;
            font-size: 1rem;
            font-weight: 400;
        }

        tr:nth-child(even) td {
            background-color: #f7f9f9;
        }

        tr:hover td {
            background-color: #bdc3c7;
            transition: background-color 0.3s ease;
        }

        .back-btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: #2c3e50;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 30px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 1px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #34495e;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #2c3e50;
            color: #fff;
            margin-top: 40px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }

            h2 {
                font-size: 1.5rem;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>My Orders</h1>
</header>

<div class="container">
    <h2>Your Order History</h2>

    <?php
    if ($result->num_rows > 0) {
        // Display user orders in a table
        echo "<table>
                <tr>
                    <th>Order ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>TK. {$row['price']}</td>
                    <td>TK. {$row['total_amount']}</td>
                    <td>{$row['order_date']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center; color: #888;'>You have not placed any orders yet.</p>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
    ?>

    <!-- Back Button -->
    <a href="customer_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

<footer>
    <p>&copy; 2024 Online Food Ordering System | All Rights Reserved</p>
</footer>

</body>
</html>
