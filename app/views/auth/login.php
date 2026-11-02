<?php

/** @var array $errors */
/** @var array $old */
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">ĐĂNG NHẬP</h3>

                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success">
                        Đăng ký thành công! Hãy đăng nhập.
                    </div>
                <?php endif; ?>

                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endforeach; ?>

                <form method="POST" action="index.php?page=login">

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password"
                            class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        Đăng nhập
                    </button>

                </form>

                <p class="text-center mt-3 mb-0">
                    Chưa có tài khoản?
                    <a href="index.php?page=register">Đăng ký ngay</a>
                </p>

            </div>
        </div>

    </div>
</div>