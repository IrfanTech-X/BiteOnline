<?php
// Include the database connection
include 'db_connect.php';

// Start session to store user data after login
session_start();

// Static admin credentials
$admin_email = "admin@example.com"; // Static admin email
$admin_password = "admin123"; // Static admin password

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the entered credentials match the static admin credentials
    if ($email == $admin_email && $password == $admin_password) {
        // If admin login is successful
        $_SESSION['user_id'] = 1; // Admin ID (you can assign any value)
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'admin'; // Assign role as admin

        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // For customer login, check the database for the entered email and password
        $query = $conn->prepare("SELECT user_id, password, role FROM users WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $query->store_result();
        $query->bind_result($user_id, $stored_password, $role);

        if ($query->num_rows > 0) {
            $query->fetch();
            // Verify the password for customer
            if (password_verify($password, $stored_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role; // Store user role

                // Redirect based on user role
                if ($role == 'admin') {
                    header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                } else {
                    header("Location: customer_dashboard.php"); // Redirect to customer dashboard
                }
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "User not found.";
        }

        $query->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('pic4.JPG') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        .login-container label {
            margin-bottom: 5px;
            text-align: left;
            font-weight: bold;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container input[type="submit"] {
            padding: 10px;
            background: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-container input[type="submit"]:hover {
            background: #4cae4c;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Welcome Back!</h2>
    <p>Please log in to continue.</p>
    <?php if (isset($error_message)) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
