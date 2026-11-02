<?php
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

$nextStatuses = [
    'pending' => 'confirmed',
    'confirmed' => 'shipping',
    'shipping' => 'completed'
];
?>

<h2 class="mb-4">QUẢN LÝ ĐƠN HÀNG</h2>

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
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Điện thoại</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                Chưa có đơn hàng nào.
                            </td>
                        </tr>
                    <?php else: ?>

                        <?php foreach ($orders as $order): ?>
                            <?php
                            $status = $order['status'];
                            $label = $statusLabels[$status] ?? $status;
                            $color = $statusColors[$status] ?? 'secondary';
                            $nextStatus = $nextStatuses[$status] ?? null;

                            ?>
                            <pre>




                            </tr>
                            <tr>
                                <td>#<?= (int)$order['id'] ?></td>

                                <td>
                                    <?= htmlspecialchars($order['full_name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($order['email']) ?>
                                </td>

                                <td>
                                    <?= date(
                                        'd/m/Y H:i',
                                        strtotime($order['created_at'])
                                    ) ?>
                                </td>

                                <td class="text-danger fw-bold">
                                    <?= number_format(
                                        $order['total_amount'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>đ
                                </td>

                                <td>
                                    <?= htmlspecialchars($order['phone']) ?>
                                </td>

                                <td>
                                    <span class="badge bg-<?= $color ?>">
                                        <?= htmlspecialchars($label) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (!empty($nextStatus)): ?>
                                        <form action="index.php?page=admin_update_order" method="POST">
                                            <input type="hidden" name="order_id"
                                                value="<?= (int)$order['id'] ?>">

                                            <input type="hidden" name="status"
                                                value="<?= htmlspecialchars($nextStatus) ?>">

                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <?= htmlspecialchars($statusLabels[$nextStatus]) ?>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Không có thao tác</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>