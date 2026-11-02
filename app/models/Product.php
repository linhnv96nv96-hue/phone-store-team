
<?php

require_once __DIR__ . '/../../config/database.php';

class Product
{
    private $conn;
    private $table = "products";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Lấy tất cả sản phẩm đang hoạt động
    public function getAll()
    {
        $sql = "SELECT 
                    p.*,
                    b.name AS brand_name,
                    c.name AS category_name
                FROM products p
                INNER JOIN brands b ON p.brand_id = b.id
                INNER JOIN categories c ON p.category_id = c.id
                WHERE p.status = 1
                ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy một sản phẩm theo ID

    // Lấy chi tiết một sản phẩm theo ID
    public function getById($id)
    {
        $sql = "SELECT
                    p.*,
                    b.name AS brand_name,
                    c.name AS category_name
                FROM products p
                INNER JOIN brands b ON p.brand_id = b.id
                INNER JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id
                AND p.status = 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Tìm kiếm sản phẩm theo tên hoặc thương hiệu

    // Tìm kiếm sản phẩm theo tên hoặc thương hiệu
    public function search($keyword)
    {
        $sql = "SELECT
                p.*,
                b.name AS brand_name,
                c.name AS category_name
            FROM products p
            INNER JOIN brands b ON p.brand_id = b.id
            INNER JOIN categories c ON p.category_id = c.id
            WHERE p.status = 1
            AND (
                p.name LIKE :name_keyword
                OR b.name LIKE :brand_keyword
            )
            ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($sql);

        $searchKeyword = "%" . $keyword . "%";

        $stmt->bindValue(':name_keyword', $searchKeyword);
        $stmt->bindValue(':brand_keyword', $searchKeyword);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: Lấy tất cả sản phẩm, kể cả sản phẩm ngừng hoạt động
    public function getAllForAdmin()
    {
        $sql = "SELECT
                    p.*,
                    b.name AS brand_name,
                    c.name AS category_name
                FROM products p
                INNER JOIN brands b ON p.brand_id = b.id
                INNER JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: Lấy danh sách danh mục
    public function getAllCategories()
    {
        $sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: Lấy danh sách thương hiệu
    public function getAllBrands()
    {
        $sql = "SELECT id, name FROM brands ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: Thêm sản phẩm mới
    public function create($data)
    {
        $sql = "INSERT INTO products (
                    category_id,
                    brand_id,
                    name,
                    price,
                    sale_price,
                    description,
                    ram,
                    rom,
                    cpu,
                    screen,
                    camera,
                    battery,
                    operating_system,
                    stock,
                    status
                ) VALUES (
                    :category_id,
                    :brand_id,
                    :name,
                    :price,
                    :sale_price,
                    :description,
                    :ram,
                    :rom,
                    :cpu,
                    :screen,
                    :camera,
                    :battery,
                    :operating_system,
                    :stock,
                    :status
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'description' => $data['description'],
            'ram' => $data['ram'],
            'rom' => $data['rom'],
            'cpu' => $data['cpu'],
            'screen' => $data['screen'],
            'camera' => $data['camera'],
            'battery' => $data['battery'],
            'operating_system' => $data['operating_system'],
            'stock' => $data['stock'],
            'status' => $data['status']
        ]);
    }
    public function getByIdForAdmin($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE products SET
        category_id = :category_id,
        brand_id = :brand_id,
        name = :name,
        price = :price,
        sale_price = :sale_price,
        description = :description,
        ram = :ram,
        rom = :rom,
        cpu = :cpu,
        screen = :screen,
        camera = :camera,
        battery = :battery,
        operating_system = :operating_system,
        stock = :stock,
        status = :status
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'description' => $data['description'],
            'ram' => $data['ram'],
            'rom' => $data['rom'],
            'cpu' => $data['cpu'],
            'screen' => $data['screen'],
            'camera' => $data['camera'],
            'battery' => $data['battery'],
            'operating_system' => $data['operating_system'],
            'stock' => $data['stock'],
            'status' => $data['status'],
            'id' => $id
        ]);
    }
}
