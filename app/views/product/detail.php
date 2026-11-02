<?php

/** @var array $product */
?>
<div class="row g-4">

    <div class="col-md-5">
        <?php
        $productImages = [
            'iPhone 15 128GB' => 'iphone-15.jpg',
            'iPhone 16 Pro Max 256GB' => 'iphone-16-pro-max.jpg',
            'Samsung Galaxy A56 5G' => 'samsung-a56.jpg',
            'Samsung Galaxy S25 Ultra' => 'phone-placeholder.svg',
            'Xiaomi 15 256GB' => 'xiaomi-15.webp',
            'OPPO Reno13 5G' => 'oppo-reno13.png'
        ];

        $imageName = $productImages[$product['name']] ?? 'phone-placeholder.svg';
        ?>

        <div class="product-card p-3">
            <img
                src="/phone_store/public/assets/images/<?= htmlspecialchars($imageName) ?>"
                alt="<?= htmlspecialchars($product['name']) ?>"
                class="img-fluid"
                onerror="this.onerror=null;this.src='/phone_store/public/assets/images/phone-placeholder.svg';">
        </div>
    </div>

    <div class="col-md-7">
        <span class="brand">
            <?= htmlspecialchars($product['brand_name']) ?>
        </span>

        <h2 class="mt-2">
            <?= htmlspecialchars($product['name']) ?>
        </h2>

        <h3 class="text-danger my-3">
            <?= number_format(
                $product['sale_price'] > 0
                    ? $product['sale_price']
                    : $product['price'],
                0,
                ',',
                '.'
            ) ?>đ
        </h3>

        <p>Danh mục: <?= htmlspecialchars($product['category_name']) ?></p>

        <p>
            Tình trạng:
            <?php if ($product['stock'] > 0): ?>
                <span class="text-success">Còn hàng</span>
            <?php else: ?>
                <span class="text-danger">Hết hàng</span>
            <?php endif; ?>
        </p>

        <p>Số lượng còn: <?= (int)$product['stock'] ?> sản phẩm</p>

        <hr>

        <h5>Thông tin sản phẩm</h5>

        <p>
            <?= !empty($product['description'])
                ? nl2br(htmlspecialchars($product['description']))
                : 'Sản phẩm điện thoại chính hãng tại Phone Store.' ?>
        </p>

        <?php if ($product['stock'] > 0): ?>

            <?php if ($product['stock'] > 0): ?>

                <form method="POST" action="/phone_store/public/index.php?page=add_cart">

                    <input type="hidden"
                        name="product_id"
                        value="<?= (int)$product['id'] ?>">

                    <label class="form-label">Số lượng:</label>

                    <input type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        max="<?= (int)$product['stock'] ?>"
                        class="form-control mb-3"
                        style="max-width: 120px;">

                    <button type="submit" class="btn btn-danger btn-lg">
                        Thêm vào giỏ hàng
                    </button>

                </form>

            <?php else: ?>

                <button class="btn btn-secondary btn-lg" disabled>
                    Hết hàng
                </button>

            <?php endif; ?>
        <?php else: ?>

            <button class="btn btn-secondary btn-lg mt-3" disabled>
                Hết hàng
            </button>

        <?php endif; ?>
    </div>
</div>