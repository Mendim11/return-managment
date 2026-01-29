<?php
session_start();
include "navbar.php";
include "db.php";
// COUNTS
$totalReturns = $conn->query("SELECT COUNT(*) AS c FROM returns")->fetch_assoc()['c'];

$pending = $conn->query("SELECT COUNT(*) AS c FROM returns WHERE status='pending'")->fetch_assoc()['c'];
$approved = $conn->query("SELECT COUNT(*) AS c FROM returns WHERE status='approved'")->fetch_assoc()['c'];
$rejected = $conn->query("SELECT COUNT(*) AS c FROM returns WHERE status='rejected'")->fetch_assoc()['c'];

$totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
   

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
</head>
<body>
<div class="container">

<div class="card total">
    <p>Total Returns</p>
    <h2><?= $totalReturns ?></h2>
</div>

<div class="card pending">
    <p>Pending</p>
    <h2><?= $pending ?></h2>
</div>

<div class="card approved">
    <p>Approved</p>
    <h2><?= $approved ?></h2>
</div>

<div class="card rejected">
    <p>Rejected</p>
    <h2><?= $rejected ?></h2>
</div>

<div class="card users">
    <p>Total Users</p>
    <h2><?= $totalUsers ?></h2>
</div>

</div>

<h2>Dashboard</h2>

<p>You are logged in as: <?php echo $_SESSION['role']; ?></p>

<ul>
    <li><a href="list_returns.php">Manage Returns</a></li>
    <li><a href="add_return.php">Add Return</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
<style>
.container {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card {
    padding:20px;
    border-radius:8px;
    color:white;
    text-align:center;
    font-family:Arial;
}

.total {background:#007bff;}
.pending {background:#ffc107;}
.approved {background:#28a745;}
.rejected {background:#dc3545;}
.users {background:#6f42c1;}
</style>
</body>
</html>

