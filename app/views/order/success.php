<?php

/**
 * @var array $errors
 * @var array $products
 * @var float|int $total
 */
?>
<?php
/** @var int|string|null $orderId */
?>
<div class="text-center py-5">

    <div class="mb-3">
        <span class="text-success" style="font-size: 60px;">✓</span>
    </div>

    <h2 class="text-success">ĐẶT HÀNG THÀNH CÔNG!</h2>

    <p class="mt-3">
        Cảm ơn bạn đã mua hàng tại Phone Store.
    </p>

    <p>
        Mã đơn hàng của bạn:
        <strong class="text-danger">
            #<?= (int)$orderId ?>
        </strong>
    </p>

    <p>
        Phương thức thanh toán: Thanh toán khi nhận hàng (COD).
    </p>

    <a href="/phone_store/public/" class="btn btn-danger mt-3">
        Tiếp tục mua hàng
    </a>

</div>