
<?php

// Khởi tạo session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Khai báo Controller
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
// Xác định trang
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'admin_orders':
        $controller = new AdminController();
        ob_start();
        $controller->orders();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;
    case 'admin_products':
        $controller = new AdminController();

        ob_start();
        $controller->products();
        $content = ob_get_clean();

        require __DIR__ . '/../app/views/layouts/main.php';
        break;
    case 'admin_product_add':
        $controller = new AdminController();
        ob_start();
        $controller->addProduct();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;
    case 'admin_order_detail':
        $controller = new AdminController();

        ob_start();
        $controller->orderDetail();
        $content = ob_get_clean();

        require __DIR__ . '/../app/views/layouts/main.php';
        break;
    case 'admin_update_order':
        $controller = new AdminController();
        $controller->updateOrderStatus();
        break;
    case 'home':
        $controller = new HomeController();
        ob_start();
        $controller->index();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;

    case 'detail':
        $controller = new HomeController();
        ob_start();
        $controller->detail();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;

    case 'register':
        $controller = new AuthController();
        ob_start();
        $controller->register();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;

    case 'login':
        $controller = new AuthController();
        ob_start();
        $controller->login();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'add_cart':
        $controller = new CartController();
        $controller->add();
        break;

    case 'cart':
        $controller = new CartController();
        ob_start();
        $controller->index();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;

    case 'update_cart':
        $controller = new CartController();
        $controller->update();
        break;

    case 'remove_cart':
        $controller = new CartController();
        $controller->remove();
        break;

    case 'checkout':
        $controller = new OrderController();
        ob_start();
        $controller->checkout();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;

    case 'order_success':
        $controller = new OrderController();
        ob_start();
        $controller->success();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;


    case 'order_history':
        $controller = new OrderController();

        ob_start();
        $controller->history();
        $content = ob_get_clean();

        require __DIR__ . '/../app/views/layouts/main.php';
        break;
    case 'order_detail':
        $controller = new OrderController();
        ob_start();
        $controller->detail();
        $content = ob_get_clean();
        require __DIR__ . '/../app/views/layouts/main.php';
        break;
    case 'order_cancel':
        $controller = new OrderController();
        $controller->cancel();
        break;
    default:
        http_response_code(404);
        echo "404 - Không tìm thấy trang.";
        break;
}
