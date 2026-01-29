<?php
session_start();
include "db.php";

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$result = $conn->query("SELECT id, username, role FROM users");

echo "<h2>Users</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Username</th><th>Role</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td>{$row['role']}</td>
          </tr>";
}

echo "</table>";


