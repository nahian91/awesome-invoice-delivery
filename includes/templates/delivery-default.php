<?php
if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php printf( esc_html__( 'Delivery Note #%s', 'awesome-invoice-delivery-notes-packing-slips' ), esc_html( $order->get_id() ) ); ?></title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    margin: 20px;
    background: #f7f7f7;
    font-size: 15px;
    line-height: 1.5;
}
.delivery-note-container {
    max-width: 950px;
    margin: auto;
    background: #fff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.delivery-note-print {
    background: #0073aa;
    color: #fff;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 25px;
    font-size: 16px;
}
.delivery-note-print:hover { background: #005f8a; }

/* Header */
.delivery-note-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
    border-bottom: 3px solid #0073aa;
    padding-bottom: 15px;
}
.delivery-note-header img { max-height: 70px; }
.delivery-note-header h1 { color: #0073aa; margin: 0; font-size: 30px; text-align: right; }

/* Sections */
.delivery-note-section { margin-bottom: 30px; }
.delivery-note-section strong { color: #0073aa; }
.delivery-note-billing-shipping { display: flex; justify-content: space-between; gap: 25px; flex-wrap: wrap; }
.delivery-note-billing, .delivery-note-shipping {
    width: 48%;
    background: #f9f9f9;
    padding: 18px;
    border-radius: 10px;
    box-sizing: border-box;
}
.delivery-note-billing h3, .delivery-note-shipping h3 { margin-top:0; margin-bottom:12px; color:#0073aa; font-size:17px; }

/* Table */
.delivery-note-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 15px;
}
.delivery-note-table th, .delivery-note-table td {
    border: 1px solid #ddd;
    padding: 12px 15px;
    text-align: left;
    vertical-align: middle;
}
.delivery-note-table th {
    background: #0073aa;
    color: #fff;
    font-weight: bold;
}
.delivery-note-table tbody tr:nth-child(even){ background: #f9f9f9; }
.delivery-note-table tbody tr:hover { background: #e6f7ff; }
.delivery-note-table tfoot td { font-weight: bold; font-size: 16px; background: #f0f8ff; }

/* Notes */
.delivery-note-notes {
    background: #f0f8ff;
    padding: 18px;
    border-left: 5px solid #0073aa;
    border-radius: 6px;
    margin-top: 25px;
    font-size: 15px;
    line-height: 1.6;
}

/* Footer */
.delivery-note-footer {
    text-align:center;
    margin-top: 45px;
    font-size:14px;
    color:#777;
}

/* Responsive */
@media print { 
    .delivery-note-print { display:none; } 
    body { background:#fff; } 
    .delivery-note-billing-shipping { flex-direction: column; } 
    .delivery-note-billing, .delivery-note-shipping { width: 100%; } 
}
</style>
</head>
<body>

<button class="delivery-note-print" onclick="window.print();"><?php esc_html_e('Print Delivery Note','awesome-invoice-delivery-notes-packing-slips'); ?></button>

<div class="delivery-note-container">

    <!-- Header -->
    <div class="delivery-note-header">
        <div class="company-info">
            <?php if(get_option('aipd_company_logo')): ?>
                <img src="<?php echo esc_url(get_option('aipd_company_logo')); ?>" alt="<?php echo esc_attr(get_option('aipd_company_name','Logo')); ?>">
            <?php endif; ?>
            <p><strong><?php echo esc_html(get_option('aipd_company_name','Company Name')); ?></strong></p>
            <p><?php echo wp_kses_post(get_option('aipd_company_address','Address')); ?></p>
            <p><strong><?php esc_html_e('Phone:','awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_phone','0123456789')); ?></p>
            <p><strong><?php esc_html_e('Email:','awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_email','email@example.com')); ?></p>
        </div>
        <h1><?php printf( esc_html__('Delivery Note #%s','awesome-invoice-delivery-notes-packing-slips'), esc_html($order->get_id()) ); ?></h1>
    </div>

    <!-- Order Info -->
    <div class="delivery-note-section">
        <p><strong><?php esc_html_e('Order Date:','awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(date_i18n(get_option('date_format','d/m/Y'), strtotime($order->get_date_created()))); ?></p>
        <p><strong><?php esc_html_e('Payment Method:','awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_payment_method_title()); ?></p>
        <p><strong><?php esc_html_e('Shipping Method:','awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_shipping_method()); ?></p>
    </div>

    <!-- Billing & Shipping -->
    <div class="delivery-note-section delivery-note-billing-shipping">
        <div class="delivery-note-billing">
            <h3><?php esc_html_e('Billing Address','awesome-invoice-delivery-notes-packing-slips'); ?></h3>
            <p><?php echo wp_kses_post($order->get_formatted_billing_address()); ?></p>
            <p><strong><?php esc_html_e('Email:','awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_billing_email()); ?></p>
            <p><strong><?php esc_html_e('Phone:','awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_billing_phone()); ?></p>
        </div>
        <div class="delivery-note-shipping">
            <h3><?php esc_html_e('Shipping Address','awesome-invoice-delivery-notes-packing-slips'); ?></h3>
            <p><?php echo wp_kses_post($order->get_formatted_shipping_address()); ?></p>
        </div>
    </div>

    <!-- Items Table -->
    <table class="delivery-note-table">
        <thead>
            <tr>
                <th><?php esc_html_e('Qty','awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <th><?php esc_html_e('Product','awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <?php if(get_option('aipd_show_sku',1)): ?><th><?php esc_html_e('SKU','awesome-invoice-delivery-notes-packing-slips'); ?></th><?php endif; ?>
                <th><?php esc_html_e('Price','awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <th><?php esc_html_e('Subtotal','awesome-invoice-delivery-notes-packing-slips'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($order->get_items() as $item):
                $product = $item->get_product();
                $sku = $product ? $product->get_sku() : '';
            ?>
            <tr>
                <td><?php echo esc_html($item->get_quantity()); ?></td>
                <td><?php echo esc_html($item->get_name()); ?></td>
                <?php if(get_option('aipd_show_sku',1)): ?><td><?php echo esc_html($sku); ?></td><?php endif; ?>
                <td><?php echo wp_kses_post(wc_price($order->get_item_total($item,true))); ?></td>
                <td><?php echo wp_kses_post(wc_price($item->get_total())); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?php echo get_option('aipd_show_sku',1)?4:3; ?>" style="text-align:right;"><?php esc_html_e('Total:','awesome-invoice-delivery-notes-packing-slips'); ?></td>
                <td><?php echo wp_kses_post(wc_price($order->get_total())); ?></td>
            </tr>
        </tfoot>
    </table>

    <!-- Customer Notes -->
    <?php if($order->get_customer_note()): ?>
    <div class="delivery-note-notes">
        <strong><?php esc_html_e('Customer Notes:','awesome-invoice-delivery-notes-packing-slips'); ?></strong>
        <p><?php echo wp_kses_post($order->get_customer_note()); ?></p>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="delivery-note-footer">
        <?php echo esc_html(get_option('aipd_footer_text','Thank you for your order!')); ?><br>
        <?php printf( esc_html__('Generated by %s.','awesome-invoice-delivery-notes-packing-slips'), esc_html(get_bloginfo('name')) ); ?>
    </div>

</div>
</body>
</html>
