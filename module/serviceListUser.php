<?php
// Không cần session_start ở đây vì mình sẽ start ở file index chính
require_once __DIR__ . '/../config/config.php';

try {
    // Truy vấn lấy danh sách user
    $sql = "SELECT user_id, user_name, user_email, created_at FROM users";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();

    // Trả về biến $users để file index sử dụng
    return $users;
} catch (PDOException $e) {
    die("Lỗi truy vấn rồi Pro ơi: " . $e->getMessage());
}
