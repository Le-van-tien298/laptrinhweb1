<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $re_pass  = $_POST['re_password'] ?? '';

    // 1. Kiểm tra mật khẩu nhập lại có khớp không
    if ($password !== $re_pass) {
        die("Mật khẩu nhập lại hổng khớp nha Pro!");
    }

    // 2. Kiểm tra xem username đã tồn tại chưa
    $check = $pdo->prepare("SELECT * FROM users WHERE user_name = ?");
    $check->execute([$username]);
    if ($check->fetch()) {
        die("Tên đăng nhập này có người hốt rồi Pro!");
    }

    // 3. Lưu vào Database (Dùng các cột user_name, user_email, user_pass)
    $sql = "INSERT INTO users (user_name, user_email, user_pass) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$username, $email, $password])) {
        header("Location: ../login.php");
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
