<?php
$statusLabels = [
    1 => 'Đang hoạt động',
    0 => 'Ngừng hoạt động'
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>QUẢN LÝ SẢN PHẨM</h2>
        <p class="text-muted mb-0">Danh sách sản phẩm của cửa hàng</p>
    </div>

    <a href="index.php?page=admin_product_add"
        class="btn btn-primary">
        + Thêm sản phẩm
    </a>
</div>

<?php if (!empty($_SESSION['admin_message'])): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($_SESSION['admin_message']) ?>
    </div>
    <?php unset($_SESSION['admin_message']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Giá khuyến mãi</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= (int)$product['id'] ?></td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($product['name']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['brand_name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['category_name']) ?>
                                </td>

                                <td>
                                    <?= number_format((float)$product['price'], 0, ',', '.') ?>đ
                                </td>

                                <td>
                                    <?= number_format((float)$product['sale_price'], 0, ',', '.') ?>đ
                                </td>

                                <td><?= (int)$product['stock'] ?></td>

                                <td>
                                    <?php if ((int)$product['status'] === 1): ?>
                                        <span class="badge bg-success">
                                            Đang hoạt động
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            Ngừng hoạt động
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a
                                        href="index.php?page=admin_product_edit&id=<?= (int)$product['id'] ?>"
                                        class="btn btn-sm btn-warning">
                                        Sửa
                                    </a>

                                    <form
                                        action="index.php?page=admin_product_delete"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int)$product['id'] ?>">

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger">
                                            Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                Chưa có sản phẩm nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>