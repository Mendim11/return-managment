<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>




<?php
include "navbar.php";
include "db.php";

/* INSERT NEW RETURN */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $order_id    = $_POST['order_id'];
    $reason      = $_POST['reason'];
    $status      = $_POST['status'];
    $return_type = $_POST['return_type'];

    $stmt = $conn->prepare("
        INSERT INTO returns 
        (order_id, reason, status, return_type, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("isss", $order_id, $reason, $status, $return_type);
    $stmt->execute();
    $stmt->close();

    header("Location: list_returns.php");
    exit;
}

/* LOAD ORDERS */
$sql = "SELECT orders.id, orders.order_number, customers.name AS customer_name
        FROM orders
        JOIN customers ON orders.customer_id = customers.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Return</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f8;
}

.box{
    width:400px;
    margin:60px auto;
    background:white;
    padding:25px;
    border-radius:6px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

input,select,button{
    width:100%;
    padding:8px;
    margin-top:8px;
}

button{
    background:#007bff;
    color:white;
    border:none;
    cursor:pointer;
}

button:hover{
    background:#0056b3;
}
</style>

</head>
<body>

<div class="box">

<h2>Add New Return</h2>

<form method="POST">

<label>Order</label>
<select name="order_id" required>
    <option value="">Select order</option>

    <?php while($row = $result->fetch_assoc()): ?>
        <option value="<?php echo $row['id']; ?>">
            Order #<?php echo $row['order_number']; ?> - <?php echo $row['customer_name']; ?>
        </option>
    <?php endwhile; ?>
</select>

<label>Reason</label>
<input type="text" name="reason" required>

<label>Status</label>
<select name="status">
    <option value="pending">Pending</option>
    <option value="approved">Approved</option>
    <option value="rejected">Rejected</option>
</select>

<label>Return Type</label>
<select name="return_type">
    <option value="refund">Refund</option>
    <option value="exchange">Exchange</option>
    <option value="credit">Credit</option>
</select>

<button type="submit">Save Return</button>

</form>

</div>

</body>
</html>
