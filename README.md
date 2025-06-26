# Web Bán Sách - Dự án xây dựng website kinh doanh sách

## Mô tả

Dự án "Web Bán Sách" là một ứng dụng web thương mại điện tử cho phép người dùng duyệt, tìm kiếm và mua sách trực tuyến.

## Chức năng chính

*   **Duyệt và tìm kiếm sách:**
    *   Hiển thị danh sách sách theo danh mục, tác giả, nhà xuất bản.
    *   Tìm kiếm sách theo tên, tác giả, ISBN, v.v.
*   **Xem chi tiết sách:**
    *   Hiển thị thông tin chi tiết về sách (tên, tác giả, mô tả, giá, đánh giá, v.v.).
    *   Hiển thị hình ảnh bìa sách.
*   **Giỏ hàng và thanh toán:**
    *   Thêm sách vào giỏ hàng.
    *   Quản lý giỏ hàng (cập nhật số lượng, xóa sách).
    *   Thanh toán bằng các phương thức khác nhau.
*   **Quản lý tài khoản người dùng:**
    *   Đăng ký tài khoản mới.
    *   Đăng nhập và đăng xuất.
    *   Cập nhật thông tin tài khoản.
    *   Xem lịch sử đơn hàng.
*   **Quản lý sách (dành cho admin):**
    *   Thêm, sửa, xóa sách.
    *   Quản lý danh mục sách.
    *   Quản lý nhà xuất bản, tác giả.
*   **Hỗ trợ khách hàng:**
    *   Form liên hệ.
    *   Hệ thống phản hồi yêu cầu hỗ trợ (dành cho admin).


## Công nghệ sử dụng

*   **Ngôn ngữ lập trình:** PHP, JavaScript, HTML, CSS
*   **Cơ sở dữ liệu:** MySQL (Sử dụng Xampp)
*   **Thư viện/Framework:**
    *   Bootstrap (tùy chọn, để tạo giao diện responsive)
    *   Có thể sử dụng jQuery hoặc các thư viện JavaScript khác
*   **Máy chủ web:** Apache (hoặc Nginx)

## Cài đặt và cấu hình (Sử dụng XAMPP)

1.  **Yêu cầu:**
    *   **XAMPP:** Đã cài đặt và cấu hình XAMPP (bao gồm Apache, MySQL, PHP).
2.  **Cài đặt:**
    *   Sao chép thư mục `Web_ban_sach_-ACS` vào thư mục `htdocs` của XAMPP (ví dụ: `C:\xampp\htdocs\`).
    *   **Khởi động Apache và MySQL trong XAMPP Control Panel.**
    *   **Tạo cơ sở dữ liệu `webbansach` bằng phpMyAdmin:**
        *   Mở trình duyệt và truy cập `http://localhost/phpmyadmin/`.
        *   Nhấn vào "New" để tạo cơ sở dữ liệu mới.
        *   Nhập `webbansach` làm tên cơ sở dữ liệu và chọn "utf8\_unicode\_ci" làm collation.
        *   Nhấn "Create".
    *   **Nhập file SQL `webbansach.sql` vào cơ sở dữ liệu `webbansach`:**
        *   Chọn cơ sở dữ liệu `webbansach` trong phpMyAdmin.
        *   Nhấn vào tab "Import".
        *   Chọn file `db/webbansach.sql` từ thư mục dự án.
        *   Nhấn "Go".
    *   Chỉnh sửa file `db/connect.php` để cập nhật thông tin kết nối cơ sở dữ liệu:
        ```php
        <?php
        $mysqli = new mysqli("localhost", "root", "", "webbansach");

        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }
        ?>
        ```
        (Trong XAMPP, username mặc định là `root` và password là rỗng "")
3.  **Cấu hình:**
    *   Truy cập trang web bằng cách mở trình duyệt và truy cập `http://localhost/Web_ban_sach_-ACS/`.

## Hướng dẫn sử dụng

1.  **Truy cập trang web:** Mở trình duyệt web và truy cập `http://localhost/Web_ban_sach_-ACS/` (hoặc địa chỉ URL tương ứng).
2.  **Sử dụng các chức năng:**
    *   Duyệt sách, tìm kiếm sách, xem chi tiết sách.
    *   Đăng ký/đăng nhập tài khoản.
    *   Thêm sách vào giỏ hàng và thanh toán.
3.  **Quản lý (dành cho admin):**
    *   Truy cập trang quản lý (ví dụ: `http://localhost/Web_ban_sach_-ACS/admin/Quản_lý_sách.php`).
    *   Đăng nhập bằng tài khoản admin.
    *   Quản lý sách, danh mục, v.v.
  
## Thông tin liên hệ

*   [Mai Phương Anh - Tào Thanh Hà]
*   [maiphuonganh1505@gmail.com]
*   []

## Ghi chú

*   Đây là một dự án đang phát triển, có thể có lỗi hoặc thiếu sót.
*   Vui lòng báo cáo bất kỳ vấn đề nào bạn gặp phải.
*   Cảm ơn bạn đã sử dụng ứng dụng web của chúng tôi!
=======
# BookHaven_Test

