<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_menu'])) {
        // Add new menu item
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock']; // Assuming you want to add stock as well

        // Prepared statement to insert menu item
        $stmt = $conn->prepare("INSERT INTO menu (name, description, price, stock) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $description, $price, $stock);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Menu item added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }

    if (isset($_POST['delete_menu'])) {
        // Ensure the ID is set before trying to delete
        $item_id = $_POST['menu_id'];
        if (empty($item_id)) {
            echo "<div class='alert alert-danger'>Menu ID is missing! Please ensure that the menu ID is passed correctly.</div>";
        } else {
            // Prepared statement to delete menu item
            $stmt = $conn->prepare("DELETE FROM menu WHERE item_id = ?");
            $stmt->bind_param("i", $item_id); // 'i' means integer
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Menu item deleted successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }
            $stmt->close();
        }
    }

    if (isset($_POST['edit_menu'])) {
        // Edit menu item
        $item_id = $_POST['item_id'];
        $name = $_POST['edit_name'];
        $description = $_POST['edit_description'];
        $price = $_POST['edit_price'];

        // Prepared statement to update menu item
        $stmt = $conn->prepare("UPDATE menu SET name = ?, description = ?, price = ? WHERE item_id = ?");
        $stmt->bind_param("ssdi", $name, $description, $price, $item_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Menu item updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

// Fetch all menu items
$menu_sql = "SELECT * FROM menu";
$menu_result = $conn->query($menu_sql);

// Check if there is any result
if (!$menu_result) {
    echo "<div class='alert alert-danger'>Error fetching menu items: " . $conn->error . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .panel {
            background-color: #fff;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h1, h3 {
            color: #2c3e50;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .menu-item-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            background-color: #fff;
            transition: all 0.3s ease;
        }
        .menu-item-card:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        .modal-header {
            background-color: #3498db;
            color: #fff;
        }
        .modal-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 8px;
        }
        .alert {
            text-align: center;
            font-size: 1.2rem;
        }
        .alert-success {
            background-color: #2ecc71;
            color: white;
        }
        .alert-danger {
            background-color: #e74c3c;
            color: white;
        }
        .card-header {
            background-color: #3498db;
            color: white;
            text-align: center;
            font-size: 1.5rem;
            padding: 15px;
        }
        .card-footer {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4">Manage Menu</h1>

    <!-- Add Menu Item Form -->
    <div class="panel">
        <h3 class="mb-4">Add New Menu Item</h3>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>

            <button type="submit" name="add_menu" class="btn btn-primary">Add Menu Item</button>
        </form>
    </div>

    <!-- Existing Menu Items -->
    <div class="panel">
        <h3 class="mb-4">Existing Menu Items</h3>
        <div class="row">
            <?php
            // Check if there are any menu items in the database
            if ($menu_result->num_rows > 0) {
                while ($row = $menu_result->fetch_assoc()) {
                    echo "
                    <div class='col-md-4'>
                        <div class='menu-item-card'>
                            <h4>{$row['name']}</h4>
                            <p><strong>Description:</strong> {$row['description']}</p>
                            <p><strong>Price:</strong> \${$row['price']}</p>
                            <p><strong>Stock:</strong> {$row['stock']}</p>
                            <div class='card-footer'>
                                <form method='POST' style='display:inline'>
                                    <input type='hidden' name='menu_id' value='{$row['item_id']}'>
                                    <button type='submit' name='delete_menu' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</button>
                                </form>
                                <button class='btn btn-warning' data-toggle='modal' data-target='#editModal' onclick='editMenu({$row['item_id']}, \"{$row['name']}\", \"{$row['description']}\", {$row['price']})'>Edit</button>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>No menu items found.</p>";
            }
            ?>
        </div>
    </div>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Menu Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST">
        <div class="modal-body">
            <input type="hidden" id="item_id" name="item_id">
            <div class="form-group">
                <label for="edit_name">Name:</label>
                <input type="text" class="form-control" id="edit_name" name="edit_name" required>
            </div>
            <div class="form-group">
                <label for="edit_description">Description:</label>
                <textarea class="form-control" id="edit_description" name="edit_description" required></textarea>
            </div>
            <div class="form-group">
                <label for="edit_price">Price:</label>
                <input type="number" class="form-control" id="edit_price" name="edit_price" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="edit_menu" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function editMenu(item_id, name, description, price) {
        document.getElementById('item_id').value = item_id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_price').value = price;
    }
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
