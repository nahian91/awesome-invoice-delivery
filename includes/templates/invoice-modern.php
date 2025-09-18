<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** @var WC_Order $order */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice #<?php echo esc_html( $order->get_id() ); ?></title>
<style>
body {
    font-family: 'Helvetica', sans-serif;
    background: #f7f7f7;
    margin: 0;
    padding: 20px;
    color: #333;
}
.invoice-wrapper {
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
.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
}
.invoice-header img { max-height: 70px; }
.invoice-header h1 { color: #0073aa; font-size: 32px; margin:0; }
.addresses {
    display:flex;
    justify-content:space-between;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}
.address-box {
    width:48%;
    background:#f9f9f9;
    padding:20px;
    border-radius:10px;
    box-sizing:border-box;
}
.address-box h3 { margin-top:0; margin-bottom:12px; color:#0073aa; font-size:17px; }
.invoice-info p { margin:6px 0; font-size:15px; }
.table-wrapper { overflow-x:auto; }
.invoice-table {
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
    font-size:15px;
}
.invoice-table th, .invoice-table td {
    border:1px solid #ddd;
    padding:12px;
    text-align:left;
    vertical-align:middle;
}
.invoice-table th {
    background:#0073aa;
    color:#fff;
    font-weight:bold;
}
.invoice-table tbody tr:nth-child(even){ background:#f9f9f9; }
.invoice-table tbody tr:hover { background:#e6f7ff; }
.total-row td {
    font-weight:bold;
    text-align:right;
    background:#f0f8ff;
}
.invoice-notes {
    background:#f0f8ff;
    padding:18px;
    border-left:5px solid #0073aa;
    border-radius:6px;
    margin-top:25px;
    white-space: pre-line;
    font-size:15px;
}
.invoice-footer {
    text-align:center;
    margin-top:45px;
    font-size:13px;
    color:#777;
}
@media print {
    .print-btn { display:none; }
    body { background:#fff; }
    .addresses { flex-direction: column; }
    .address-box { width:100%; margin-bottom:15px; }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print();">Print Invoice</button>

<div class="invoice-wrapper">

    <div class="invoice-header">
        <div class="company-info">
            <?php if( get_option('aipd_company_logo') ): ?>
                <img src="<?php echo esc_url( get_option('aipd_company_logo') ); ?>" alt="Logo">
            <?php endif; ?>
            <p><strong><?php echo esc_html( get_option('aipd_company_name','Company Name') ); ?></strong></p>
            <p><?php echo esc_html( get_option('aipd_company_address','Address') ); ?></p>
            <p>Phone: <?php echo esc_html( get_option('aipd_company_phone','0123456789') ); ?></p>
            <p>Email: <?php echo esc_html( get_option('aipd_company_email','email@example.com') ); ?></p>
        </div>
        <h1>Invoice #<?php echo esc_html( $order->get_id() ); ?></h1>
    </div>

    <div class="addresses">
        <div class="address-box">
            <h3>Billing Address</h3>
            <p><?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?></p>
            <p><strong>Email:</strong> <?php echo esc_html( $order->get_billing_email() ); ?></p>
            <p><strong>Phone:</strong> <?php echo esc_html( $order->get_billing_phone() ); ?></p>
        </div>
        <div class="address-box">
            <h3>Shipping Address</h3>
            <p><?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?></p>
        </div>
    </div>

    <div class="invoice-info">
        <p><strong>Invoice Date:</strong> <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
        <p><strong>Payment Method:</strong> <?php echo esc_html( $order->get_payment_method_title() ); ?></p>
        <p><strong>Shipping Method:</strong> <?php echo esc_html( $order->get_shipping_method() ); ?></p>
    </div>

    <div class="table-wrapper">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $order->get_items() as $item ):
                    $product = $item->get_product();
                    $sku = $product ? $product->get_sku() : '';
                ?>
                <tr>
                    <td><?php echo esc_html( $item->get_name() ); ?></td>
                    <td><?php echo esc_html( $sku ); ?></td>
                    <td><?php echo esc_html( $item->get_quantity() ); ?></td>
                    <td><?php echo wp_kses_post( wc_price( $order->get_item_total( $item, true ) ) ); ?></td>
                    <td><?php echo wp_kses_post( wc_price( $item->get_total() ) ); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4">Subtotal:</td>
                    <td><?php echo wp_kses_post( wc_price( $order->get_subtotal() ) ); ?></td>
                </tr>
                <?php if( $order->get_total_tax() > 0 ): ?>
                <tr class="total-row">
                    <td colspan="4">Tax:</td>
                    <td><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></td>
                </tr>
                <?php endif; ?>
                <?php if( $order->get_discount_total() > 0 ): ?>
                <tr class="total-row">
                    <td colspan="4">Discount:</td>
                    <td><?php echo wp_kses_post( wc_price( $order->get_discount_total() ) ); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="4">Total:</td>
                    <td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php if( $order->get_customer_note() ): ?>
    <div class="invoice-notes">
        <strong>Customer Notes:</strong>
        <p><?php echo esc_html( $order->get_customer_note() ); ?></p>
    </div>
    <?php endif; ?>

    <div class="invoice-footer">
        <?php echo esc_html( get_option('aipd_footer_text','Thank you for your order!') ); ?><br>
        Generated by <?php bloginfo('name'); ?>.
    </div>
</div>

</body>
</html>
