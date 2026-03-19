<?php
// 1. Phải khởi động session thì mới xóa nó được nha Pro
session_start();

// 2. Xóa sạch các biến trong session
$_SESSION = array();
// 4. Hủy bỏ hoàn toàn session trên server
session_destroy();

// 5. Đá User quay về trang login (index.php của Pro)
header("Location: ../login.php");
exit();
