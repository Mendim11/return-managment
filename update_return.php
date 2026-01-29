<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $return_id = $_POST['return_id'];
    $status = $_POST['status'];
    $return_type = $_POST['return_type'];

    $sql = "UPDATE returns 
            SET status = ?, return_type = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $return_type, $return_id);
    $stmt->execute();

    $stmt->close();
}

header("Location: list_returns.php");
exit;
