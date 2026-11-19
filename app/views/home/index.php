<section class="hero-banner mb-5">
    <div class="hero-overlay">
        <div class="hero-content">
            <span class="hero-tag">PHONE STORE</span>
            <h1>ĐIỆN THOẠI CHÍNH HÃNG</h1>
            <p>
                Khám phá công nghệ mới, hiệu năng mạnh mẽ và ưu đãi hấp dẫn mỗi ngày.
            </p>
            <a href="#products" class="btn hero-btn">
                Mua sắm ngay
            </a>
        </div>
    </div>
</section>

<section id="products" class="product-section py-4">
    <div class="section-header mb-4">
        <?php if (!empty($keyword ?? '')): ?>
            <div>
                <span class="section-label">TÌM KIẾM</span>
                <h2>KẾT QUẢ TÌM KIẾM 1000</h2>
                <h2>KẾT QUẢ TÌM KIẾM 1000</h2>
                <p>
                    Từ khóa:
                    <strong><?= htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                    — Tìm thấy <?= count($products ?? []) ?> sản phẩm
                </p>
            </div>
        <?php else: ?>
            <div>
                <span class="section-label">SẢN PHẨM</span>
                <h2>ĐIỆN THOẠI NỔI BẬT</h2>
                <p>Những sản phẩm mới nhất tại Phone Store</p>
                <p>Những sản phẩm mới nhất tại Phone Store</p>
                <p>Những sản phẩm mới nhất tại Phone Store</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>

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

                $originalPrice = (int)($product['price'] ?? 0);
                $salePrice = (int)($product['sale_price'] ?? 0);
                $finalPrice = $salePrice > 0 ? $salePrice : $originalPrice;

                $discountPercent = 0;
                if ($salePrice > 0 && $originalPrice > 0 && $salePrice < $originalPrice) {
                    $discountPercent = round((($originalPrice - $salePrice) / $originalPrice) * 100);
                }
                ?>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="product-card h-100">

                        <div class="product-thumb">
                            <?php if ($discountPercent > 0): ?>
                                <span class="discount-badge">-<?= $discountPercent ?>%</span>
                            <?php endif; ?>

                            <img
                                src="/phone_store/public/assets/images/<?= htmlspecialchars($imageName) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                onerror="this.onerror=null; this.src='/phone_store/public/assets/images/phone-placeholder.svg';">
                        </div>

                        <div class="product-info">
                            <span class="brand-pill">
                                <?= htmlspecialchars($product['brand_name']) ?>
                            </span>

                            <h5 class="product-name">
                                <?= htmlspecialchars($product['name']) ?>
                            </h5>

                            <div class="price-box">
                                <div class="current-price">
                                    <?= number_format($finalPrice, 0, ',', '.') ?>đ
                                </div>

                                <?php if ($salePrice > 0 && $salePrice < $originalPrice): ?>
                                    <div class="old-price">
                                        <?= number_format($originalPrice, 0, ',', '.') ?>đ
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="stock-row">
                                <span class="stock-dot"></span>
                                <span>Còn <?= (int)$product['stock'] ?> sản phẩm</span>
                            </div>

                            <a href="/phone_store/public/index.php?page=detail&id=<?= (int)$product['id'] ?>"
                                class="btn btn-detail w-100">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>

            <div class="col-12">
                <div class="empty-box">
                    <?php if (!empty($keyword)): ?>
                        <p>
                            Không tìm thấy sản phẩm phù hợp với:
                            <strong><?= htmlspecialchars($keyword) ?></strong>
                        </p>
                    <?php else: ?>
                        <p>Hiện chưa có sản phẩm nào.</p>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
</section>