<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập & Đăng ký</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <div class="container">
        <div class="form-box" id="login-form">
            <h2>Đăng Nhập</h2>
            <form action="module/seviceLogin.php" method="POST">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Nhập tên đăng nhập" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            </p>Chưa có tài khoản? <a href="javascript:void(0)" onclick="toggleForm('register-form')">Đăng ký ngay!</a></p>
        </div>

        <div class="form-box" id="register-form" style="display: none;">
            <h2>Đăng Ký</h2>
            <form action="module/seviceRegister.php" method="POST">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Tên đăng nhập" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Địa chỉ email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>
                <div class="input-group">
                    <label>Nhập lại mật khẩu</label>
                    <input type="password" name="re_password" placeholder="Xác nhận mật khẩu" required>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>

    <script>
        function toggleForm(formId) {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'none';
            document.getElementById(formId).style.display = 'block';
        }
    </script>
</body>

</html>