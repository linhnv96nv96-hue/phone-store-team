<?php

/** @var array $products */
/** @var int|float $total */
?>

<h2 class="mb-4">GIỎ HÀNG CỦA BẠN</h2>

<?php if (empty($products)): ?>

    <div class="alert alert-info">
        Giỏ hàng đang trống.
    </div>

    <a href="/phone_store/public/" class="btn btn-primary">
        Tiếp tục mua hàng
    </a>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($product['name']) ?>
                        </td>

                        <td>
                            <?= number_format(
                                $product['sale_price'] > 0
                                    ? $product['sale_price']
                                    : $product['price'],
                                0,
                                ',',
                                '.'
                            ) ?>đ
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <form method="POST"
                                    action="index.php?page=update_cart">
                                    <input type="hidden"
                                        name="product_id"
                                        value="<?= (int)$product['id'] ?>">
                                    <input type="hidden"
                                        name="quantity"
                                        value="<?= (int)$product['cart_quantity'] - 1 ?>">
                                    <button type="submit"
                                        class="btn btn-outline-secondary btn-sm">
                                        −
                                    </button>
                                </form>

                                <strong>
                                    <?= (int)$product['cart_quantity'] ?>
                                </strong>

                                <form method="POST"
                                    action="index.php?page=update_cart">
                                    <input type="hidden"
                                        name="product_id"
                                        value="<?= (int)$product['id'] ?>">
                                    <input type="hidden"
                                        name="quantity"
                                        value="<?= (int)$product['cart_quantity'] + 1 ?>">
                                    <button type="submit"
                                        class="btn btn-outline-secondary btn-sm"
                                        <?= $product['cart_quantity'] >= $product['stock'] ? 'disabled' : '' ?>>
                                        +
                                    </button>
                                </form>

                            </div>
                        </td>

                        <td class="text-danger fw-bold">
                            <?= number_format($product['subtotal'], 0, ',', '.') ?>đ
                        </td>

                        <td>
                            <form method="POST"
                                action="index.php?page=remove_cart">
                                <input type="hidden"
                                    name="product_id"
                                    value="<?= (int)$product['id'] ?>">
                                <button type="submit"
                                    class="btn btn-danger btn-sm">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-4">
        <h4 class="text-danger mb-3">
            Tổng tiền: <?= number_format($total, 0, ',', '.') ?>đ
        </h4>

        <a href="/phone_store/public/" class="btn btn-outline-primary">
            Tiếp tục mua hàng
        </a>

        <a href="index.php?page=checkout"
            class="btn btn-danger btn-lg ms-2">
            TIẾN HÀNH THANH TOÁN
        </a>
    </div>

<?php endif; ?>