<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
include "mail_config.php"; // contains sendMail()

$message = '';
$messageType = '';

$uid = $_SESSION['user_id'];
$action = "Updated return #$return_id";

$conn->query("INSERT INTO activity_logs (user_id, action) 
              VALUES ($uid, '$action')");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $return_id   = $_POST['return_id'];
    $status      = $_POST['status'];
    $return_type = $_POST['return_type'];

    $sql = "UPDATE returns 
            SET status = ?, return_type = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $return_type, $return_id);

    if ($stmt->execute()) {

        $subject = "Return Status Updated";
        $body    = "Return #$return_id status has been changed to <b>$status</b>.";

        if (sendMail("mendimgash1@hotmail.com", $subject, $body)) {
            $message = "Return updated and email sent!";
            $messageType = "success";
        } else {
            $message = "Return updated but failed to send email.";
            $messageType = "warning";
        }

    } else {
        $message = "Failed to update return.";
        $messageType = "error";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Return</title>

<style>
.popup-message {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 6px;
    color: white;
    font-weight: bold;
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.5s ease, transform 0.5s ease;
    transform: translateY(-20px);
}

.popup-message.show {
    opacity: 1;
    transform: translateY(0);
}

.popup-success { background: #28a745; }
.popup-warning { background: #ffc107; color: #333; }
.popup-error   { background: #dc3545; }
</style>

</head>
<body>

<?php if($message): ?>
<div id="popupMessage" class="popup-message 
<?= $messageType == 'success' ? 'popup-success' : 
   ($messageType == 'warning' ? 'popup-warning' : 'popup-error') ?>">
<?= $message ?>
</div>
<?php endif; ?>

<script>
const popup = document.getElementById('popupMessage');
if(popup){
    popup.classList.add('show');

    setTimeout(() => {
        popup.classList.remove('show');
        window.location.href = "list_returns.php";
    }, 2000);
}
</script>

</body>
</html>
