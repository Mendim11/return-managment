<?php
session_start();
include "db.php";

if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

header("Location: users.php");
exit;
?>