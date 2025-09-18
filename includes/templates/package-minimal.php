<?php
if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Package #<?php echo $order->get_id(); ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; color: #333; }
        header { background: #0073aa; color: #fff; padding: 10px; text-align: center; }
        h1 { margin: 0; font-size: 20px; }
        .customer-info { margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f7f7f7; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #555; }
        .print-btn { margin-bottom: 10px; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print();">Print Package Slip</button>

<header>
    <h1>Package #<?php echo $order->get_id(); ?></h1>
</header>

<div class="customer-info">
    <strong>Customer:</strong> <?php echo esc_html($order->get_formatted_billing_full_name()); ?><br>
    <strong>Shipping Address:</strong> <?php echo esc_html($order->get_formatted_shipping_address()); ?><br>
    <strong>Date:</strong> <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?>
</div>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
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
    <strong>Customer Notes:</strong>
    <p><?php echo esc_html($order->get_customer_note()); ?></p>
</div>
<?php endif; ?>

<div class="footer">
    <?php echo esc_html(get_option('aipd_footer_text', 'Thank you for your order!')); ?>
</div>

</body>
</html>
