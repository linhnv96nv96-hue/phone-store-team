<?php

require_once __DIR__ . '/../../config/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Tìm tài khoản bằng email
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetch();
    }

    // Tạo tài khoản khách hàng
    public function create($data)
    {
        // Tìm role customer, không đoán ID
        $stmt = $this->conn->prepare(
            "SELECT id FROM roles WHERE name = 'customer' LIMIT 1"
        );
        $stmt->execute();

        $role = $stmt->fetch();

        if (!$role) {
            throw new Exception("Chưa có quyền customer trong bảng roles.");
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO users
                (role_id, full_name, email, phone, password, address)
                VALUES
                (:role_id, :full_name, :email, :phone, :password, :address)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'role_id' => $role['id'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?: null,
            'password' => $hashedPassword,
            'address' => $data['address'] ?: null
        ]);
    }
}
