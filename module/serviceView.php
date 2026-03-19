<?php
// session_start(); // Nếu cần check quyền admin
require_once '../config/config.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<p style='color:red;'>Hổng tìm thấy user này Pro ơi!</p>";
    exit;
}

// Chỉ echo ra phần nội dung bên trong Popup
?>
<div class="user-detail-content">
    <p><strong> Username:</strong> <?php echo htmlspecialchars($user['user_name']); ?></p>
    <p><strong> Email:</strong> <?php echo htmlspecialchars($user['user_email']); ?></p>

</div>