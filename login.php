<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Wrong password";
        }

    } else {
        $error = "User not found";
    }
}
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
        background: #f4f6f8;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background: #fff;
        padding: 30px 40px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #007BFF;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    input[type="text"],
    input[type="password"] {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
    }

    button {
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    .error {
        color: #dc3545;
        margin-bottom: 15px;
        text-align: center;
    }

    @media (max-width: 450px) {
        .login-container {
            padding: 20px;
        }
    }
</style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
