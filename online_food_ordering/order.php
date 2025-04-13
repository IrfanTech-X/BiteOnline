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

// Initialize order details array if not already set
if (!isset($_SESSION['order_items'])) {
    $_SESSION['order_items'] = [];
}

// Check if an item is being added to the order
if (isset($_POST['add_to_order'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Fetch item details from the menu
    $sql = "SELECT * FROM menu WHERE item_id = $item_id";
    $result = mysqli_query($conn, $sql);
    $item = mysqli_fetch_assoc($result);

    // Add item to the order
    if ($item) {
        $_SESSION['order_items'][] = [
            'item_id' => $item['item_id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $quantity,
            'total' => $item['price'] * $quantity
        ];
    }
}

// Handle order submission
if (isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $total_amount = array_sum(array_column($_SESSION['order_items'], 'total'));
    $order_date = date('Y-m-d H:i:s');

    // Insert order into the orders table
    $sql = "INSERT INTO orders (user_id, total_amount, order_date) VALUES ($user_id, $total_amount, '$order_date')";
    if (mysqli_query($conn, $sql)) {
        // Get the last inserted order_id
        $order_id = mysqli_insert_id($conn);

        // Insert order items into the order_details table
        foreach ($_SESSION['order_items'] as $order_item) {
            $item_id = $order_item['item_id'];
            $quantity = $order_item['quantity'];
            $total = $order_item['total'];

            $sql = "INSERT INTO order_details (order_id, item_id, quantity, price) VALUES ($order_id, $item_id, $quantity, $total)";
            mysqli_query($conn, $sql);
        }

        // Clear order items after placing the order
        $_SESSION['order_items'] = [];

        echo "<script>alert('Your order has been placed successfully!');</script>";
    } else {
        echo "<script>alert('Error placing the order. Please try again.');</script>";
    }
}

// Handle editing or removing an item from the order
if (isset($_POST['edit_item'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['new_quantity'];

    // Update the quantity or remove the item from the order
    foreach ($_SESSION['order_items'] as &$order_item) {
        if ($order_item['item_id'] == $item_id) {
            if ($new_quantity == 0) {
                // Remove the item
                $order_item = null;
            } else {
                // Update the quantity and total
                $order_item['quantity'] = $new_quantity;
                $order_item['total'] = $order_item['price'] * $new_quantity;
            }
            break;
        }
    }

    // Remove null entries (removed items)
    $_SESSION['order_items'] = array_filter($_SESSION['order_items']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Food</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: #c0392b;
            color: white;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 400;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .menu-item:hover {
            transform: translateY(-5px);
        }

        .menu-item h3 {
            margin: 0;
            color: #c0392b;
            font-size: 1.6em;
            font-weight: 500;
        }

        .menu-item p {
            margin: 5px 0;
            color: #666;
            font-size: 1em;
        }

        .menu-item .price {
            font-size: 1.3em;
            color: #e74c3c;
            font-weight: 400;
        }

        .order-btn {
            background-color: #e74c3c;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            display: inline-block;
            margin-top: 5px;
            font-weight: 400;
            transition: background-color 0.3s ease;
        }

        .order-btn:hover {
            background-color: #e5533d;
        }

        .order-summary {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-summary th, .order-summary td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-summary th {
            font-size: 1.1em;
            font-weight: 500;
        }

        .order-summary td {
            font-size: 1em;
            font-weight: 400;
        }

        .order-summary td strong {
            font-size: 1.2em;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #333;
            color: white;
            margin-top: 40px;
        }

        .back-btn {
            background-color: #333;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-weight: 400;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #FF6347;
        }
    </style>
</head>
<body>

<header>
    <h1>Order Food</h1>
</header>

<div class="container">
    <h2>Choose Your Items</h2>

    <!-- Fetch the menu items from the database -->
    <?php
    $sql = "SELECT * FROM menu";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
    ?>
        <div class="menu-item">
            <div class="item-details">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p class="price">TK. <?php echo number_format($row['price'], 2); ?></p>
            </div>
            <form method="POST" action="order.php">
                <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                <input type="number" name="quantity" min="1" value="1" required>
                <button type="submit" name="add_to_order" class="order-btn">Add to Cart</button>
            </form>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No items available in the menu.</p>";
    endif;
    ?>

    <!-- Display order summary -->
    <?php if (!empty($_SESSION['order_items'])): ?>
        <div class="order-summary">
            <h3>Your Order Summary</h3>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php
                $total = 0;
                foreach ($_SESSION['order_items'] as $order_item):
                    $total += $order_item['total'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($order_item['name']); ?></td>
                    <td><?php echo $order_item['quantity']; ?></td>
                    <td>TK. <?php echo number_format($order_item['price'], 2); ?></td>
                    <td>TK. <?php echo number_format($order_item['total'], 2); ?></td>
                    <td>
                        
                        <!-- Remove Button -->
                        <form method="POST" action="order.php" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?php echo $order_item['item_id']; ?>">
                            <input type="hidden" name="new_quantity" value="0">
                            <button type="submit" name="edit_item" class="order-btn" style="background-color: #e74c3c;">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="text-align:right;"><strong>Total</strong></td>
                    <td><strong>TK. <?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </table>
            <form method="POST" action="order.php">
                <button type="submit" name="place_order" class="order-btn">Place Order</button>
            </form>
        </div>
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
