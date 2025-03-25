<?php
include_once('db/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$book = null;
$book_id = isset($_GET['book_id']) ? intval(trim($_GET['book_id'])) : 0;  

// Kiểm tra kết nối cơ sở dữ liệu
$mysqli = new mysqli("localhost", "root", "", "webbansach");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}


if ($book_id > 0) {
    $query = "SELECT * FROM tbl_book WHERE book_id = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
        } else {
            echo "Không tìm thấy sách.";
            exit();
        }
        $stmt->close();
    } else {
        echo "Lỗi prepare statement: " . $mysqli->error;
        exit();
    }
} else {
    echo "ID sách không hợp lệ.";
    exit();
}

// Xử lý form sửa thông tin sách
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
    $image_name = ''; // Initialize $image_name
    $uploadOk = 1;
    $message = ''; // Initialize $message

    if (!empty($image)) {
        $target_dir = "images/"; // Changed target directory
        $image_name = uniqid() . "_" . basename($image);
        $target_file = $target_dir . $image_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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
                // Delete old image if it exists and is different
                if (!empty($book['book_image']) && file_exists("images/" . $book['book_image']) && $book['book_image'] != $image_name) {
                    unlink("images/" . $book['book_image']);
                }

                // Cập nhật book_image riêng
                $query = "UPDATE tbl_book SET book_image = ? WHERE book_id = ?";
                $stmt = $mysqli->prepare($query);

                if ($stmt) {
                    $stmt->bind_param("si", $image_name, $book_id);
                    if ($stmt->execute()) {

                         // Cập nhật các trường còn lại (tiêu đề, mô tả, v.v.)
                        $query2 = "UPDATE tbl_book SET book_title = ?, book_describe = ?, book_author = ?, book_publisher = ?, book_size = ?, book_original_price = ?, book_discount = ?, book_quantity = ?, book_category = ? WHERE book_id = ?";
                        $stmt2 = $mysqli->prepare($query2);
                        if($stmt2){
                            $stmt2->bind_param("sssssdiiii", $title, $description, $author, $publisher, $size, $price, $discount, $quantity, $category, $book_id);

                            $stmt2->execute();
                            $stmt2->close();
                         }

                        $message = "Thông tin sách và ảnh đã được cập nhật thành công.";
                    } else {
                        $message = "Lỗi cập nhật database: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "Lỗi prepare statement: " . $mysqli->error;
                }
            } else {
                $message = "Xin lỗi, đã có lỗi xảy ra khi upload file. Lỗi: " . $_FILES["image"]["error"];
                $uploadOk = 0;
            }
        }
    } else {
        // No new image uploaded, update the other fields only
        $query = "UPDATE tbl_book SET book_title = ?, book_describe = ?, book_author = ?, book_publisher = ?, book_size = ?, book_original_price = ?, book_discount = ?, book_quantity = ?, book_category = ? WHERE book_id = ?";
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param("sssssdiiii", $title, $description, $author, $publisher, $size, $price, $discount, $quantity, $category, $book_id);
            if ($stmt->execute()) {

                 // Cập nhật book_image riêng biệt
                 $query2 = "UPDATE tbl_book SET book_image = ? WHERE book_id = ?";
                 $stmt2 = $mysqli->prepare($query2);
                 if($stmt2){
                     $stmt2->bind_param("si", $image_name, $book_id);
                     $stmt2->execute();
                     $stmt2->close();
                 }

                $message = "Thông tin sách đã được cập nhật thành công (giữ lại ảnh cũ).";
            } else {
                $message = "Lỗi cập nhật database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Lỗi prepare statement: " . $mysqli->error;
        }
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
    <link rel="stylesheet" href="css/Tao_san_pham.css?v=<?php echo time(); ?>" type="text/css">
    <title>Chỉnh sửa sách</title>
</head>
<body>
    <div class="header">
        <a href="./Admin.php"><</a>
    </div>

    <div class="body">
        <form class="container" id="story-form" action="Sửa_sách.php?book_id=<?php echo $book_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="box-avata">
                <div class="avata">
                    <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;">
                    <label for="image-upload">
                        <img class="img" id="preview-image" src="images/<?php echo htmlspecialchars($book['book_image']); ?>" alt="Ảnh bìa">
                    </label>
                </div>
            </div>

            <div class="box-box">
                <div class="box" id="box">
                    <div class="story">
                        <p for="story-title">Tiêu đề:</p>
                        <input type="text" name="book_title" id="story-title" placeholder="Nhập tiêu đề của truyện" value="<?php echo htmlspecialchars($book['book_title']); ?>" required>

                        <p for="story-content">Mô tả:</p>
                        <textarea id="story-content" name="book_describe" placeholder="Viết mô tả của truyện ở đây" required><?php echo htmlspecialchars($book['book_describe']); ?></textarea> <br>

                        <div class="hd">
                            <p>Tác giả:</p>
                            <input type="text" name="book_author" id="price" placeholder="Nhập tên tác giả" value="<?php echo htmlspecialchars($book['book_author']); ?>" required />
                        </div>

                        <label for="publisher">Nhà xuất bản:</label>
                        <select id="publisher" name="book_publisher" required>
                            <option value="Nhà xuất bản Kim Đồng" <?php if ($book['book_publisher'] == 'Nhà xuất bản Kim Đồng') echo 'selected'; ?>>Nhà xuất bản Kim Đồng</option>
                            <option value="Nhà xuất bản Trẻ" <?php if ($book['book_publisher'] == 'Nhà xuất bản Trẻ') echo 'selected'; ?>>Nhà xuất bản Trẻ</option>
                            <option value="Nhà xuất bản IPM" <?php if ($book['book_publisher'] == 'Nhà xuất bản IPM') echo 'selected'; ?>>Nhà xuất bản IPM</option>
                            <option value="Nhà xuất bản Amak" <?php if ($book['book_publisher'] == 'Nhà xuất bản Amak') echo 'selected'; ?>>Nhà xuất bản Amak</option>
                            <option value="Az Việt Nam" <?php if ($book['book_publisher'] == 'Az Việt Nam') echo 'selected'; ?>>Az Việt Nam</option>
                            <option value="Nhà xuất bản Phúc Minh" <?php if ($book['book_publisher'] == 'Nhà xuất bản Phúc Minh') echo 'selected'; ?>>Nhà xuất bản Phúc Minh</option>
                            <option value="Nhà xuất bản Moonbook" <?php if ($book['book_publisher'] == 'Nhà xuất bản Moonbook') echo 'selected'; ?>>Nhà xuất bản Moonbook</option>
                        </select> <br>

                        <label for="size">Kích thước:</label>
                        <select id="size" name="book_size" required>
                            <option value="11.3x17.6 cm" <?php if ($book['book_size'] == '11.3x17.6 cm') echo 'selected'; ?>>11.3x17.6 cm</option>
                            <option value="13x18 cm" <?php if ($book['book_size'] == '13x18 cm') echo 'selected'; ?>>13x18 cm</option>
                            <option value="15.7x24 cm" <?php if ($book['book_size'] == '15.7x24 cm') echo 'selected'; ?>>15.7x24 cm</option>
                            <option value="14.5x20.5 cm" <?php if ($book['book_size'] == '14.5x20.5 cm') echo 'selected'; ?>>14.5x20.5 cm</option>
                        </select>

                        <div class="hd">
                            <p>Số tiền:</p>
                            <input type="number" name="book_original_price" id="price" placeholder="Nhập số tiền" min="0" step="1000" required value="<?php echo htmlspecialchars($book['book_original_price']); ?>" />
                        </div>

                        <div class="hd">
                            <p>Phần trăm giảm:</p>
                            <input type="number" name="book_discount" id="discount" placeholder="Phần trăm giảm" min="0" max="100" step="1" required value="<?php echo htmlspecialchars($book['book_discount']); ?>" />
                        </div>

                        <div class="hd">
                            <p>Số lượng:</p>
                            <input type="number" name="book_quantity" id="quantity" placeholder="Nhập số lượng" min="0" step="1" required value="<?php echo htmlspecialchars($book['book_quantity']); ?>" /> <br>
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
                                <option value="<?php echo htmlspecialchars($category['category_id']); ?>" <?php if ($book['book_category'] == $category['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($category['category_name']); ?></option>
                            <?php endforeach;

                            $mysqli2->close();
                            ?>
                        </select>
                        <button type="submit">Cập nhật</button>
                        <?php if (isset($message)): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('image-upload').addEventListener('change', function() {
            var file = this.files[0];
            var oldImage = "<?php echo htmlspecialchars($book['book_image']); ?>"; // Lấy ảnh cũ

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').setAttribute('src', 'images/' + file.name);
                }
                reader.readAsDataURL(file);
            }else{
                 document.getElementById('preview-image').setAttribute('src', 'images/' + oldImage);
            }
        });

        document.getElementById('image-upload').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>