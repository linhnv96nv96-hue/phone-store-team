
<?php

require_once __DIR__ . '/../../config/database.php';

class Order
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Tạo đơn hàng và trừ tồn kho
    public function createOrder($userId, $address, $phone, $cart)
    {
        try {
            $this->conn->beginTransaction();

            $orderItems = [];
            $totalAmount = 0;

            // Kiểm tra sản phẩm và tồn kho
            foreach ($cart as $productId => $quantity) {

                $sql = "SELECT id, name, price, sale_price, stock
                        FROM products
                        WHERE id = :id AND status = 1
                        FOR UPDATE";

                $stmt = $this->conn->prepare($sql);
                $stmt->execute(['id' => $productId]);

                $product = $stmt->fetch();

                if (!$product) {
                    throw new Exception("Có sản phẩm không còn tồn tại.");
                }

                if ($quantity <= 0 || $quantity > $product['stock']) {
                    throw new Exception(
                        "Sản phẩm " . $product['name'] . " không đủ tồn kho."
                    );
                }

                $price = $product['sale_price'] > 0
                    ? $product['sale_price']
                    : $product['price'];

                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_id' => $product['id'],
                    'quantity' => $quantity,
                    'price' => $price
                ];
            }

            if (empty($orderItems)) {
                throw new Exception("Giỏ hàng đang trống.");
            }

            // Lưu đơn hàng
            $sql = "INSERT INTO orders
                    (user_id, total_amount, shipping_address, phone, status)
                    VALUES
                    (:user_id, :total_amount, :address, :phone, 'pending')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'address' => $address,
                'phone' => $phone
            ]);

            $orderId = $this->conn->lastInsertId();

            // Lưu chi tiết đơn hàng và cập nhật tồn kho
            foreach ($orderItems as $item) {

                $sql = "INSERT INTO order_details
                        (order_id, product_id, quantity, price)
                        VALUES
                        (:order_id, :product_id, :quantity, :price)";

                $stmt = $this->conn->prepare($sql);

                $stmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                $sql = "UPDATE products
                        SET stock = stock - :quantity
                        WHERE id = :product_id";

                $stmt = $this->conn->prepare($sql);

                $stmt->execute([
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product_id']
                ]);
            }

            $this->conn->commit();

            return $orderId;
        } catch (Exception $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }

    // Lấy lịch sử đơn hàng theo tài khoản khách hàng
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT
                    id,
                    total_amount,
                    shipping_address,
                    phone,
                    status,
                    created_at
                FROM orders
                WHERE user_id = :user_id
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn hàng của một khách hàng
    public function getOrderDetail($orderId, $userId)
    {
        $sql = "SELECT
                    o.id AS order_id,
                    o.total_amount,
                    o.shipping_address,
                    o.phone,
                    o.status,
                    o.created_at,
                    od.product_id,
                    od.quantity,
                    od.price,
                    p.name AS product_name
                FROM orders o
                INNER JOIN order_details od
                    ON o.id = od.order_id
                INNER JOIN products p
                    ON od.product_id = p.id
                WHERE o.id = :order_id
                    AND o.user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'order_id' => $orderId,
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hủy đơn hàng và hoàn lại số lượng tồn kho
    public function cancelOrder($orderId, $userId)
    {
        try {
            $this->conn->beginTransaction();

            // Khóa đơn hàng để tránh hủy hai lần
            $sql = "SELECT id, status
                FROM orders
                WHERE id = :order_id
                  AND user_id = :user_id
                FOR UPDATE";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'order_id' => $orderId,
                'user_id' => $userId
            ]);

            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order || $order['status'] !== 'pending') {
                $this->conn->rollBack();
                return false;
            }

            // Lấy các sản phẩm trong đơn hàng
            $sql = "SELECT product_id, quantity
                FROM order_details
                WHERE order_id = :order_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['order_id' => $orderId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Hoàn lại số lượng sản phẩm vào kho
            foreach ($items as $item) {
                $sql = "UPDATE products
                    SET stock = stock + :quantity
                    WHERE id = :product_id";

                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product_id']
                ]);
            }

            // Cập nhật trạng thái đơn hàng
            $sql = "UPDATE orders
                SET status = 'cancelled'
                WHERE id = :order_id
                  AND user_id = :user_id
                  AND status = 'pending'";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'order_id' => $orderId,
                'user_id' => $userId
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new Exception('Không thể cập nhật trạng thái đơn hàng.');
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $e;
        }
    }
    // Lấy danh sách tất cả đơn hàng cho Admin
    public function getAllOrders()
    {
        $sql = "SELECT
                o.id,
                o.user_id,
                o.total_amount,
                o.shipping_address,
                o.phone,
                o.status,
                o.created_at,
                u.full_name,
                u.email
            FROM orders o
            INNER JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Admin cập nhật trạng thái đơn hàng
    // Admin lấy thông tin chi tiết một đơn hàng
    public function getOrderDetailForAdmin($orderId)
    {
        $sql = "SELECT
                o.id AS order_id,
                o.total_amount,
                o.shipping_address,
                o.phone,
                o.status,
                o.created_at,
                u.full_name,
                u.email,
                od.product_id,
                od.quantity,
                od.price,
                p.name AS product_name
            FROM orders o
            INNER JOIN users u
                ON o.user_id = u.id
            INNER JOIN order_details od
                ON o.id = od.order_id
            INNER JOIN products p
                ON od.product_id = p.id
            WHERE o.id = :order_id
            ORDER BY od.product_id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'order_id' => $orderId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Admin cập nhật trạng thái đơn hàng theo đúng thứ tự
    public function updateOrderStatus($orderId, $status)
    {
        $allowedTransitions = [
            'pending' => 'confirmed',
            'confirmed' => 'shipping',
            'shipping' => 'completed'
        ];

        // Kiểm tra trạng thái mới có hợp lệ không
        if (!in_array($status, $allowedTransitions, true)) {
            return false;
        }

        // Chỉ cập nhật nếu đơn đang ở trạng thái trước đó tương ứng
        $currentStatus = array_search(
            $status,
            $allowedTransitions,
            true
        );

        if ($currentStatus === false) {
            return false;
        }

        $sql = "UPDATE orders
            SET status = :status
            WHERE id = :id
            AND status = :current_status";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'status' => $status,
            'id' => $orderId,
            'current_status' => $currentStatus
        ]);

        return $stmt->rowCount() === 1;
    }
}
