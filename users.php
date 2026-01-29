<?php
session_start();
include "navbar.php";
include "db.php";

// Restrict access to admin only
if ($_SESSION['role'] != 'admin') {
    die("<p style='color:red; text-align:center;'>Access denied</p>");
}

// CREATE USER
$createMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "staff";

    $stmt = $conn->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
    $stmt->bind_param("sss", $username,$password,$role);

    if ($stmt->execute()) {
        $createMessage = "Staff user <strong>" . htmlspecialchars($username) . "</strong> created successfully!";
    } else {
        $createMessage = "Error creating user";
    }
}

// LIST USERS
$result = $conn->query("SELECT id,username,role FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 20px;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

h2 {
    color: #000000;
    margin-bottom: 15px;
}

form {
    display: flex;
    flex-direction: column;
    margin-bottom: 25px;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

input[type="text"], input[type="password"] {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

input[type="text"]:focus, input[type="password"]:focus {
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
   ;
}
.buttondelete {
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    width: 20%;
}

button:hover {
    background-color: #0056b3;
}

.message {
    margin-bottom: 20px;
    color: green;
    font-weight: bold;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007BFF;
    color: white;
}

td form {
    margin: 0;
}
</style>
</head>
<body>

<div class="container">

    <h2>Create Staff User</h2>

    <?php if($createMessage): ?>
        <div class="message"><?= $createMessage ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button class="buttondelete" type="submit">Create Staff</button>
    </form>

    <hr>

    <h2>All Users</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['role'] ?></td>
            <td>
                <?php if ($row['role'] != 'admin'): ?>
                    <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" style="background-color:#dc3545;">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>

<?php $conn->close(); ?>
