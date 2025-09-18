<?php
if (!defined('ABSPATH')) exit;

function aipd_reports_page() {

    // Fetch WooCommerce orders (limit 50 for example)
    $orders = wc_get_orders([
        'limit' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    ?>
    <div class="wrap">
        <h1>Plugin Reports</h1>

        <div class="aipd-tabs">
            <div class="aipd-tab-buttons">
                <button class="active" data-tab="invoices">Invoices</button>
                <button data-tab="packages">Packages</button>
                <button data-tab="delivery">Delivery Notes</button>
            </div>

            <!-- Invoices Tab -->
            <div id="invoices" class="aipd-tab-content active">
                <h2>Invoices</h2>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo esc_html(get_post_meta($order->get_id(), '_aipd_invoice_number', true) ?: '-'); ?></td>
                                <td><?php echo $order->get_id(); ?></td>
                                <td><?php echo esc_html($order->get_formatted_billing_full_name()); ?></td>
                                <td><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></td>
                                <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Packages Tab -->
            <div id="packages" class="aipd-tab-content">
                <h2>Packages</h2>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Package #</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo esc_html(get_post_meta($order->get_id(), '_aipd_package_number', true) ?: '-'); ?></td>
                                <td><?php echo $order->get_id(); ?></td>
                                <td><?php echo esc_html($order->get_formatted_billing_full_name()); ?></td>
                                <td><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Delivery Notes Tab -->
            <div id="delivery" class="aipd-tab-content">
                <h2>Delivery Notes</h2>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Delivery Note #</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo esc_html(get_post_meta($order->get_id(), '_aipd_delivery_number', true) ?: '-'); ?></td>
                                <td><?php echo $order->get_id(); ?></td>
                                <td><?php echo esc_html($order->get_formatted_billing_full_name()); ?></td>
                                <td><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        const tabButtons = document.querySelectorAll('.aipd-tab-buttons button');
        const tabContents = document.querySelectorAll('.aipd-tab-content');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(btn.dataset.tab).classList.add('active');
            });
        });
    </script>

    <style>
        .aipd-tabs { margin-top:20px; }
        .aipd-tab-buttons button { background:#f1f1f1; border:1px solid #ccc; padding:10px 20px; cursor:pointer; margin-right:2px; border-radius:4px 4px 0 0; }
        .aipd-tab-buttons button.active { background:#0073aa; color:#fff; }
        .aipd-tab-content { border:1px solid #ccc; padding:20px; display:none; border-radius:0 4px 4px 4px; background:#fff; }
        .aipd-tab-content.active { display:block; }
    </style>
    <?php
}
