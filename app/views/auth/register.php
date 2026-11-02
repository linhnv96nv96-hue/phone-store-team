<?php

/** @var array $errors */
/** @var array $old */
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">ĐĂNG KÝ TÀI KHOẢN</h3>

                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endforeach; ?>

                <form method="POST" action="index.php?page=register">

                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" name="full_name"
                            class="form-control" required
                            value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                            class="form-control" required
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone"
                            class="form-control"
                            value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="address"
                            class="form-control"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password"
                            class="form-control" minlength="8" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="confirm_password"
                            class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        Đăng ký
                    </button>

                </form>

                <p class="text-center mt-3 mb-0">
                    Đã có tài khoản?
                    <a href="index.php?page=login">Đăng nhập</a>
                </p>

            </div>
        </div>

    </div>
</div>