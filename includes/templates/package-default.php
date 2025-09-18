<?php
if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php printf( esc_html__('Package #%s', 'awesome-invoice-delivery-notes-packing-slips'), esc_html($order->get_id()) ); ?></title>
<style>
body {
    font-family: 'Helvetica', sans-serif;
    background: #f7f7f7;
    margin: 0;
    padding: 20px;
    color: #333;
}
.package-wrapper {
    max-width: 950px;
    margin: auto;
    background: #fff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.1);
}
.print-btn {
    background: #0073aa;
    color: #fff;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 25px;
    font-size: 16px;
}
.print-btn:hover { background: #005f8a; }
.package-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
    background: #0073aa;
    color: #fff;
    padding: 15px 20px;
    border-radius: 8px;
}
.package-header img { max-height: 60px; }
.package-header h1 { margin: 0; font-size: 28px; }
.customer-info {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    font-size: 15px;
}
.customer-info strong { color: #0073aa; }
.package-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 15px;
}
.package-table th, .package-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}
.package-table th {
    background: #0073aa;
    color: #fff;
    font-weight: bold;
}
.package-table tbody tr:nth-child(even) { background: #f9f9f9; }
.package-table tbody tr:hover { background: #e6f7ff; }
.notes {
    background: #f0f8ff;
    padding: 18px;
    border-left: 5px solid #0073aa;
    border-radius: 6px;
    margin-top: 25px;
    white-space: pre-line;
    font-size: 15px;
}
.footer {
    text-align: center;
    margin-top: 45px;
    font-size: 13px;
    color: #777;
}
@media print {
    .print-btn { display: none; }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print();"><?php esc_html_e('Print Package Slip', 'awesome-invoice-delivery-notes-packing-slips'); ?></button>

<div class="package-wrapper">

    <div class="package-header">
        <div class="company-info">
            <?php if(get_option('aipd_company_logo')): ?>
                <img src="<?php echo esc_url(get_option('aipd_company_logo')); ?>" alt="<?php echo esc_attr(get_option('aipd_company_name','Logo')); ?>">
            <?php endif; ?>
            <p><strong><?php echo esc_html(get_option('aipd_company_name','Company Name')); ?></strong></p>
            <p><?php echo wp_kses_post(get_option('aipd_company_address','Address')); ?></p>
            <p><strong><?php esc_html_e('Phone:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_phone','0123456789')); ?></p>
            <p><strong><?php esc_html_e('Email:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_email','email@example.com')); ?></p>
        </div>
        <h1><?php printf( esc_html__('Package #%s', 'awesome-invoice-delivery-notes-packing-slips'), esc_html($order->get_id()) ); ?></h1>
    </div>

    <div class="customer-info">
        <p><strong><?php esc_html_e('Customer:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_formatted_billing_full_name()); ?></p>
        <p><strong><?php esc_html_e('Shipping Address:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo wp_kses_post($order->get_formatted_shipping_address()); ?></p>
        <p><strong><?php esc_html_e('Date:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></p>
    </div>

    <table class="package-table">
        <thead>
            <tr>
                <th><?php esc_html_e('Product', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <th><?php esc_html_e('SKU', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <th><?php esc_html_e('Qty', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
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
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if($order->get_customer_note()): ?>
    <div class="notes">
        <strong><?php esc_html_e('Special Instructions / Customer Notes:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong>
        <p><?php echo wp_kses_post($order->get_customer_note()); ?></p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <?php echo esc_html(get_option('aipd_footer_text','Thank you for your order!')); ?><br>
        <?php printf( esc_html__('Generated by %s.', 'awesome-invoice-delivery-notes-packing-slips'), esc_html(get_bloginfo('name')) ); ?>
    </div>

</div>

</body>
</html>
