<?php
if (!defined('ABSPATH')) exit;

/** @var WC_Order $order */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice #<?php echo esc_html($order->get_id()); ?></title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f7f7f7; margin: 0; padding: 20px; color: #333; font-size: 15px; line-height: 1.6; }
.invoice-wrapper { max-width: 950px; margin: auto; background: #fff; padding: 35px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
.print-btn { background: #0073aa; color: #fff; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; margin-bottom: 25px; font-size: 16px; }
.print-btn:hover { background: #005f8a; }

.invoice-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; border-bottom: 3px solid #0073aa; padding-bottom: 15px; }
.invoice-header img { max-height: 70px; }
.invoice-header h1 { color: #0073aa; margin: 0; font-size: 30px; text-align: right; }

.addresses { display: flex; justify-content: space-between; gap: 25px; flex-wrap: wrap; margin-bottom: 30px; }
.address-box { width: 48%; background: #f9f9f9; padding: 18px; border-radius: 10px; box-sizing: border-box; }
.address-box h3 { margin-top: 0; margin-bottom: 12px; color: #0073aa; font-size: 17px; }

.invoice-info { margin-bottom: 30px; }
.invoice-info p { margin: 5px 0; }

.table-wrapper { overflow-x: auto; }
.invoice-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 15px; }
.invoice-table th, .invoice-table td { border: 1px solid #ddd; padding: 12px 15px; text-align: left; vertical-align: middle; }
.invoice-table th { background: #0073aa; color: #fff; font-weight: bold; }
.invoice-table tbody tr:nth-child(even){ background: #f9f9f9; }
.invoice-table tbody tr:hover { background: #e6f7ff; }

.total-row td { font-weight: bold; text-align: right; background: #f0f8ff; }

.invoice-notes { background: #f0f8ff; padding: 18px; border-left: 5px solid #0073aa; border-radius: 6px; margin-top: 25px; font-size: 15px; white-space: pre-line; }

.invoice-footer { text-align:center; margin-top: 45px; font-size:14px; color:#777; }

@media print { 
    .print-btn { display:none; } 
    body { background:#fff; } 
    .addresses { flex-direction: column; } 
    .address-box { width: 100%; } 
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print();"><?php esc_html_e('Print Invoice', 'awesome-invoice-delivery-notes-packing-slips'); ?></button>

<div class="invoice-wrapper">

    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <?php if(get_option('aipd_company_logo')): ?>
                <img src="<?php echo esc_url(get_option('aipd_company_logo')); ?>" alt="<?php echo esc_attr(get_option('aipd_company_name','Logo')); ?>">
            <?php endif; ?>
            <p><strong><?php echo esc_html(get_option('aipd_company_name','Company Name')); ?></strong></p>
            <p><?php echo wp_kses_post(get_option('aipd_company_address','Address')); ?></p>
            <p><strong><?php esc_html_e('Phone:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_phone','0123456789')); ?></p>
            <p><strong><?php esc_html_e('Email:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_email','email@example.com')); ?></p>
        </div>
        <h1><?php printf(esc_html__('Invoice #%s', 'awesome-invoice-delivery-notes-packing-slips'), esc_html($order->get_id())); ?></h1>
    </div>

    <!-- Addresses -->
    <div class="addresses">
        <div class="address-box">
            <h3><?php esc_html_e('Billing Address', 'awesome-invoice-delivery-notes-packing-slips'); ?></h3>
            <p><?php echo wp_kses_post($order->get_formatted_billing_address()); ?></p>
            <p><strong><?php esc_html_e('Email:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_billing_email()); ?></p>
            <p><strong><?php esc_html_e('Phone:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_billing_phone()); ?></p>
        </div>
        <div class="address-box">
            <h3><?php esc_html_e('Shipping Address', 'awesome-invoice-delivery-notes-packing-slips'); ?></h3>
            <p><?php echo wp_kses_post($order->get_formatted_shipping_address()); ?></p>
        </div>
    </div>

    <!-- Invoice Info -->
    <div class="invoice-info">
        <p><strong><?php esc_html_e('Invoice Date:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></p>
        <p><strong><?php esc_html_e('Payment Method:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_payment_method_title()); ?></p>
        <p><strong><?php esc_html_e('Shipping Method:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_shipping_method()); ?></p>
    </div>

    <!-- Items Table -->
    <div class="table-wrapper">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Product', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('SKU', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('Qty', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('Unit Price', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('Subtotal', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($order->get_items() as $item):
                    $product = $item->get_product();
                    $sku = $product ? $product->get_sku() : '';
                ?>
                <tr>
                    <td><?php echo esc_html($item->get_name()); ?></td>
                    <td><?php echo esc_html($sku); ?></td>
                    <td><?php echo esc_html($item->get_quantity()); ?></td>
                    <td><?php echo wp_kses_post(wc_price($order->get_item_total($item,true))); ?></td>
                    <td><?php echo wp_kses_post(wc_price($item->get_total())); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4"><?php esc_html_e('Subtotal:', 'awesome-invoice-delivery-notes-packing-slips'); ?></td>
                    <td><?php echo wp_kses_post(wc_price($order->get_subtotal())); ?></td>
                </tr>
                <?php if($order->get_total_tax() > 0): ?>
                <tr class="total-row">
                    <td colspan="4"><?php esc_html_e('Tax:', 'awesome-invoice-delivery-notes-packing-slips'); ?></td>
                    <td><?php echo wp_kses_post(wc_price($order->get_total_tax())); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($order->get_discount_total() > 0): ?>
                <tr class="total-row">
                    <td colspan="4"><?php esc_html_e('Discount:', 'awesome-invoice-delivery-notes-packing-slips'); ?></td>
                    <td>- <?php echo wp_kses_post(wc_price($order->get_discount_total())); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="4"><?php esc_html_e('Total:', 'awesome-invoice-delivery-notes-packing-slips'); ?></td>
                    <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Customer Notes -->
    <?php if($order->get_customer_note()): ?>
    <div class="invoice-notes">
        <strong><?php esc_html_e('Customer Notes:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong>
        <p><?php echo wp_kses_post($order->get_customer_note()); ?></p>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="invoice-footer">
        <?php echo esc_html(get_option('aipd_footer_text','Thank you for your order!')); ?><br>
        <?php printf(esc_html__('Generated by %s.', 'awesome-invoice-delivery-notes-packing-slips'), esc_html(get_bloginfo('name'))); ?>
    </div>
</div>

</body>
</html>
