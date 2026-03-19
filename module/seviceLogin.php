<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $user['user_pass'] === $password) {
        // Cấp "vé thông hành" bằng Session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['user_name'];

        // Chuyển hướng về trang chủ
        header("Location: ../index.php");
        exit(); // Nhớ có dòng này để code bên dưới không chạy tiếp
    } else {
        echo "<script>alert('Sai rồi Pro ơi!'); window.location.href='index.php';</script>";
        exit();
    }
}
