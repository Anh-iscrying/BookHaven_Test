<?php
include_once('db/connect.php');

// 2. Lấy ID từ URL
$id = $_GET["id"];

// Kiểm tra xem form đã được submit chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu form
    $reply = $_POST["reply"];

    // Cập nhật trạng thái và reply trong bảng `contacts`
    $sql = "UPDATE contacts SET status = 'replied', reply = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $reply, $id);  // 's' cho reply (string), 'i' cho id (integer)

    if ($stmt->execute()) {
        echo "<script>alert('Đã trả lời yêu cầu!'); window.location.href = 'Hỗ_trợ.php';</script>";
        exit();
    } else {
        echo "<script>alert('Lỗi cập nhật: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// 3. Lấy thông tin yêu cầu từ database để hiển thị
$sql = "SELECT * FROM contacts WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trả lời yêu cầu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/Trang_chu.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php include 'header_admin.php'; ?>
    <div class="container">
        <h1>Trả lời yêu cầu hỗ trợ</h1>

        <div class="mb-3">
            <label>Tên:</label>
            <p><?php echo htmlspecialchars($row["name"]); ?></p>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <p><?php echo htmlspecialchars($row["email"]); ?></p>
        </div>

        <div class="mb-3">
            <label>Nội dung:</label>
            <p><?php echo htmlspecialchars($row["comment"]); ?></p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="reply">Câu trả lời:</label>
                <textarea class="form-control" id="reply" name="reply" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Gửi trả lời
            </button>
             <a href="Hỗ_trợ.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
} else {
    echo "<div class='alert alert-danger'>Không tìm thấy yêu cầu.</div>";
}

$stmt->close();
$mysqli->close();
?>