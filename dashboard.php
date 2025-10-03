<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT m.message, m.created_at, u.username FROM messages m JOIN users u ON m.user_id=u.id ORDER BY m.created_at DESC");
?>
<h2>Welcome to Dashboard</h2>
<form method="POST">
  <textarea name="message" required></textarea><br>
  <button type="submit">Send Message</button>
</form>
<h3>Messages:</h3>
<?php while($row = $result->fetch_assoc()) { ?>
  <p><b><?php echo $row['username']; ?>:</b> <?php echo $row['message']; ?> (<?php echo $row['created_at']; ?>)</p>
<?php } ?>
<a href="logout.php">Logout</a>
