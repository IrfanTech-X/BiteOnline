<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE menu SET quantity = $quantity WHERE id = $id";
    $conn->query($sql);
}

$menu_sql = "SELECT * FROM menu";
$menu_result = $conn->query($menu_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Inventory</title>
</head>
<body>
    <h1>Update Inventory</h1>
    <form method="POST">
        <label for="menu_id">Select Menu Item:</label>
        <select id="menu_id" name="menu_id">
            <?php
            if ($menu_result->num_rows > 0) {
                while ($row = $menu_result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
            }
            ?>
        </select><br>
        <label for="quantity">New Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br>
        <button type="submit">Update Inventory</button>
    </form>
    <a href="admin.php">Back to Admin Panel</a>
</body>
</html>
