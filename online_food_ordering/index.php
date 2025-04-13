<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Restaurant</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('pic6.AVIF') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            text-align: center;
        }
        .welcome-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px 50px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 1rem;
            color: #fff;
            background-color: #ff5722;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

<div class="welcome-container">
    <h1>Welcome to Our Restaurant!</h1>
    <p>Experience the finest dining and exceptional service. Let us make your meal memorable!</p>
    <a href="login.php" class="btn">Login</a>
    <a href="register.php" class="btn">Register</a>
</div>

</body>
</html>
