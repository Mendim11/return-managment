<?php
include "db.php";

$sql = "SELECT * FROM shops";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Shop ID: " . $row['id'] . " | Name: " . $row['name'] . "<br>";
    }
} else {
    echo "No shops found";
}

$conn->close();
?>
     