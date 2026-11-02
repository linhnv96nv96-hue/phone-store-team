<?php

$statusLabels = [
    'pending'   => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'shipping'  => 'Đang giao hàng',
    'completed' => 'Đã giao hàng',
    'cancelled' => 'Đã hủy'
];

$statusColors = [
    'pending'   => 'warning',
    'confirmed' => 'info',
    'shipping'  => 'primary',
    'completed' => 'success',
    'cancelled' => 'danger'
];

?>

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">ĐƠN HÀNG CỦA TÔI</h2>

        <a href="index.php" class="btn btn-outline-primary">
            Tiếp tục mua sắm
        </a>
    </div>

    <?php if (!empty($_SESSION['order_message'])): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['order_message']) ?>

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>

        <?php unset($_SESSION['order_message']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <?php if (empty($orders)): ?>

                <div class="alert alert-info mb-0">
                    Bạn chưa có đơn hàng nào.
                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Địa chỉ giao hàng</th>
                                <th>Số điện thoại</th>
                                <th>Trạng thái</th>
                                <th style="min-width: 150px;">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($orders as $order): ?>

                                <?php
                                $orderId = (int)$order['id'];
                                $status = $order['status'] ?? '';

                                $label = $statusLabels[$status] ?? $status;
                                $color = $statusColors[$status] ?? 'secondary';
                                ?>

                                <tr>

                                    <td>
                                        <strong>#<?= $orderId ?></strong>
                                    </td>

                                    <td>
                                        <?= !empty($order['created_at'])
                                            ? date('d/m/Y H:i', strtotime($order['created_at']))
                                            : '' ?>
                                    </td>

                                    <td class="text-danger fw-bold">
                                        <?= number_format(
                                            (float)$order['total_amount'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>đ
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($order['shipping_address'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($order['phone'] ?? '') ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= htmlspecialchars($color) ?>">
                                            <?= htmlspecialchars($label) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-2">

                                            <!-- Xem chi tiết -->
                                            <a
                                                href="index.php?page=order_detail&id=<?= $orderId ?>"
                                                class="btn btn-sm btn-primary">

                                                Xem chi tiết
                                            </a>

                                            <!-- Chỉ được hủy khi đang chờ xác nhận -->
                                            <?php if ($status === 'pending'): ?>

                                                <form
                                                    action="index.php?page=order_cancel"
                                                    method="POST"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng #<?= $orderId ?> không?');">

                                                    <input
                                                        type="hidden"
                                                        name="order_id"
                                                        value="<?= $orderId ?>">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-danger w-100">

                                                        Hủy đơn hàng
                                                    </button>

                                                </form>

                                            <?php endif; ?>

                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>
    </div>

</div>