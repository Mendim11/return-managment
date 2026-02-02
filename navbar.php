<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set defaults if session variables are not set
$username = $_SESSION['username'] ?? 'Guest';
$role = $_SESSION['role'] ?? '';
?>

<style>
.navbar {
    background: #222;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.navbar a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.navbar a:hover {
    color: #ffc107;
}

.nav-right {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 5px;
}

.nav-username {
    color: #aaa;
}

.logout {
    color: #dc3545;
}
.navbar {
    margin-bottom: 25px;
}

</style>

<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="list_returns.php">Returns</a>
    <a href="add_return.php">Add Return</a>

    <?php if ($role === 'admin'): ?>
        <a href="users.php">Users</a>
    <?php endif; ?>

    <div class="nav-right">
        <span class="nav-username"><?= htmlspecialchars($username) ?></span>
        |
        <a class="logout" href="logout.php">Logout</a>
    </div>
</div>
