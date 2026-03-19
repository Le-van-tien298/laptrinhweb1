<?php
session_start();

// 1. Chặn cửa bảo mật - Chỉ cần 1 lần là đủ
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Triệu hồi dữ liệu từ Duy Nhất một file service (servicePage.php)
// File này phải chứa logic kết hợp cả FILTER và PAGINATION như tui hướng dẫn lúc nãy
$data = require_once 'module/servicePage.php';

// 3. Bóc tách dữ liệu an toàn
$users = $data['users'] ?? [];
$totalPages = $data['totalPages'] ?? 1;
$currentPage = $data['currentPage'] ?? 1;
$current_filter = $_GET['filter'] ?? 0;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý User - Pro Admin</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;

            position: sticky;
            top: 0;
            background-color: #f4f7f6;
            /* Màu nền trùng với nền trang của Pro */
            z-index: 100;
            padding: 15px 0;
            border-bottom: 2px solid #007bff;
            /* Thêm cái gạch chân cho nó phân biệt với bảng */
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .user-table th,
        .user-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .user-table th {
            background-color: #007bff;
            color: white;
        }

        .user-table tr:hover {
            background-color: #f9f9f9;
        }

        /* Màu sắc cho các nút thao tác */
        .btn-view {
            color: #28a745;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-edit {
            color: #ffc107;
            text-decoration: none;
            font-weight: bold;
            margin: 0 5px;
        }

        .btn-delete {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-logout {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* Nền mờ */
            backdrop-filter: blur(3px);
            /* Làm mờ hậu cảnh cho xịn */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: zoomIn 0.3s ease;
            /* Hiệu ứng phóng ra */
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.7);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .close-btn {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }

        .close-btn:hover {
            color: #dc3545;
        }

        #modal-body-data p {
            margin: 15px 0;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            color: #333;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 2px;
            border-radius: 4px;
            transition: 0.3s;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination a:hover:not(.active) {
            background-color: #f1f1f1;
        }

        .filter-wrapper {
            position: fixed;
            display: inline-block;
            right: 20px;
            bottom: 20px;



        }

        .filter-trigger {
            cursor: pointer;
            background: #fff;
            padding: 10px 18px;
            border: 1px solid #007bff;
            border-radius: 25px;
            color: #007bff;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;

        }

        .filter-trigger:hover {
            background: #007bff;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        /* Cái hộp chứa option khi hiện ra */
        .filter-popover {
            display: none;
            /* 1. Đổi sang fixed để nó đứng yên giữa màn hình */
            position: fixed;

            /* 2. Tuyệt chiêu căn giữa tuyệt đối */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Dịch ngược lại 50% kích thước chính nó */

            background: white;
            min-width: 350px;
            padding: 25px;
            /* Tăng padding xíu cho thoáng */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            /* Đổ bóng đậm hơn cho nổi bật */
            border-radius: 16px;
            /* Bo góc tròn hơn cho xịn */
            z-index: 10000;
            /* Phải cao hơn cái sticky header của Pro */
            border: 1px solid #eee;

            /* 3. Cập nhật Animation để nó phóng từ giữa ra */
            animation: popCenter 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Hiệu ứng phóng ra từ tâm */
        @keyframes popCenter {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.5);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .filter-popover.active {
            display: block;
        }

        /* Làm cho cái select nhìn chuyên nghiệp hơn */
        .custom-select {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px;
            outline: none;
            font-family: inherit;
            cursor: pointer;
        }

        .custom-select option {
            padding: 10px;
            border-bottom: 1px solid #f9f9f9;
        }

        .custom-select option:hover {
            background-color: #f0f7ff;
        }
    </style>
</head>

<body>
    <div class="container" style="width: 90%; max-width: 1100px; margin: 20px auto;">

        <div class="header-actions">
            <h2>Hệ Thống Quản Lý User</h2>
            <a href="./module/logout.php" class="btn-logout" onclick="return confirm('Ban co muốn thoát thiệt hả?')">Đăng xuất</a>
        </div>

        <div class="filter-wrapper">
            <div class="filter-trigger" onclick="toggleFilter()">
                <span class="icon">⏳</span>
            </div>

            <div id="filter-popover" class="filter-popover">
                <form method="GET" id="filterForm">
                    <div class="filter-trigger" onclick="toggleFilter()">
                        <i class="fas fa-filter icon" style="font-size: 16px;"></i> <strong>Lọc danh sách</strong>
                    </div>
                    <select name="filter" onchange="this.form.submit()" size="11" class="custom-select">
                        <option value="0" <?= $current_filter == 0 ? 'selected' : '' ?>>-- Mặc định (Tất cả) --</option>
                        <option value="1" <?= $current_filter == 1 ? 'selected' : '' ?>>1. Danh sách Alphabet (A-Z)</option>
                        <option value="2" <?= $current_filter == 2 ? 'selected' : '' ?>>2. Lấy 07 người đầu (A-Z)</option>
                        <option value="3" <?= $current_filter == 3 ? 'selected' : '' ?>>3. Tên có chữ 'a' (A-Z)</option>
                        <option value="4" <?= $current_filter == 4 ? 'selected' : '' ?>>4. Tên bắt đầu bằng 'm'</option>
                        <option value="5" <?= $current_filter == 5 ? 'selected' : '' ?>>5. Tên kết thúc bằng 'i'</option>
                        <option value="6" <?= $current_filter == 6 ? 'selected' : '' ?>>6. Email là Gmail (@gmail.com)</option>
                        <option value="7" <?= $current_filter == 7 ? 'selected' : '' ?>>7. Gmail + Tên bắt đầu bằng 'm'</option>
                        <option value="8" <?= $current_filter == 8 ? 'selected' : '' ?>>8. Gmail + Tên có 'i' + Tên dài > 5</option>
                        <option value="9" <?= $current_filter == 9 ? 'selected' : '' ?>>9. Tên 'a' (5-9) + Gmail + Tên email có 'i'</option>
                        <option value="10" <?= $current_filter == 10 ? 'selected' : '' ?>>10. Tổng hợp phức tạp</option>
                    </select>
                    <div style="margin-top: 10px; text-align: right;">
                        <button type="button" onclick="toggleFilter()" style="background:#666; color:#fff; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($user['user_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                            <td><?php echo $user['created_at']; ?></td>
                            <td class="actions-cell">
                                <div class="action-group">
                                    <a href="javascript:void(0)" class="btn-view" onclick="viewUser(<?= $user['user_id']; ?>)">View</a>
                                    <span class="divider"></span>
                                    <a href="edit_user.php?id=<?= $user['user_id']; ?>" class="btn-edit">Edit</a>
                                    <span class="divider"></span>
                                    <a href="delete_user.php?id=<?= $user['user_id']; ?>" class="btn-delete" onclick="return confirm('Xóa là mất xác đó Pro!')">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Trống trơn hà Pro!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=1">« Đầu</a>
                <a href="?page=<?php echo $currentPage - 1; ?>">‹ Trước</a>
            <?php endif; ?>

            <?php
            // Hiển thị các số trang (giới hạn hiện 5 trang xung quanh trang hiện tại cho đẹp)
            for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++):
            ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>">Sau ›</a>
                <a href="?page=<?php echo $totalPages; ?>">Cuối »</a>
            <?php endif; ?>
        </div>
    </div>

    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 style="margin-top:0">Chi Tiết Người Dùng</h2>
            <div id="modal-body-data">
            </div>
        </div>

    </div>

    <div></div>


    <script>
        // Hàm mở Modal và lấy dữ liệu từ Server
        async function viewUser(id) {
            const modal = document.getElementById('userModal');
            const bodyData = document.getElementById('modal-body-data');

            // Hiện modal và trạng thái chờ
            modal.style.display = "block";
            bodyData.innerHTML = "<p style='text-align:center;'>Đang tải dữ liệu, Pro đợi xíu nha...</p>";

            try {
                // Gọi đến file logic trong folder module
                // Lưu ý: Đường dẫn phải khớp với cấu trúc thư mục của Pro
                const response = await fetch(`module/serviceView.php?id=${id}`);

                if (!response.ok) throw new Error('Hổng kết nối được server Pro ơi');

                const html = await response.text();

                // Đổ dữ liệu HTML từ PHP trả về vào trong Popup
                bodyData.innerHTML = html;
            } catch (error) {
                bodyData.innerHTML = "<p style='color:red;'>Lỗi: " + error.message + "</p>";
            }
        }

        // Hàm đóng Modal
        function closeModal() {
            document.getElementById('userModal').style.display = "none";
        }

        function toggleFilter() {
            const popover = document.getElementById('filter-popover');
            popover.classList.toggle('active');
        }

        // Tự động đóng khi bấm ra ngoài vùng filter
        window.addEventListener('click', function(e) {
            const popover = document.getElementById('filter-popover');
            const trigger = document.querySelector('.filter-trigger');

            if (popover.classList.contains('active')) {
                if (!popover.contains(e.target) && !trigger.contains(e.target)) {
                    popover.classList.remove('active');
                }
            }
        });
    </script>
</body>

</html>