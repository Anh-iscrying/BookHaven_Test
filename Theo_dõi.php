<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theo dõi đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/Theo_dõi.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKeJ9GfNUaa6x5tQOSG5O9WYJ9NR+5Tml9KWHeZAlvAAYRQD6qz4u6R" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Theo dõi đơn hàng</h1>p

        <div class="search-bar">
            <input type="text" id="order-search" placeholder="Nhập mã đơn hàng...">
            <button onclick="searchOrder()"><i class="fas fa-search"></i> Tìm kiếm</button>
        </div>

        <div id="order-list">
            <!-- Danh sách đơn hàng sẽ được hiển thị ở đây -->
        </div>
    </div>

    <script src="javascript/Theo_dõi.js"></script>
</body>
</html>