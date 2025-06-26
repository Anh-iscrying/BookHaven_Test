<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hỗ trợ khách hàng</title>
    <link rel="stylesheet" href="css/Hỗ_trợ.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="javascript/Hỗ_trợ.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/Trang_chu.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <!-- header -->
    <!-- header -->
    <?php include 'header_admin.php'; ?>

    <div class="container">
        <h1 class="text-center mb-4">Hỗ trợ khách hàng</h1>

        <h2 class="text-center mb-3">Danh sách yêu cầu hỗ trợ</h2>
        <div class="table-responsive">  <!-- Thêm lớp table-responsive -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include_once('db/connect.php');

                    // Truy vấn dữ liệu
                    $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
                    $result = $mysqli->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                            echo "<td>" . htmlspecialchars(substr($row["comment"], 0, 50)) . "...</td>"; // Hiển thị một phần nội dung
                            echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                            echo "<td>";
                            echo "<a href='reply_contact.php?id=" . $row["id"] . "' class='btn btn-primary btn-sm'>Trả lời</a>"; // Sử dụng nút Bootstrap
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Không có yêu cầu hỗ trợ nào.</td></tr>";
                    }
                    $mysqli->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>