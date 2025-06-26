<?php
// Kết nối đến cơ sở dữ liệu
include_once('db/connect.php');

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate Email
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        echo "<script>alert('Email không hợp lệ (phải là @gmail.com)!');</script>";
        exit();
    } // Kiểm tra xem mật khẩu có khớp không
    elseif ($password !== $confirmPassword) {
        echo "<script>alert('Mật khẩu và nhập lại mật khẩu không khớp!');</script>";
        exit();
    } else {
        // Validate Phone
        if (!preg_match('/^(0[0-9]{9})$/', $phone)) {
            echo "<script>alert('Số điện thoại không hợp lệ (phải bắt đầu bằng 0 và có 10 chữ số)!');</script>";
            exit();
        }
        // Kiểm tra độ mạnh của mật khẩu (ví dụ đơn giản)
        elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            echo "<script>alert('Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa và số!');</script>";
            exit();
        } else {
            // Băm mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("INSERT INTO tbl_user (name, email, phone, pass) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword); // Lưu mật khẩu đã băm

            if ($stmt->execute()) {
                // Đăng ký thành công, có thể chuyển hướng đến trang đăng nhập
                header("Location: DN.php");
                exit();
            } else {
                // Thông báo lỗi
                echo "Có lỗi xảy ra: " . $stmt->error;
                exit();
            }
        }
    }
}
?>


<!DOCTYPE html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Kí</title>
    <link rel="stylesheet" href="css/Đk.css" type="text/css">
    <style>
        .error {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
    <div class="gdđn">
        <div class="khunglon">
            <div class="x"><a href="ĐN.html">X</a></div>
            <div class="khungnho">
                <div class="khungbe">
                    <div class="thamgiagt">
                        <div class="dnvao">
                            <span class="chu">Tham gia với chúng tôi</span>
                        </div>
                        <div class="dnvao">
                            <span class="quyenloi">Là một phần của cộng đồng tác giả và độc giả toàn cầu, mọi người đều được kết nối bằng sức mạnh của trí tưởng tượng.</span>
                        </div>
                    </div>

                    <form id="registerForm" method="post" onsubmit="return validateForm()">
                        <input type="text" id="name" name="name" placeholder="Tên" required>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <div class="error" id="email-error"></div>
                        <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" required>
                        <div class="error" id="phone-error"></div>
                        <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu" required>
                        <button type="submit" name="register" class="buttondn">Đăng ký</button>
                    </form>
                </div>
                <footer class="khungdk">
                    <span>Nếu bạn đã có tài khoản <button class="dangki"><a class="dangki" href="DN.php">Đăng nhập</a></button></span>
                </footer>
                <div class="quenMK">
                    By continuing, you agree to Website's <a class="blue" href="">Điều khoản Dịch vụ</a> and <a class="blue" href="">Chính Sách Bảo Mật.</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            let isValid = true;  // Biến để theo dõi xem form có hợp lệ hay không
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const phoneInput = document.getElementById('phone');
            const phoneError = document.getElementById('phone-error');
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

             // Validate Email
            const emailValue = emailInput.value;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
            if (!emailRegex.test(emailValue)) {
                emailError.textContent = 'Email không hợp lệ (phải là @gmail.com).';
                emailError.style.display = 'block';
                isValid = false;
            } else {
                emailError.style.display = 'none';
            }
            // Validate Phone
            const phoneValue = phoneInput.value;
            const phoneRegex = /^(0[0-9]{9})$/; // Bắt đầu bằng 0 và có 10 chữ số
            if (!phoneRegex.test(phoneValue)) {
                phoneError.textContent = 'Số điện thoại không hợp lệ (phải bắt đầu bằng 0 và có 10 chữ số).';
                phoneError.style.display = 'block';
                isValid = false;
            } else {
                phoneError.style.display = 'none';
            }
            
            if (password !== confirmPassword) {
                alert("Mật khẩu và nhập lại mật khẩu không khớp!");
                return false;
            }

            return isValid; // Trả về false nếu có bất kỳ lỗi nào
        }
    </script>
</body>
</html>