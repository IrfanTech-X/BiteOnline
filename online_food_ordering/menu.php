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

// Fetch menu items from the database
$sql = "SELECT * FROM menu";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        header {
            background-color: #c0392b; /* Rich Red */
            color: white;
            padding: 30px 0;
            text-align: center;
            font-family: 'Playfair Display', serif;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 40px;
            letter-spacing: 2px;
            font-weight: 500; /* Reduced boldness */
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            font-size: 36px;
            color: #c0392b;
            margin-bottom: 40px;
            font-weight: 500; /* Reduced boldness */
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .menu-item .item-details {
            max-width: 75%;
        }

        .menu-item h3 {
            margin: 0;
            font-size: 24px;
            color: #c0392b;
            font-family: 'Playfair Display', serif;
            font-weight: 500; /* Reduced boldness */
        }

        .menu-item p {
            margin: 10px 0;
            color: #555;
            font-size: 16px;
        }

        .menu-item .price {
            font-size: 20px;
            font-weight: 400; /* Reduced boldness */
            color: #e74c3c;
        }

        .menu-item .order-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e74c3c;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 16px;
            text-transform: uppercase;
            transition: background-color 0.3s ease-in-out;
            font-weight: 500; /* Reduced boldness */
        }

        .menu-item .order-btn:hover {
            background-color: #c0392b;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #2c3e50;
            color: white;
            margin-top: 40px;
        }

        .back-btn {
            background-color: #2c3e50;
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500; /* Reduced boldness */
            margin-top: 30px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #e74c3c;
        }

        .menu-item img {
            max-width: 150px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Menu</h1>
</header>

<div class="container">
    <h2>Our Delicious Menu</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="menu-item">
                <div class="item-details">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="price">TK. <?php echo number_format($row['price'], 2); ?></p>
                </div>
                <a href="order.php?item_id=<?php echo $row['item_id']; ?>" class="order-btn">Order Now</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No items found in the menu.</p>
    <?php endif; ?>

    <a href="customer_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

<footer>
    <p>&copy; 2024 Online Food Ordering System | All Rights Reserved</p>
</footer>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
