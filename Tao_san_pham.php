<?php
include_once('db/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Xử lý form tạo sách mới
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST['book_title']) ? trim(htmlspecialchars($_POST['book_title'])) : '';
    $description = isset($_POST['book_describe']) ? trim(htmlspecialchars($_POST['book_describe'])) : '';
    $author = isset($_POST['book_author']) ? trim(htmlspecialchars($_POST['book_author'])) : '';
    $publisher = isset($_POST['book_publisher']) ? trim(htmlspecialchars($_POST['book_publisher'])) : '';
    $size = isset($_POST['book_size']) ? trim(htmlspecialchars($_POST['book_size'])) : '';
    $price = isset($_POST['book_original_price']) ? (float)$_POST['book_original_price'] : 0;
    $discount = isset($_POST['book_discount']) ? (int)$_POST['book_discount'] : 0;
    $quantity = isset($_POST['book_quantity']) ? (int)$_POST['book_quantity'] : 0;
    $category = isset($_POST['book_category']) ? (int)$_POST['book_category'] : 0;

    $image = $_FILES['image']['name'] ?? '';
    $target_dir = "images/"; 
    $image_name = uniqid() . "_" . basename($image);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!empty($image)) {
        $check = @getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $message = "File không phải là hình ảnh.";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 5000000) {
            $message = "Xin lỗi, file quá lớn.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message = "Xin lỗi, chỉ cho phép các định dạng JPG, JPEG, PNG & GIF.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $query = "INSERT INTO tbl_book (book_title, book_describe, book_author, book_publisher, book_size, book_original_price, book_discount, book_quantity, book_category, book_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("sssssdiiss", $title, $description, $author, $publisher, $size, $price, $discount, $quantity, $category, $image_name); // Lưu tên file vào database
                    if ($stmt->execute()) {
                        $message = "Sách mới đã được tạo thành công.";
                    } else {
                        $message = "Lỗi thêm dữ liệu vào database: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "Lỗi prepare statement: " . $mysqli->error;
                }
            } else {
                $message = "Xin lỗi, đã có lỗi xảy ra khi upload file.";
            }
        }
    } else {
        $message = "Vui lòng chọn ảnh bìa cho sách.";
    }
    $mysqli->close();
    header("Location: Admin.php?message=" . urlencode($message));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Thêm Bootstrap CSS -->
    <link rel="stylesheet" href="css/Tao_san_pham.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <title>Tạo sách mới</title>

</head>
<body>
    <?php include 'header_admin.php'; ?>

    <div class="body">
        <form class="container" id="story-form" action="Tao_san_pham.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="box-avata">
                <div class="avata">
                    <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;">
                    <label for="image-upload">
                        <img class="img" id="preview-image" src="#" alt="Ảnh bìa">
                    </label>
                </div>
            </div>

            <div class="box-box">
                <div class="box" id="box">
                    <div class="story">
                        <p for="story-title">Tiêu đề:</p>
                        <input type="text" name="book_title" id="story-title" placeholder="Nhập tiêu đề của sách" required>

                        <p for="story-content">Mô tả:</p>
                        <textarea id="story-content" name="book_describe" placeholder="Viết mô tả của sách ở đây" required></textarea> <br>

                        <div class="hd">
                            <p>Tác giả:</p>
                            <input type="text" name="book_author" id="price" placeholder="Nhập tên tác giả" required />
                        </div>

                        <label for="publisher">Nhà xuất bản:</label>
                        <select id="publisher" name="book_publisher" required>
                            <option value="Nhà xuất bản Kim Đồng">Nhà xuất bản Kim Đồng</option>
                            <option value="Nhà xuất bản Trẻ">Nhà xuất bản Trẻ</option>
                            <option value="Nhà xuất bản IPM">Nhà xuất bản IPM</option>
                            <option value="Nhà xuất bản Amak">Nhà xuất bản Amak</option>
                            <option value="Az Việt Nam">Az Việt Nam</option>
                            <option value="Nhà xuất bản Phúc Minh">Nhà xuất bản Phúc Minh</option>
                            <option value="Nhà xuất bản Moonbook">Nhà xuất bản Moonbook</option>
                        </select> <br>

                        <label for="size">Kích thước:</label>
                        <select id="size" name="book_size" required>
                            <option value="11.3x17.6 cm">11.3x17.6 cm</option>
                            <option value="13x18 cm">13x18 cm</option>
                            <option value="15.7x24 cm">15.7x24 cm</option>
                            <option value="14.5x20.5 cm">14.5x20.5 cm</option>
                        </select>

                        <div class="hd">
                            <p>Số tiền:</p>
                            <input type="number" name="book_original_price" id="price" placeholder="Nhập số tiền" min="0" step="1000" required />
                        </div>

                        <div class="hd">
                            <p>Phần trăm giảm:</p>
                            <input type="number" name="book_discount" id="discount" placeholder="Phần trăm giảm" min="0" max="100" step="1" required />
                        </div>

                        <div class="hd">
                            <p>Số lượng:</p>
                            <input type="number" name="book_quantity" id="quantity" placeholder="Nhập số lượng" min="0" step="1" required /> <br>
                        </div>

                        <label for="category">Thể loại:</label>
                        <select name="book_category" class="form-group" required>
                            <option value="">Chọn thể loại</option>
                            <?php
                            $mysqli2 = new mysqli("localhost", "root", "", "webbansach");

                            if ($mysqli2->connect_errno) {
                                echo "Failed to connect to MySQL: " . $mysqli2->connect_error;
                                exit();
                            }

                            $stmt2 = $mysqli2->prepare("SELECT category_id, category_name FROM tbl_category");
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
                            $categories = $result2->fetch_all(MYSQLI_ASSOC);

                            foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['category_id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                            <?php endforeach;

                            $mysqli2->close();
                            ?>
                        </select>
                        <button type="submit">Lưu</button>
                        <?php if (isset($message)): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="./javascript/Tao_san_pham.js">
    </script>
</body>
</html>