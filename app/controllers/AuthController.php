<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userModel = new User();
    }

    // Đăng ký
    public function register()
    {
        $errors = [];
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $_POST;

            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($fullName === '') {
                $errors[] = "Vui lòng nhập họ tên.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ.";
            }

            if (strlen($password) < 8) {
                $errors[] = "Mật khẩu phải có ít nhất 8 ký tự.";
            }

            if ($password !== $confirmPassword) {
                $errors[] = "Mật khẩu xác nhận không khớp.";
            }

            if (!$errors) {
                if ($this->userModel->getByEmail($email)) {
                    $errors[] = "Email này đã được đăng ký.";
                } else {
                    try {
                        $this->userModel->create([
                            'full_name' => $fullName,
                            'email' => $email,
                            'phone' => $phone,
                            'address' => $address,
                            'password' => $password
                        ]);

                        header('Location: index.php?page=login&registered=1');
                        exit;
                    } catch (Exception $e) {
                        $errors[] = "Không thể tạo tài khoản. Vui lòng thử lại.";
                    }
                }
            }
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    // Đăng nhập
    // Đăng nhập
    public function login()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'role_id' => (int)$user['role_id']
                ];

                header('Location: index.php?page=home');
                exit;
            } else {
                $errors[] = "Email hoặc mật khẩu không chính xác.";
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    // Đăng xuất
    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?page=home');
        exit;
    }
}
