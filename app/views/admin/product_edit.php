```php
<h2 class="mb-4">SỬA SẢN PHẨM</h2>

<form action="index.php?page=admin_product_edit&id=<?= (int)$product['id'] ?>"
    method="POST">

    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="name" class="form-control"
            value="<?= htmlspecialchars($product['name']) ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-select" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id'] ?>"
                        <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Thương hiệu</label>
            <select name="brand_id" class="form-select" required>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= (int)$brand['id'] ?>"
                        <?= $product['brand_id'] == $brand['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Giá gốc</label>
            <input type="number" name="price" class="form-control"
                value="<?= htmlspecialchars($product['price']) ?>" min="1" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Giá khuyến mãi</label>
            <input type="number" name="sale_price" class="form-control"
                value="<?= htmlspecialchars($product['sale_price'] ?? 0) ?>" min="0">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Số lượng</label>
            <input type="number" name="stock" class="form-control"
                value="<?= (int)$product['stock'] ?>" min="0" required>
        </div>
    </div>

    <?php
    $fields = [
        'ram' => 'RAM',
        'rom' => 'ROM',
        'cpu' => 'CPU',
        'screen' => 'Màn hình',
        'camera' => 'Camera',
        'battery' => 'Pin',
        'operating_system' => 'Hệ điều hành'
    ];
    ?>

    <div class="row">
        <?php foreach ($fields as $key => $label): ?>
            <div class="col-md-6 mb-3">
                <label class="form-label"><?= $label ?></label>
                <input type="text" name="<?= $key ?>" class="form-control"
                    value="<?= htmlspecialchars($product[$key] ?? '') ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" name="status" class="form-check-input"
            id="status" <?= !empty($product['status']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="status">Đang kinh doanh</label>
    </div>

    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    <a href="index.php?page=admin_products" class="btn btn-secondary">Quay lại</a>
</form>
```