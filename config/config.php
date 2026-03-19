<?php
define("HOST", "localhost");
define("DB_NAME", "my_shop");
define("DB_USER", "root");
define("DB_PASS", "");
try {
    // Chuỗi kết nối cho MySQL trong XAMPP
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Hiện lỗi để dễ fix
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Trả về mảng dễ xài
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Chống SQL Injection
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // echo "Kết nối ngon lành rồi Pro ơi!"; 
} catch (PDOException $e) {
    die("Lỗi kết nối DB rồi Pro: " . $e->getMessage());
}
