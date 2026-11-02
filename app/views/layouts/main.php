<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Store - Điện thoại chính hãng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/phone_store/public/assets/css/style.css">
</head>

<body>

    <header class="store-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-3">

                <a href="/phone_store/public/index.php"
                    class="logo text-decoration-none">
                    <span>PHONE</span> STORE
                </a>

                <form class="search-box"
                    action="/phone_store/public/index.php"
                    method="GET">

                    <input
                        type="text"
                        name="keyword"
                        placeholder="Bạn muốn tìm điện thoại nào?"
                        value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">

                    <button type="submit">
                        Tìm kiếm
                    </button>

                </form>


                <div class="header-actions">
                    <a href="#">Đăng nhập</a>
                    <a href="#">Giỏ hàng 🛒</a>
                </div>
                <?php if (isset($_SESSION['user'])): ?>

                    <span class="nav-link">
                        Xin chào, <?= htmlspecialchars($_SESSION['user']['full_name']) ?>
                        <a href="index.php?page=order_history"
                            class="btn btn-outline-primary btn-sm">
                            Đơn hàng của tôi
                        </a>
                    </span>

                    <a href="index.php?page=logout"
                        class="btn btn-outline-danger btn-sm">
                        Đăng xuất
                    </a>

                <?php else: ?>

                    <a href="index.php?page=login"
                        class="nav-link">
                        Đăng nhập
                    </a>

                    <a href="index.php?page=register"
                        class="btn btn-danger btn-sm">
                        Đăng ký
                    </a>

                <?php endif; ?>
            </div>
        </div>
    </header>

    <nav class="main-nav">
        <div class="container">
            <a href="#">Trang chủ</a>
            <a href="#">Điện thoại</a>
            <a href="#">Máy tính bảng</a>
            <a href="#">Phụ kiện</a>
            <a href="#">Khuyến mãi</a>
            <a href="#">Tin công nghệ</a>
        </div>
    </nav>

    <main class="container py-4">
        <?= $content ?? '' ?>
    </main>

    <footer class="store-footer">
        <div class="container text-center py-4">
            <h5>PHONE STORE</h5>
            <p>Hệ thống bán điện thoại chính hãng</p>
            <p>Hotline: 1900 0000</p>
            <p>© 2026 Phone Store</p>
        </div>
    </footer>

</body>

</html>