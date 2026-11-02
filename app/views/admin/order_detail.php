<?php
if (empty($orderDetails)) {
    echo '<div class="alert alert-warning">Không có thông tin đơn hàng.</div>';
    return;
}

$order = $orderDetails[0];

$statusLabels = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'shipping' => 'Đang giao hàng',
    'completed' => 'Đã giao hàng',
    'cancelled' => 'Đã hủy'
];

$statusColors = [
    'pending' => 'warning',
    'confirmed' => 'info',
    'shipping' => 'primary',
    'completed' => 'success',
    'cancelled' => 'danger'
];

$status = $order['status'];
?>

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>CHI TIẾT ĐƠN HÀNG #<?= (int)$order['order_id'] ?></h2>

        <a href="index.php?page=admin_orders"
            class="btn btn-secondary">
            Quay lại
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            Thông tin đơn hàng
        </div>

        <div class="card-body">
            <p><strong>Mã đơn:</strong>
                #<?= (int)$order['order_id'] ?></p>

            <p><strong>Ngày đặt:</strong>
                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
            </p>

            <p><strong>Trạng thái:</strong>
                <span class="badge bg-<?= $statusColors[$status] ?? 'secondary' ?>">
                    <?= htmlspecialchars($statusLabels[$status] ?? $status) ?>
                </span>
            </p>

            <p><strong>Tên khách hàng:</strong>
                <?= htmlspecialchars($order['full_name']) ?></p>

            <p><strong>Email:</strong>
                <?= htmlspecialchars($order['email']) ?></p>

            <p><strong>Số điện thoại:</strong>
                <?= htmlspecialchars($order['phone']) ?></p>

            <p><strong>Địa chỉ giao hàng:</strong>
                <?= htmlspecialchars($order['shipping_address']) ?></p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Danh sách sản phẩm
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($orderDetails as $index => $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= $index + 1 ?></td>

                                <td>
                                    <?= htmlspecialchars($item['product_name']) ?>
                                </td>

                                <td>
                                    <?= number_format($item['price'], 0, ',', '.') ?>đ
                                </td>

                                <td><?= (int)$item['quantity'] ?></td>

                                <td class="text-danger fw-bold">
                                    <?= number_format($subtotal, 0, ',', '.') ?>đ
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">
                                Tổng tiền:
                            </td>

                            <td class="text-danger fw-bold">
                                <?= number_format($order['total_amount'], 0, ',', '.') ?>đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>