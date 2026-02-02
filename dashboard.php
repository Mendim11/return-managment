<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";
include "navbar.php";

$logs = $conn->query("
SELECT activity_logs.*, users.username
FROM activity_logs
JOIN users ON activity_logs.user_id = users.id
ORDER BY activity_logs.created_at DESC
LIMIT 5
");

/* COUNTS */
$totalReturns = $conn->query("SELECT COUNT(*) AS c FROM returns")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) AS c FROM returns WHERE status='pending'")->fetch_assoc()['c'];
$approved = $conn->query("SELECT COUNT(*) AS c FROM returns WHERE status='approved'")->fetch_assoc()['c'];
$rejected = $conn->query("SELECT COUNT(*) AS c FROM returns WHERE status='rejected'")->fetch_assoc()['c'];
$totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];

/* RECENT ACTIVITY */
$recent = $conn->query("
SELECT r.id, o.order_number, r.status, r.created_at
FROM returns r
JOIN orders o ON r.order_id = o.id
ORDER BY r.created_at DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family:Arial;
    background:#f4f6f8;
}

.container {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap:20px;
    margin-bottom:40px;
}

.card {
    padding:25px;
    border-radius:10px;
    color:white;
    text-align:center;
    box-shadow:0 2px 6px rgba(0,0,0,.15);
}

.total {background:#007bff;}
.pending {background:#ffc107;}
.approved {background:#28a745;}
.rejected {background:#dc3545;}
.users {background:#6f42c1;}

.activity{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 6px rgba(0,0,0,.15);
}

.activity p{
    padding:8px 0;
    border-bottom:1px solid #eee;
}
.chart{
    width:80vh;
    margin:20px auto;   
}

</style>

</head>
<body>

<h2>Dashboard Overview</h2>

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

<h3>Recent Activity</h3>

<table>
<tr>
    <th>User</th>
    <th>Action</th>
    <th>Date</th>
</tr>

<?php while($l = $logs->fetch_assoc()): ?>
<tr>
    <td><?= $l['username'] ?></td>
    <td><?= $l['action'] ?></td>
    <td><?= $l['created_at'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</div>

<h3>Returns Status Chart</h3>
<div class="chart"> 
<canvas id="returnsChart" height="100"></canvas>
</div>

<div class="chart">
    <script>
const ctx = document.getElementById('returnsChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            data: [<?= $pending ?>, <?= $approved ?>, <?= $rejected ?>],
            backgroundColor: ['orange','green','red']
        }]
    }
});
</script>
</div>

<div class="activity">
<h3>Recent Returns</h3>

<?php
while($r = $recent->fetch_assoc()){
    echo "<p>
    #{$r['id']} | Order {$r['order_number']} | {$r['status']} | {$r['created_at']}
    </p>";
}
?>
</div>

</body>
</html>
