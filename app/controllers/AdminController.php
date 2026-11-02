
<?php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class AdminController
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

    // Kiểm tra quyền Admin
    private function checkAdmin()
    {
        if (
            !isset($_SESSION['user']) ||
            (int)$_SESSION['user']['role_id'] !== 1
        ) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Danh sách tất cả đơn hàng
    public function orders()
    {
        $this->checkAdmin();

        $orders = $this->orderModel->getAllOrders();

        require __DIR__ . '/../views/admin/orders.php';
    }

    // Admin xem chi tiết đơn hàng
    public function orderDetail()
    {
        $this->checkAdmin();

        $orderId = (int)($_GET['id'] ?? 0);

        if ($orderId <= 0) {
            $_SESSION['admin_message'] = 'Mã đơn hàng không hợp lệ.';
            header('Location: index.php?page=admin_orders');
            exit;
        }

        $orderDetails = $this->orderModel->getOrderDetailForAdmin($orderId);

        if (empty($orderDetails)) {
            $_SESSION['admin_message'] = 'Không tìm thấy đơn hàng.';
            header('Location: index.php?page=admin_orders');
            exit;
        }

        require __DIR__ . '/../views/admin/order_detail.php';
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_orders');
            exit;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($orderId <= 0) {
            $_SESSION['admin_message'] = 'Mã đơn hàng không hợp lệ.';
        } else {
            try {
                $result = $this->orderModel->updateOrderStatus(
                    $orderId,
                    $status
                );

                $_SESSION['admin_message'] = $result
                    ? 'Cập nhật trạng thái thành công!'
                    : 'Không thể cập nhật trạng thái đơn hàng.';
            } catch (Throwable $e) {
                $_SESSION['admin_message'] = 'Có lỗi xảy ra khi cập nhật.';
            }
        }

        header('Location: index.php?page=admin_orders');
        exit;
    }

    // Danh sách sản phẩm dành cho Admin
    public function products()
    {
        $this->checkAdmin();

        $products = $this->productModel->getAllForAdmin();
        $categories = $this->productModel->getAllCategories();
        $brands = $this->productModel->getAllBrands();

        require __DIR__ . '/../views/admin/products.php';
    }

    // Hiển thị form và xử lý thêm sản phẩm
    public function addProduct()
    {
        $this->checkAdmin();

        $categories = $this->productModel->getAllCategories();
        $brands = $this->productModel->getAllBrands();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $categoryId = (int)($_POST['category_id'] ?? 0);
            $brandId = (int)($_POST['brand_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $salePrice = (float)($_POST['sale_price'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);

            if (
                $name === '' ||
                $categoryId <= 0 ||
                $brandId <= 0 ||
                $price <= 0 ||
                $salePrice < 0 ||
                $stock < 0
            ) {
                $_SESSION['admin_message'] = 'Vui lòng nhập thông tin hợp lệ.';
                header('Location: index.php?page=admin_product_add');
                exit;
            }

            $data = [
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'name' => $name,
                'price' => $price,
                'sale_price' => $salePrice,
                'description' => trim($_POST['description'] ?? ''),
                'ram' => trim($_POST['ram'] ?? ''),
                'rom' => trim($_POST['rom'] ?? ''),
                'cpu' => trim($_POST['cpu'] ?? ''),
                'screen' => trim($_POST['screen'] ?? ''),
                'camera' => trim($_POST['camera'] ?? ''),
                'battery' => trim($_POST['battery'] ?? ''),
                'operating_system' => trim($_POST['operating_system'] ?? ''),
                'stock' => $stock,
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            try {
                $result = $this->productModel->create($data);

                $_SESSION['admin_message'] = $result
                    ? 'Thêm sản phẩm thành công!'
                    : 'Không thể thêm sản phẩm.';
            } catch (Throwable $e) {
                $_SESSION['admin_message'] = 'Có lỗi khi thêm sản phẩm.';
            }

            header('Location: index.php?page=admin_products');
            exit;
        }

        require __DIR__ . '/../views/admin/product_add.php';
    }
    public function editProduct()
    {
        $this->checkAdmin();

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: index.php?page=admin_products');
            exit;
        }

        $product = $this->productModel->getByIdForAdmin($id);

        if (!$product) {
            $_SESSION['admin_message'] = 'Không tìm thấy sản phẩm.';
            header('Location: index.php?page=admin_products');
            exit;
        }

        $categories = $this->productModel->getAllCategories();
        $brands = $this->productModel->getAllBrands();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'brand_id' => (int)($_POST['brand_id'] ?? 0),
                'name' => trim($_POST['name'] ?? ''),
                'price' => (float)($_POST['price'] ?? 0),
                'sale_price' => (float)($_POST['sale_price'] ?? 0),
                'description' => trim($_POST['description'] ?? ''),
                'ram' => trim($_POST['ram'] ?? ''),
                'rom' => trim($_POST['rom'] ?? ''),
                'cpu' => trim($_POST['cpu'] ?? ''),
                'screen' => trim($_POST['screen'] ?? ''),
                'camera' => trim($_POST['camera'] ?? ''),
                'battery' => trim($_POST['battery'] ?? ''),
                'operating_system' => trim($_POST['operating_system'] ?? ''),
                'stock' => (int)($_POST['stock'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            if (
                $data['name'] === '' || $data['price'] <= 0 ||
                $data['sale_price'] < 0 || $data['stock'] < 0
            ) {
                $_SESSION['admin_message'] = 'Thông tin sản phẩm không hợp lệ.';
            } else {
                $this->productModel->update($id, $data);
                $_SESSION['admin_message'] = 'Cập nhật sản phẩm thành công!';
            }

            header('Location: index.php?page=admin_products');
            exit;
        }

        require __DIR__ . '/../views/admin/product_edit.php';
    }
}
