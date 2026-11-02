
<?php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class OrderController
{
    private $orderModel;
    private $productModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->orderModel = new Order();
        $this->productModel = new Product();
    }

    // ==============================
    // TRANG THANH TOÁN
    // ==============================
    public function checkout()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        // Lấy giỏ hàng
        $cart = $_SESSION['cart'] ?? [];

        // Kiểm tra giỏ hàng trống
        if (empty($cart)) {
            header('Location: index.php?page=cart');
            exit;
        }

        $products = [];
        $total = 0;
        $errors = [];

        // Lấy thông tin sản phẩm trong giỏ
        foreach ($cart as $id => $quantity) {

            $id = (int)$id;
            $quantity = (int)$quantity;

            if ($id <= 0 || $quantity <= 0) {
                $errors[] = "Thông tin sản phẩm trong giỏ hàng không hợp lệ.";
                continue;
            }

            $product = $this->productModel->getById($id);

            if (!$product) {
                $errors[] = "Một sản phẩm trong giỏ hàng không còn tồn tại.";
                continue;
            }

            $price = !empty($product['sale_price'])
                ? $product['sale_price']
                : $product['price'];

            $subtotal = $price * $quantity;

            $product['cart_quantity'] = $quantity;
            $product['subtotal'] = $subtotal;

            $total += $subtotal;

            $products[] = $product;
        }

        // Xử lý khi khách xác nhận đặt hàng
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $address = trim($_POST['address'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            // Kiểm tra địa chỉ
            if ($address === '') {
                $errors[] = "Vui lòng nhập địa chỉ giao hàng.";
            }

            // Kiểm tra số điện thoại
            if (!preg_match('/^[0-9+\s().-]{9,20}$/', $phone)) {
                $errors[] = "Số điện thoại không hợp lệ.";
            }

            // Nếu không có lỗi thì tạo đơn hàng
            if (empty($errors)) {

                try {

                    $orderId = $this->orderModel->createOrder(
                        (int)$_SESSION['user']['id'],
                        $address,
                        $phone,
                        $cart
                    );

                    if (!$orderId) {
                        throw new Exception("Không tạo được mã đơn hàng.");
                    }

                    // Lưu mã đơn hàng vào session
                    $_SESSION['last_order_id'] = $orderId;

                    // Xóa giỏ hàng sau khi đặt thành công
                    unset($_SESSION['cart']);

                    // Chuyển sang trang thành công
                    header('Location: index.php?page=order_success');
                    exit;
                } catch (Throwable $e) {

                    echo '<h3 style="color:red;">LỖI ĐẶT HÀNG</h3>';
                    echo '<pre>';
                    echo htmlspecialchars($e->getMessage());
                    echo '</pre>';
                    exit;
                }
            }
        }

        // Hiển thị trang checkout
        require __DIR__ . '/../views/order/checkout.php';
    }


    // ==============================
    // TRANG ĐẶT HÀNG THÀNH CÔNG
    // ==============================
    public function success()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        // Lấy mã đơn hàng
        $orderId = $_SESSION['last_order_id'] ?? null;

        // Kiểm tra mã đơn hàng
        if (!$orderId) {
            header('Location: index.php?page=home');
            exit;
        }

        // Hiển thị trang thành công
        require __DIR__ . '/../views/order/success.php';
    }

    // Lịch sử đơn hàng của khách hàng
    public function history()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = (int) $_SESSION['user']['id'];

        $orders = $this->orderModel->getOrdersByUser($userId);

        require __DIR__ . '/../views/order/history.php';
    }

    // Xem chi tiết đơn hàng
    public function detail()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        // Lấy mã đơn hàng từ URL
        $orderId = (int)($_GET['id'] ?? 0);

        if ($orderId <= 0) {
            header('Location: index.php?page=order_history');
            exit;
        }

        // Lấy thông tin đơn hàng
        $orderItems = $this->orderModel->getOrderDetail(
            $orderId,
            (int)$_SESSION['user']['id']
        );

        // Không tìm thấy đơn hàng hoặc không thuộc về khách hàng
        if (empty($orderItems)) {
            header('Location: index.php?page=order_history');
            exit;
        }

        // Thông tin chung của đơn hàng
        $order = $orderItems[0];

        // Hiển thị giao diện
        require __DIR__ . '/../views/order/detail.php';
    }
    // Hủy đơn hàng
    public function cancel()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=order_history');
            exit;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $userId = (int)$_SESSION['user']['id'];

        if ($orderId <= 0) {
            header('Location: index.php?page=order_history');
            exit;
        }

        try {
            $result = $this->orderModel->cancelOrder($orderId, $userId);

            if ($result) {
                $_SESSION['order_message'] = 'Hủy đơn hàng thành công!';
            } else {
                $_SESSION['order_message'] = 'Không thể hủy đơn hàng này. Đơn hàng có thể đã được xử lý.';
            }
        } catch (Throwable $e) {
            $_SESSION['order_message'] = 'Có lỗi xảy ra khi hủy đơn hàng.';
        }

        header('Location: index.php?page=order_history');
        exit;
    }
}
