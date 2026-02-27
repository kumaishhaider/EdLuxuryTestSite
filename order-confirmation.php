<?php
/**
 * Order Confirmation Page
 */

$pageTitle = 'Order Confirmation';
require_once 'includes/header.php';

$orderNumber = $_GET['order'] ?? '';

if (empty($orderNumber)) {
    Helpers::redirect(Helpers::url());
}

$orderModel = new Order();
$order = $orderModel->getByOrderNumber($orderNumber);

if (!$order) {
    echo '<div class="container my-5 text-center"><h1>' . (CURRENT_LANG === 'ar' ? 'الطلب غير موجود' : 'Order Not Found') . '</h1></div>';
    require_once 'includes/footer.php';
    exit;
}

?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
                </div>
                <h1 class="mb-3 fw-bold">
                    <?php echo CURRENT_LANG === 'ar' ? 'شكراً لطلبكم!' : 'Thank You for Your Order!'; ?>
                </h1>
                <p class="lead opacity-75">
                    <?php echo CURRENT_LANG === 'ar' ? 'تم استلام طلبكم بنجاح وتم تأكيده.' : 'Your order has been successfully placed.'; ?>
                </p>
                <div class="bg-light p-3 rounded-pill d-inline-block px-4">
                    <span class="text-muted small">
                        <?php echo CURRENT_LANG === 'ar' ? 'رقم الطلب' : 'Order Number'; ?>:
                        <strong
                            class="text-primary font-heading"><?php echo Security::escape($order['order_number']); ?></strong>
                    </span>
                </div>
            </div>


            <div class="card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-black text-white py-3">
                    <h5 class="mb-0 fw-bold"><?php echo CURRENT_LANG === 'ar' ? 'تفاصيل الطلب' : 'Order Details'; ?>
                    </h5>
                </div>
                <div class="card-body p-4">

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label
                                class="text-muted d-block small text-uppercase fw-bold"><?php echo CURRENT_LANG === 'ar' ? 'تاريخ الطلب' : 'Order Date'; ?></label>
                            <span class="fw-bold"><?php echo Helpers::formatDateTime($order['created_at']); ?></span>
                        </div>
                        <div class="col-md-6">
                            <label
                                class="text-muted d-block small text-uppercase fw-bold"><?php echo CURRENT_LANG === 'ar' ? 'طريقة الدفع' : 'Payment Method'; ?></label>
                            <span class="fw-bold">
                                <?php
                                if ($order['payment_method'] === 'whatsapp') {
                                    echo CURRENT_LANG === 'ar' ? 'تأكيد عبر الواتساب' : 'WhatsApp Confirmation';
                                } else {
                                    echo CURRENT_LANG === 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery';
                                }
                                ?>
                            </span>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label
                                class="text-muted d-block small text-uppercase fw-bold"><?php echo CURRENT_LANG === 'ar' ? 'اسم العميل' : 'Customer Name'; ?></label>
                            <span class="fw-bold"><?php echo Security::escape($order['customer_name']); ?></span>
                        </div>

                        <div class="col-md-6">
                            <label
                                class="text-muted d-block small text-uppercase fw-bold"><?php echo CURRENT_LANG === 'ar' ? 'البريد الإلكتروني' : 'Email'; ?></label>
                            <span class="fw-bold"><?php echo Security::escape($order['customer_email']); ?></span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <label
                                class="text-muted d-block small text-uppercase fw-bold"><?php echo CURRENT_LANG === 'ar' ? 'عنوان الشحن' : 'Shipping Address'; ?></label>
                            <address class="fw-bold mb-0">
                                <?php
                                $address = json_decode($order['shipping_address'], true);
                                if (is_array($address)) {
                                    echo Security::escape($address['address_line1'] ?? '') . '<br>';
                                    echo Security::escape($address['city'] ?? '') . ', ' . Security::escape($address['country'] ?? '');
                                } else {
                                    echo nl2br(Security::escape($order['shipping_address']));
                                }
                                ?>
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-black text-white py-3">
                    <h5 class="mb-0 fw-bold"><?php echo CURRENT_LANG === 'ar' ? 'المنتجات' : 'Order Items'; ?></h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3"><?php echo Helpers::translate('product'); ?></th>
                                    <th class="text-center"><?php echo Helpers::translate('quantity'); ?></th>
                                    <th class="text-end px-4"><?php echo Helpers::translate('price'); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold"><?php echo Security::escape($item['product_name']); ?>
                                            </div>
                                            <small
                                                class="text-muted"><?php echo CURRENT_LANG === 'ar' ? 'جودة ممتازة' : 'Premium Quality'; ?></small>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $item['quantity']; ?>
                                        </td>
                                        <td class="text-end px-4">
                                            <?php echo Helpers::formatPrice($item['total']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="text-end px-4 py-2 text-muted">
                                        <?php echo CURRENT_LANG === 'ar' ? 'المجموع الفرعي' : 'Subtotal'; ?>:
                                    </td>
                                    <td class="text-end px-4 py-2 fw-bold">
                                        <?php echo Helpers::formatPrice($order['subtotal']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end px-4 py-2 text-muted">
                                        <?php echo CURRENT_LANG === 'ar' ? 'الشحن' : 'Shipping'; ?>:
                                    </td>
                                    <td class="text-end px-4 py-2 text-success fw-bold">
                                        <?php echo $order['shipping_cost'] == 0 ? (CURRENT_LANG === 'ar' ? 'مجاني' : 'FREE') : Helpers::formatPrice($order['shipping_cost']); ?>
                                    </td>
                                </tr>
                                <tr class="border-top border-secondary border-opacity-10">
                                    <td colspan="2" class="text-end px-4 py-3">
                                        <h5 class="fw-bold mb-0">
                                            <?php echo CURRENT_LANG === 'ar' ? 'الإجمالي' : 'Total'; ?>:
                                        </h5>
                                    </td>
                                    <td class="text-end px-4 py-3">
                                        <h5 class="fw-bold mb-0 text-primary">
                                            <?php echo Helpers::formatPrice($order['total']); ?>
                                        </h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 text-center">
                <div class="sh-benefit-icon mx-auto mb-3"
                    style="background: var(--sh-gradient-primary); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; font-size: 24px;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5 class="fw-bold mb-3"><?php echo CURRENT_LANG === 'ar' ? 'ما هي الخطوة التالية؟' : "What's Next?"; ?>
                </h5>
                <p class="text-muted mb-4">
                    <?php echo CURRENT_LANG === 'ar' ?
                        'ستتلقى تأكيداً عبر البريد الإلكتروني قريباً. سنقوم بإشعارك بمجرد شحن طلبك.' :
                        'You will receive an email confirmation shortly. We\'ll notify you when your order is shipped.'; ?>
                </p>
                <a href="<?php echo Helpers::url('track-order.php'); ?>"
                    class="btn btn-dark px-4 py-2 rounded-pill fw-bold" style="letter-spacing: 1px;">
                    <i class="bi bi-geo-alt me-2"></i>TRACK STATUS
                </a>
            </div>

            <div class="text-center d-flex flex-column flex-md-row justify-content-center gap-3">
                <a href="<?php echo Helpers::url(); ?>" class="btn btn-primary btn-lg px-5 rounded-0 fw-bold">
                    <i class="bi bi-house me-2"></i><?php echo Helpers::translate('back_to_home'); ?>
                </a>
                <?php if (Security::isLoggedIn()): ?>
                    <a href="<?php echo Helpers::url('account.php'); ?>"
                        class="btn btn-outline-dark btn-lg px-5 rounded-0 fw-bold">
                        <i class="bi bi-person me-2"></i><?php echo CURRENT_LANG === 'ar' ? 'طلباتي' : 'My Orders'; ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>