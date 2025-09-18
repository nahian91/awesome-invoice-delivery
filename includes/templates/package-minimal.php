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
    font-family: Arial, sans-serif;
    font-size: 14px;
    margin: 20px;
    color: #333;
    background: #f7f7f7;
}
.package-wrapper {
    max-width: 700px;
    margin: auto;
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.print-btn {
    margin-bottom: 15px;
    padding: 8px 20px;
    background: #0073aa;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.print-btn:hover { background: #005f8a; }
header {
    background: #0073aa;
    color: #fff;
    padding: 12px 0;
    text-align: center;
    border-radius: 5px;
    margin-bottom: 20px;
}
header h1 { margin: 0; font-size: 22px; }
.customer-info {
    margin-bottom: 20px;
    line-height: 1.6;
}
.customer-info strong { color: #0073aa; }
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
th { background: #f7f7f7; font-weight: bold; }
tbody tr:nth-child(even) { background: #f9f9f9; }
tbody tr:hover { background: #e6f7ff; }
.customer-notes {
    background: #f0f8ff;
    padding: 12px;
    border-left: 4px solid #0073aa;
    border-radius: 4px;
    margin-bottom: 20px;
    white-space: pre-line;
}
.footer {
    text-align: center;
    font-size: 12px;
    color: #555;
}
@media print {
    .print-btn { display: none; }
}
</style>
</head>
<body>

<div class="package-wrapper">

<button class="print-btn" onclick="window.print();"><?php esc_html_e('Print Package Slip', 'awesome-invoice-delivery-notes-packing-slips'); ?></button>

<header>
    <h1><?php printf( esc_html__('Package #%s', 'awesome-invoice-delivery-notes-packing-slips'), esc_html($order->get_id()) ); ?></h1>
</header>

<div class="customer-info">
    <p><strong><?php esc_html_e('Customer:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_formatted_billing_full_name()); ?></p>
    <p><strong><?php esc_html_e('Shipping Address:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo wp_kses_post($order->get_formatted_shipping_address()); ?></p>
    <p><strong><?php esc_html_e('Date:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></p>
</div>

<table>
    <thead>
        <tr>
            <th><?php esc_html_e('Product', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
            <th><?php esc_html_e('Qty', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($order->get_items() as $item): ?>
            <tr>
                <td><?php echo esc_html($item->get_name()); ?></td>
                <td><?php echo esc_html($item->get_quantity()); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if($order->get_customer_note()): ?>
<div class="customer-notes">
    <strong><?php esc_html_e('Customer Notes:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong>
    <p><?php echo wp_kses_post($order->get_customer_note()); ?></p>
</div>
<?php endif; ?>

<div class="footer">
    <?php echo esc_html(get_option('aipd_footer_text', 'Thank you for your order!')); ?>
</div>

</div>
</body>
</html>
