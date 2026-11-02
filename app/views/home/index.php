<section class="banner mb-4">
    <div>
        <h1>ĐIỆN THOẠI CHÍNH HÃNG</h1>
        <p>Khám phá công nghệ mới – Ưu đãi hấp dẫn mỗi ngày</p>
        <a href="#products" class="btn btn-light">
            Mua sắm ngay
        </a>
    </div>
</section>


<section id="products" class="product-section">

    <div class="section-title mb-4">

        <?php if (!empty($keyword ?? '')): ?>

            <h2>KẾT QUẢ TÌM KIẾM</h2>

            <p>
                Từ khóa:
                <strong><?= htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                — Tìm thấy <?= count($products ?? []) ?> sản phẩm
            </p>

        <?php else: ?>


            <h2>ĐIỆN THOẠI NỔI BẬT</h2>

            <p>Những sản phẩm mới nhất tại Phone Store</p>

        <?php endif; ?>

    </div>


    <div class="row g-4">

        <?php if (!empty($products)): ?>

            <?php foreach ($products as $product): ?>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card">




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

                        <div class="product-image">
                            <img
                                src="/phone_store/public/assets/images/<?= htmlspecialchars($imageName) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                onerror="this.onerror=null; this.src='/phone_store/public/assets/images/phone-placeholder.svg';">
                        </div>




                        <div class="product-info">

                            <span class="brand">
                                <?= htmlspecialchars($product['brand_name']) ?>
                            </span>

                            <h5>
                                <?= htmlspecialchars($product['name']) ?>
                            </h5>

                            <div class="price">
                                <strong>
                                    <?= number_format(
                                        $product['sale_price'] > 0
                                            ? $product['sale_price']
                                            : $product['price'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>đ
                                </strong>
                            </div>

                            <p class="stock">
                                Còn <?= (int)$product['stock'] ?> sản phẩm
                            </p>

                            <a href="/phone_store/public/index.php?page=detail&id=<?= (int)$product['id'] ?>"
                                class="btn btn-danger w-100">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <?php if (!empty($keyword)): ?>

                <p>
                    Không tìm thấy sản phẩm phù hợp với:
                    <strong><?= htmlspecialchars($keyword) ?></strong>
                </p>

            <?php else: ?>

                <p>Hiện chưa có sản phẩm nào.</p>

            <?php endif; ?>

        <?php endif; ?>

    </div>
</section>