<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>THÊM SẢN PHẨM</h2>

        <a href="index.php?page=admin_products" class="btn btn-secondary">
            Quay lại
        </a>
    </div>

    <?php if (!empty($_SESSION['admin_message'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['admin_message']) ?>
        </div>
        <?php unset($_SESSION['admin_message']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="index.php?page=admin_product_add" method="POST">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên sản phẩm *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Danh mục *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int)$category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Thương hiệu *</label>
                        <select name="brand_id" class="form-select" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= (int)$brand['id'] ?>">
                                    <?= htmlspecialchars($brand['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá gốc *</label>
                        <input type="number" name="price" class="form-control"
                            min="1" step="0.01" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" name="sale_price" class="form-control"
                            min="0" step="0.01" value="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Số lượng tồn kho</label>
                        <input type="number" name="stock" class="form-control"
                            min="0" value="0">
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">Thông số kỹ thuật</h5>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">RAM</label>
                        <input type="text" name="ram" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">ROM</label>
                        <input type="text" name="rom" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">CPU</label>
                        <input type="text" name="cpu" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Màn hình</label>
                        <input type="text" name="screen" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Camera</label>
                        <input type="text" name="camera" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pin</label>
                        <input type="text" name="battery" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Hệ điều hành</label>
                        <input type="text" name="operating_system" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả sản phẩm</label>
                    <textarea name="description" class="form-control"
                        rows="4"></textarea>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="status" value="1"
                        class="form-check-input" id="status" checked>
                    <label class="form-check-label" for="status">
                        Đang hoạt động
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    Lưu sản phẩm
                </button>

                <a href="index.php?page=admin_products"
                    class="btn btn-secondary">
                    Hủy
                </a>

            </form>
        </div>
    </div>
</div>