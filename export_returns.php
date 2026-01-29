<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    die("Access denied");
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=returns.xls");

$sql = "SELECT r.id, o.order_number, c.name AS customer,
               s.name AS shop, r.reason, r.status, r.return_type, r.created_at
FROM returns r
JOIN orders o ON r.order_id=o.id
JOIN customers c ON o.customer_id=c.id
JOIN shops s ON o.shop_id=s.id
WHERE 1=1";

if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " AND o.order_number LIKE '%$search%'";
}
if (!empty($_GET['status'])) {
    $sql .= " AND r.status='{$_GET['status']}'";
}
if (!empty($_GET['type'])) {
    $sql .= " AND r.return_type='{$_GET['type']}'";
}

$sql .= " ORDER BY r.created_at DESC";

$result = $conn->query($sql);

echo "ID\tOrder\tCustomer\tShop\tReason\tStatus\tType\tDate\n";

while ($row = $result->fetch_assoc()) {
    echo "{$row['id']}\t{$row['order_number']}\t{$row['customer']}\t{$row['shop']}\t{$row['reason']}\t{$row['status']}\t{$row['return_type']}\t{$row['created_at']}\n";
}
