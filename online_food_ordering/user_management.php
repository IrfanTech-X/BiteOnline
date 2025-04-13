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

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// Handle delete user
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $delete_sql = "DELETE FROM users WHERE user_id = $user_id";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('User deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting user.');</script>";
    }
}

// Handle edit user (update status or role)
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];
    $role = $_POST['role'];

    $update_sql = "UPDATE users SET status = '$status', role = '$role' WHERE user_id = $user_id";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('User updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating user.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: #2c3e50;
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

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th, .user-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .user-table th {
            font-size: 1.1em;
            font-weight: 500;
        }

        .user-table td {
            font-size: 1em;
            font-weight: 400;
        }

        .btn {
            background-color: #2c3e50;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            margin: 5px;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .update-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-btn {
            background-color: #333;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>

<header>
    <h1>User Management</h1>
</header>

<div class="container">
    <h2>Manage Users</h2>

    <!-- User table -->
    <table class="user-table">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['role']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <a href="user_management.php?delete_user_id=<?php echo $row['user_id']; ?>" class="btn">Delete</a>
                <a href="user_management.php?edit_user_id=<?php echo $row['user_id']; ?>" class="btn">Edit</a>
            </td>
        </tr>
        <?php
            endwhile;
        else:
            echo "<tr><td colspan='5'>No users found</td></tr>";
        endif;
        ?>
    </table>

    <!-- Edit User Form (Only visible if an edit is requested) -->
    <?php if (isset($_GET['edit_user_id'])): ?>
        <?php
        $edit_user_id = $_GET['edit_user_id'];
        $edit_sql = "SELECT * FROM users WHERE user_id = $edit_user_id";
        $edit_result = mysqli_query($conn, $edit_sql);
        $user = mysqli_fetch_assoc($edit_result);
        ?>
        <div class="update-form">
            <h3>Edit User</h3>
            <form method="POST" action="user_management.php">
                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="customer" <?php echo ($user['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                </select><br><br>

                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select><br><br>

                <button type="submit" name="update_user" class="btn">Update User</button>
            </form>
        </div>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
