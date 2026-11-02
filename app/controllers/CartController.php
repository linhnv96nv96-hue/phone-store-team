
<?php

require_once __DIR__ . '/../models/Product.php';

class CartController
{
    private $productModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->productModel = new Product();
    }

    // Thêm sản phẩm vào giỏ
    public function add()
    {
        $id = (int)($_POST['product_id'] ?? 0);
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));

        $product = $this->productModel->getById($id);

        if (!$product || $product['stock'] < $quantity) {
            header('Location: index.php?page=detail&id=' . $id);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $currentQuantity = $_SESSION['cart'][$id] ?? 0;

        if ($currentQuantity + $quantity <= $product['stock']) {
            $_SESSION['cart'][$id] = $currentQuantity + $quantity;
        }

        header('Location: index.php?page=cart');
        exit;
    }

    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        $products = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = $this->productModel->getById($id);

            if ($product) {
                $price = $product['sale_price'] > 0
                    ? $product['sale_price']
                    : $product['price'];

                $product['cart_quantity'] = $quantity;
                $product['subtotal'] = $price * $quantity;

                $total += $product['subtotal'];
                $products[] = $product;
            }
        }

        require_once __DIR__ . '/../views/cart/index.php';
    }

    // Cập nhật số lượng sản phẩm
    public function update()
    {
        $id = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        $product = $this->productModel->getById($id);

        if (!$product || $quantity <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            // Không cho số lượng vượt quá tồn kho
            $quantity = min($quantity, (int)$product['stock']);

            if ($quantity > 0) {
                $_SESSION['cart'][$id] = $quantity;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }

        header('Location: index.php?page=cart');
        exit;
    }

    // Xóa sản phẩm
    public function remove()
    {
        $id = (int)($_POST['product_id'] ?? 0);

        unset($_SESSION['cart'][$id]);

        header('Location: index.php?page=cart');
        exit;
    }
}
