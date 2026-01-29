<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $return_id = $_POST['return_id'];
    $status    = $_POST['status'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE returns SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $return_id);

    if ($stmt->execute()) {
        // redirect back to list
        header("Location: list_returns.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
