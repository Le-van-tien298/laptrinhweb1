<?php
require_once __DIR__ . '/../config/config.php';

// 1. Lấy tham số trang và lọc
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$filter = isset($_GET['filter']) ? (int)$_GET['filter'] : 0;

try {
    // 2. Xây dựng câu lệnh WHERE dựa trên filter (Phải đồng bộ với switch case bên dưới)
    $whereClause = "WHERE 1=1";
    switch ($filter) {
        case 3:
            $whereClause .= " AND user_name LIKE '%a%'";
            break;
        case 4:
            $whereClause .= " AND user_name LIKE 'm%'";
            break;
        case 5:
            $whereClause .= " AND user_name LIKE '%i'";
            break;
        case 6:
            $whereClause .= " AND user_email LIKE '%@gmail.com'";
            break;
        case 7:
            $whereClause .= " AND user_email LIKE '%@gmail.com' AND user_name LIKE 'm%'";
            break;
        case 8:
            $whereClause .= " AND user_email LIKE '%@gmail.com' AND user_name LIKE '%i%' AND LENGTH(user_name) > 5";
            break;
        case 9:
            $whereClause .= " AND user_name LIKE '%a%' AND LENGTH(user_name) BETWEEN 5 AND 9 AND SUBSTRING_INDEX(user_email, '@', 1) LIKE '%i%' AND user_email LIKE '%@gmail.com'";
            break;
        case 10:
            $whereClause .= " AND ((user_name LIKE '%a%' AND LENGTH(user_name) BETWEEN 5 AND 9) OR (user_name LIKE '%i%' AND LENGTH(user_name) < 9) OR (user_email LIKE '%@gmail.com' AND SUBSTRING_INDEX(user_email, '@', 1) LIKE '%i%'))";
            break;
    }

    // 3. Tính tổng số dòng sau khi lọc để phân trang đúng
    $countSql = "SELECT COUNT(*) FROM users $whereClause";
    $totalUsers = $pdo->query($countSql)->fetchColumn();
    $totalPages = ceil($totalUsers / $limit);

    // 4. Truy vấn lấy dữ liệu đã lọc + có phân trang
    $orderBy = ($filter == 1 || $filter == 2 || $filter == 3) ? "ORDER BY user_name ASC" : "ORDER BY created_at DESC";
    $limitClause = ($filter == 2) ? "LIMIT 7" : "LIMIT $limit OFFSET $offset";

    $sql = "SELECT * FROM users $whereClause $orderBy $limitClause";

    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();

    return [
        'users' => $users,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ];
} catch (PDOException $e) {
    die("Lỗi rồi Pro ơi: " . $e->getMessage());
}
