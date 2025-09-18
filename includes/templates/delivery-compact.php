<?php
if (!defined('ABSPATH')) exit;

// Fetch style options
$font_family    = get_option('aipd_style_font_family', 'Arial, sans-serif');
$font_size      = get_option('aipd_style_font_size', '14px');
$text_color     = get_option('aipd_style_text_color', '#333333');
$bg_color       = get_option('aipd_style_background', '#ffffff');
$header_color   = get_option('aipd_style_header_color', '#0073aa');
$footer_color   = get_option('aipd_style_footer_color', '#333333');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php printf(esc_html__('Delivery Note #%s', 'awesome-invoice-delivery-notes-packing-slips'), esc_html($order->get_id())); ?></title>
<style>
body {
    font-family: <?php echo esc_attr($font_family); ?>;
    font-size: <?php echo esc_attr($font_size); ?>;
    color: <?php echo esc_attr($text_color); ?>;
    background: <?php echo esc_attr($bg_color); ?>;
    margin: 0; padding: 0;
}
.container {
    width: 90%; max-width: 1000px; margin: 20px auto;
    padding: 30px; background: #fff;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
    border-radius: 10px;
}
header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px; background: <?php echo esc_attr($header_color); ?>10;
    border-radius: 10px 10px 0 0; margin-bottom: 30px;
}
header img { max-height: 80px; margin-right: 15px; }
header .company-details { line-height: 1.4; }
header h1 {
    font-size: 28px; color: <?php echo esc_attr($header_color); ?>;
    margin: 0; text-align: right;
}
.section { margin-bottom: 25px; }
.section p { margin: 5px 0; font-size: 16px; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 16px; }
th, td { border: 1px solid #ccc; padding: 12px 10px; text-align: left; }
th { background: <?php echo esc_attr($header_color); ?>20; color: <?php echo esc_attr($header_color); ?>; }
tbody tr:nth-child(even) { background: #f9f9f9; }
tfoot td { font-weight: bold; font-size: 16px; }
.notes {
    margin-top: 20px; padding: 15px; background: #f4f4f4;
    border-left: 4px solid <?php echo esc_attr($header_color); ?>;
    border-radius: 4px;
}
.footer {
    text-align: center; margin-top: 50px;
    font-size: 14px; color: <?php echo esc_attr($footer_color); ?>;
    padding: 15px; background: <?php echo esc_attr($footer_color); ?>10;
    border-radius: 0 0 10px 10px;
}
.print-button {
    margin: 20px auto; display: block;
    background: <?php echo esc_attr($header_color); ?>;
    color: #fff; padding: 10px 25px; font-size: 16px;
    border: none; border-radius: 6px; cursor: pointer;
}
.print-button:hover { opacity: 0.9; }
@media print {
    .print-button { display: none; }
    body { margin: 0; padding: 0; }
    .container { box-shadow: none; margin: 0; border-radius: 0; }
}
</style>
</head>
<body>

<button class="print-button" onclick="window.print();"><?php esc_html_e('Print Delivery Note', 'awesome-invoice-delivery-notes-packing-slips'); ?></button>

<div class="container">
    <header>
        <div class="company-info">
            <?php if(get_option('aipd_company_logo')): ?>
                <img src="<?php echo esc_url(get_option('aipd_company_logo')); ?>" alt="<?php echo esc_attr(get_option('aipd_company_name','Logo')); ?>">
            <?php endif; ?>
        </div>
        <div class="company-details">
            <p><strong><?php echo esc_html(get_option('aipd_company_name', 'Company Name')); ?></strong></p>
            <p><?php echo wp_kses_post(get_option('aipd_company_address', 'Address')); ?></p>
            <p><strong><?php esc_html_e('Phone:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_phone', '0123456789')); ?></p>
            <p><strong><?php esc_html_e('Email:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(get_option('aipd_company_email', 'email@example.com')); ?></p>
        </div>
        <h1><?php esc_html_e('Delivery Note', 'awesome-invoice-delivery-notes-packing-slips'); ?></h1>
    </header>

    <div class="section order-info">
        <p><strong><?php esc_html_e('Delivery Note #:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_id()); ?></p>
        <p><strong><?php esc_html_e('Order Date:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></p>
        <p><strong><?php esc_html_e('Payment Method:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_payment_method_title()); ?></p>
        <p><strong><?php esc_html_e('Shipping Method:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_shipping_method()); ?></p>
    </div>

    <div class="section customer-info">
        <p><strong><?php esc_html_e('Customer:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_formatted_billing_full_name()); ?></p>
        <p><strong><?php esc_html_e('Address:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo wp_kses_post($order->get_formatted_billing_address()); ?></p>
        <p><strong><?php esc_html_e('Email:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_billing_email()); ?></p>
        <p><strong><?php esc_html_e('Phone:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong> <?php echo esc_html($order->get_billing_phone()); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th><?php esc_html_e('Qty', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <th><?php esc_html_e('Product', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <?php if(get_option('aipd_show_sku',1)): ?><th><?php esc_html_e('SKU', 'awesome-invoice-delivery-notes-packing-slips'); ?></th><?php endif; ?>
                <th><?php esc_html_e('Price', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                <th><?php esc_html_e('Subtotal', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
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
                <td colspan="<?php echo get_option('aipd_show_sku',1) ? 4 : 3; ?>" style="text-align:right;"><?php esc_html_e('Total:', 'awesome-invoice-delivery-notes-packing-slips'); ?></td>
                <td><?php echo wp_kses_post(wc_price($order->get_total())); ?></td>
            </tr>
        </tfoot>
    </table>

    <?php if($order->get_customer_note()): ?>
    <div class="notes">
        <strong><?php esc_html_e('Customer Notes:', 'awesome-invoice-delivery-notes-packing-slips'); ?></strong>
        <p><?php echo wp_kses_post($order->get_customer_note()); ?></p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <?php echo esc_html(get_option('aipd_footer_text','Thank you for your order!')); ?><br>
        <?php printf(esc_html__('Generated by %s.', 'awesome-invoice-delivery-notes-packing-slips'), esc_html(get_bloginfo('name'))); ?>
    </div>
</div>

</body>
</html>
