
<?php

require_once __DIR__ . '/../models/Product.php';

class HomeController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    // Trang chủ và tìm kiếm sản phẩm
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword !== '') {
            $products = $this->productModel->search($keyword);
        } else {
            $products = $this->productModel->getAll();
        }

        require_once __DIR__ . '/../views/home/index.php';
    }

    // Trang chi tiết sản phẩm
    public function detail()
    {
        $id = (int)($_GET['id'] ?? 0);

        // Lấy sản phẩm từ database
        $product = $this->productModel->getById($id);

        // Kiểm tra sản phẩm
        if (!$product) {
            http_response_code(404);
            echo "404 - Không tìm thấy sản phẩm.";
            return;
        }

        // Mở giao diện chi tiết
        require_once __DIR__ . '/../views/product/detail.php';
    }
}
