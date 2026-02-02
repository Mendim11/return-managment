<?php
session_start();
include 'navbar.php';
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get filter values
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$type = $_GET['type'] ?? '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build SQL query
$sql = "SELECT r.id, r.reason, r.status, r.return_type, r.created_at,
               o.order_number, c.name AS customer_name, s.name AS shop_name
        FROM returns r
        JOIN orders o ON r.order_id = o.id
        JOIN customers c ON o.customer_id = c.id
        JOIN shops s ON o.shop_id = s.id
        WHERE 1=1";

if ($search !== '') {
    $searchEsc = $conn->real_escape_string($search);
    $sql .= " AND o.order_number LIKE '%$searchEsc%'";
}

if ($status !== '') {
    $sql .= " AND r.status='$status'";
}

if ($type !== '') {
    $sql .= " AND r.return_type='$type'";
}

// Count total rows for pagination
$countSql = "SELECT COUNT(*) AS total
             FROM returns r
             JOIN orders o ON r.order_id=o.id
             JOIN customers c ON o.customer_id=c.id
             JOIN shops s ON o.shop_id=s.id
             WHERE 1=1";

if ($search !== '') $countSql .= " AND o.order_number LIKE '%$searchEsc%'";
if ($status !== '') $countSql .= " AND r.status='$status'";
if ($type !== '') $countSql .= " AND r.return_type='$type'";

$totalRows = $conn->query($countSql)->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$sql .= " ORDER BY r.created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns Admin Panel</title>
    <style>
        .pagination{
    margin-top:30px;
    text-align:center;
}

.pagination a{
    padding:8px 14px;
    margin:3px;
    background:#f1f1f1;
    color:#333;
    text-decoration:none;
    border-radius:5px;
    border:1px solid #ccc;
    font-weight:bold;
}

.pagination a:hover{
    background:#007bff;
    color:white;
}

.pagination a.active{
    background:#007bff;
    color:white;
    border-color:#007bff;
}

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 20px;
        }

        form {
            margin-bottom: 15px;
        }

        input, select, button {
            padding: 5px 8px;
            margin: 2px 5px 2px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #dc3545;
            color: white;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        select.status {
            font-weight: bold;
            color: white;
        }

        select.status.pending { background-color: orange; }
        select.status.approved { background-color: green; }
        select.status.rejected { background-color: red; }
        select.status option { background-color: white; color: black; }
    </style>
</head>
<body>

<!-- Filter Form -->
<form method="GET">
    <input type="text" name="search" placeholder="Search order number" value="<?= htmlspecialchars($search) ?>">

    <select name="status">
        <option value="">All Status</option>
        <option value="pending" <?= $status=='pending'?'selected':'' ?>>Pending</option>
        <option value="approved" <?= $status=='approved'?'selected':'' ?>>Approved</option>
        <option value="rejected" <?= $status=='rejected'?'selected':'' ?>>Rejected</option>
    </select>

    <select name="type">
        <option value="">All Types</option>
        <option value="refund" <?= $type=='refund'?'selected':'' ?>>Refund</option>
        <option value="exchange" <?= $type=='exchange'?'selected':'' ?>>Exchange</option>
        <option value="credit" <?= $type=='credit'?'selected':'' ?>>Credit</option>
    </select>

    <button type="submit">Filter</button>
</form>

<!-- Export Form -->
<form method="GET" action="export_returns.php">
    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
    <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
    <button type="submit">Export to Excel</button>
</form>

<hr>

<h2>All Returns</h2>

<?php
if ($result->num_rows > 0):
?>
<table>
    <tr>
        <th>Return ID</th>
        <th>Order</th>
        <th>Customer</th>
        <th>Shop</th>
        <th>Reason</th>
        <th>Type & Status</th>
        <th>Actions</th>
        <th></th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['order_number'] ?></td>
        <td><?= $row['customer_name'] ?></td>
        <td><?= $row['shop_name'] ?></td>
        <td><?= $row['reason'] ?></td>
        <td>
            <form method="POST" action="update_return.php">
                <input type="hidden" name="return_id" value="<?= $row['id'] ?>">

                Status:
                <select name="status" class="status <?= $row['status'] ?>" onchange="this.form.submit()">
                    <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending</option>
                    <option value="approved" <?= $row['status']=='approved'?'selected':'' ?>>Approved</option>
                    <option value="rejected" <?= $row['status']=='rejected'?'selected':'' ?>>Rejected</option>
                </select>

                Type:
                <select name="return_type" onchange="this.form.submit()">
                    <option value="refund" <?= $row['return_type']=='refund'?'selected':'' ?>>Refund</option>
                    <option value="exchange" <?= $row['return_type']=='exchange'?'selected':'' ?>>Exchange</option>
                    <option value="credit" <?= $row['return_type']=='credit'?'selected':'' ?>>Credit</option>
                </select>
            </form>
        </td>
        <td>
            <?php if($_SESSION['role']=='admin'): ?>
            <form method="POST" action="delete_return.php" onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="return_id" value="<?= $row['id'] ?>">
                <button type="submit">Delete</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- Pagination -->
<div class="pagination" style="margin-top:20px;">
<!-- Pagination -->
<div class="pagination">

<?php if($page > 1): ?>
    <a href="?<?= http_build_query(array_merge($_GET,['page'=>$page-1])) ?>">Prev</a>
<?php endif; ?>

<?php for($i=1;$i<=$totalPages;$i++): ?>
    <?php 
        $active = ($i==$page) ? 'class="active"' : ''; 
        $params = $_GET;
        $params['page'] = $i;
    ?>
    <a <?= $active ?> href="?<?= http_build_query($params) ?>"><?= $i ?></a>
<?php endfor; ?>

<?php if($page < $totalPages): ?>
    <a href="?<?= http_build_query(array_merge($_GET,['page'=>$page+1])) ?>">Next</a>
<?php endif; ?>

</div>


<?php else: ?>
<p>No returns found</p>
<?php endif; ?>

<script>
document.querySelectorAll("select.status").forEach(select => {
    select.addEventListener("change", function () {
        this.classList.remove("pending", "approved", "rejected");
        this.classList.add(this.value);
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
