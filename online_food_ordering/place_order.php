<?php
// Include the database connection
include 'db_connect.php';

// Start session to check if the user is logged in as a customer
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the necessary POST variables are set when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate that both item_id and quantity are provided
    if (!isset($_POST['item_id']) || !isset($_POST['quantity'])) {
        echo "Item ID and quantity are required.";
        exit();
    }

    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Validate that quantity is a positive integer
    if ($quantity <= 0) {
        echo "Quantity must be a positive number.";
        exit();
    }

    // Fetch item price from the menu
    $item_query = $conn->prepare("SELECT price FROM menu_items WHERE item_id = ?");
    $item_query->bind_param("i", $item_id);
    $item_query->execute();
    $item_query->store_result();
    $item_query->bind_result($price);

    // Check if item exists
    if ($item_query->num_rows === 0) {
        echo "Item not found.";
        exit();
    }

    $item_query->fetch();

    // Calculate total price
    $total_price = $price * $quantity;

    // Insert the order into the database
    $order_query = $conn->prepare("INSERT INTO orders (user_id, item_id, quantity, total_amount, order_date) 
                                   VALUES (?, ?, ?, ?, NOW())");
    $order_query->bind_param("iiid", $user_id, $item_id, $quantity, $total_price);

    // Execute the query and handle success or failure
    if ($order_query->execute()) {
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $order_query->error;
    }

    // Close database connections
    $order_query->close();
    $item_query->close();
    $conn->close();
} else {
    // Show the form to place an order
    echo '<h2>Place Your Order</h2>';
    echo '<form method="POST" action="order.php">
            <label for="item_id">Item ID:</label>
            <input type="number" name="item_id" id="item_id" required><br>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" required><br>

            <button type="submit">Place Order</button>
          </form>';
}
?>
