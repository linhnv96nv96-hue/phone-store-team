<?php

/**
 * @var array $errors
 * @var array $products
 * @var float|int $total
 */
?>
<h2 class="mb-4">THÔNG TIN ĐẶT HÀNG</h2>

<?php foreach ($errors as $error): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endforeach; ?>

<div class="row">

    <div class="col-md-7">

        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="mb-3">Thông tin giao hàng</h5>

                <p>
                    Khách hàng:
                    <strong>
                        <?= htmlspecialchars($_SESSION['user']['full_name']) ?>
                    </strong>
                </p>

                <form method="POST" action="index.php?page=checkout">

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text"
                            name="phone"
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <textarea name="address"
                            class="form-control"
                            rows="3"
                            required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phương thức thanh toán</label>
                        <input type="text"
                            class="form-control"
                            value="Thanh toán khi nhận hàng (COD)"
                            readonly>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100">
                        XÁC NHẬN ĐẶT HÀNG
                    </button>

                </form>

            </div>
        </div>

    </div>

    <div class="col-md-5">

        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="mb-3">Đơn hàng của bạn</h5>

                <?php foreach ($products as $product): ?>

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong>
                                <?= htmlspecialchars($product['name']) ?>
                            </strong>

                            <div class="text-muted">
                                Số lượng: <?= (int)$product['cart_quantity'] ?>
                            </div>
                        </div>

                        <span>
                            <?= number_format($product['subtotal'], 0, ',', '.') ?>đ
                        </span>
                    </div>

                <?php endforeach; ?>

                <hr>

                <div class="d-flex justify-content-between">
                    <strong>Tổng thanh toán:</strong>

                    <strong class="text-danger fs-5">
                        <?= number_format($total, 0, ',', '.') ?>đ
                    </strong>
                </div>

            </div>
        </div>

    </div>

</div>