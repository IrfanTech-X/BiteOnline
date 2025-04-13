<?php
// Include database connection
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert customer data
    $query = $conn->prepare("INSERT INTO users (user_name, email, password, role) VALUES (?, ?, ?, 'customer')");
    $query->bind_param("sss", $user_name, $email, $hashed_password);

    if ($query->execute()) {
        // Success message with a link to the login page
        echo "<script>
                alert('Registration successful! Redirecting to login page.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>alert('Error: " . $query->error . "');</script>";
    }

    $query->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('pic5.JPG') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Welcome to Our Restaurant!</h2>
        <p class="welcome-message">Register now to explore our menu and place your favorite orders.</p>
        <form method="POST" action="register.php">
            <label for="user_name">User Name:</label>
            <input type="text" id="user_name" name="user_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
