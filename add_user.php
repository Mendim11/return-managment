<?php
session_start();
include "db.php";

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "User created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<h2>Add User</h2>

<form method="POST">
    Username:<br>
    <input type="text" name="username" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    Role:<br>
    <select name="role">
        <option value="staff">Staff</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <button type="submit">Create User</button>
</form>
 