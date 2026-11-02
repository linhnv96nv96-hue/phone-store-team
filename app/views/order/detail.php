<?php

/** @var array $order */
/** @var array $orderItems */

$statusLabels = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'shipping' => 'Đang giao hàng',
    'completed' => 'Đã giao hàng',
    'cancelled' => 'Đã hủy'
];

$status = $order['status'];
$statusLabel = $statusLabels[$status] ?? $status;
?>

<h2 class="mb-4">CHI TIẾT ĐƠN HÀNG #<?= (int)$order['order_id'] ?></h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <h5 class="mb-3">Thông tin đơn hàng</h5>

        <p>
            <strong>Mã đơn hàng:</strong>
            #<?= (int)$order['order_id'] ?>
        </p>

        <p>
            <strong>Ngày đặt:</strong>
            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
        </p>

        <p>
            <strong>Trạng thái:</strong>
            <span class="badge bg-warning text-dark">
                <?= htmlspecialchars($statusLabel) ?>
            </span>
        </p>

        <p>
            <strong>Địa chỉ giao hàng:</strong>
            <?= htmlspecialchars($order['shipping_address']) ?>
        </p>

        <p>
            <strong>Số điện thoại:</strong>
            <?= htmlspecialchars($order['phone']) ?>
        </p>

        <p class="mb-0">
            <strong>Thanh toán:</strong>
            Thanh toán khi nhận hàng (COD)
        </p>

    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">Sản phẩm đã đặt</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($item['product_name']) ?>
                            </td>

                            <td>
                                <?= number_format($item['price'], 0, ',', '.') ?>đ
                            </td>

                            <td>
                                <?= (int)$item['quantity'] ?>
                            </td>

                            <td class="text-danger fw-bold">
                                <?= number_format(
                                    $item['price'] * $item['quantity'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>đ
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

        <div class="text-end">
            <h4 class="text-danger">
                Tổng thanh toán:
                <?= number_format($order['total_amount'], 0, ',', '.') ?>đ
            </h4>
        </div>

    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <a href="index.php?page=order_history" class="btn btn-outline-primary">
        ← Quay lại lịch sử đơn hàng
    </a>

    <?php if ($order['status'] === 'pending'): ?>
        <form action="index.php?page=order_cancel" method="POST"
            onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">

            <input type="hidden" name="order_id"
                value="<?= (int)$order['order_id'] ?>">

            <button type="submit" class="btn btn-danger">
                Hủy đơn hàng
            </button>
        </form>
    <?php endif; ?>
</div>