<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>



<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $return_id = $_POST['return_id'];

    // Delete from returns table
    $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
    $stmt->bind_param("i", $return_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: list_returns.php"); // go back to admin panel
exit;
