<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "navbar.php";
include "db.php";

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

/* FUNCTION TO SEND EMAIL USING PHPMailer */
function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';       // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'a12e53001@smtp-brevo.com'; // Your email
        $mail->Password = getenv("BREVO_SMTP_KEY");  // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('mendimgashi35@gmail.com', 'E return');
        $mail->addAddress( 'mendimgash1@hotmail.com');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Initialize message variable
$message = '';
$messageType = '';

// INSERT NEW RETURN
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

    if ($stmt->execute()) {
        // Send email after successful insert
        $subject = "New Return Created";
        $body    = "A new return has been added to the system.<br>
                    Order ID: $order_id<br>
                    Reason: $reason<br>
                    Status: $status<br>
                    Type: $return_type";

        if (sendMail("mendimgashi35@gmail.com", $subject, $body)) {
            $message = "Return added successfully and email sent!";
            $messageType = "success";
        } else {
            $message = "Return added successfully but failed to send email.";
            $messageType = "warning";
        }

        $stmt->close();
    } else {
        $stmt->close();
        $message = "Error saving return.";
        $messageType = "error";
    }
}
$uid = $_SESSION['user_id'];
$action = "Added new return for order #$order_id";

$conn->query("INSERT INTO activity_logs (user_id, action)
              VALUES ($uid, '$action')");

// LOAD ORDERS
$sql = "SELECT orders.id, orders.order_number, customers.name AS customer_name
        FROM orders
        JOIN customers ON orders.customer_id = customers.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    position: relative;
}

h2 {
    color:#007BFF;
    text-align:center;
}

input,select,button{
    width:100%;
    padding:10px;
    margin-top:8px;
    border-radius:5px;
    border:1px solid #ccc;
    font-size:16px;
}

input:focus, select:focus {
    outline:none;
    border-color:#007BFF;
    box-shadow:0 0 5px rgba(0,123,255,0.3);
}

button{
    background:#007bff;
    color:white;
    border:none;
    cursor:pointer;
    margin-top:10px;
    transition: background 0.3s;
}

button:hover{
    background:#0056b3;
}

/* Popup message styles */
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

<div class="box">
    <h2>Add New Return</h2>

    <form method="POST">
        <label>Order</label>
        <select name="order_id" required>
            <option value="">Select order</option>
            <?php while($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">
                    Order #<?= $row['order_number'] ?> - <?= htmlspecialchars($row['customer_name']) ?>
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

<?php if($message): ?>
    <div id="popupMessage" class="popup-message <?= $messageType == 'success' ? 'popup-success' : ($messageType == 'warning' ? 'popup-warning' : 'popup-error') ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<script>
// Show the popup
const popup = document.getElementById('popupMessage');
if(popup){
    popup.classList.add('show');
    // Hide after 4 seconds
    setTimeout(() => {
        popup.classList.remove('show');
    }, 4000);
}
</script>

</body>
</html>

<?php $conn->close(); ?>
