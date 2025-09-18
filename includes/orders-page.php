<?php
if (!defined('ABSPATH')) exit;

function aipd_orders_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Awesome Documents - Orders', 'awesome-invoice-delivery-notes-packing-slips'); ?></h1>

        <table id="aipd-orders-table" class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Order ID', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('Customer', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('Total', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('Status', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                    <th><?php esc_html_e('Action', 'awesome-invoice-delivery-notes-packing-slips'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $orders = wc_get_orders(['limit'=>100,'orderby'=>'date','order'=>'DESC']);
                $templates = [
                    'invoice' => get_option('aipd_template_invoice','default'),
                    'packing_slip' => get_option('aipd_template_package','default'),
                    'delivery_note' => get_option('aipd_template_delivery','default'),
                ];

                foreach ($orders as $order) {
                    $order_id = $order->get_id();
                    ?>
                    <tr>
                        <td><?php echo esc_html($order_id); ?></td>
                        <td><?php echo esc_html($order->get_formatted_billing_full_name()); ?></td>
                        <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                        <td><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></td>
                        <td>
                            <?php
                            $nonce = wp_create_nonce('aipd_generate_document_'.$order_id);

                            // Actions with templates
                            foreach ([
                                'invoice' => __('Invoice','awesome-invoice-delivery-notes-packing-slips'),
                                'packing_slip' => __('Packing Slip','awesome-invoice-delivery-notes-packing-slips'),
                                'delivery_note' => __('Delivery Note','awesome-invoice-delivery-notes-packing-slips')
                            ] as $action => $label) {

                                // Get template for this action
                                $tpl = $templates[$action] ?? 'default';

                                printf(
                                    '<a href="%s" target="_blank" class="button button-primary button-small" style="margin-right:5px;">%s</a>',
                                    esc_url(admin_url('admin-post.php?action=aipd_generate_'.$action.'&order_id='.$order_id.'&template='.$tpl.'&_wpnonce='.$nonce)),
                                    esc_html($label)
                                );
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- DataTables scripts -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script>
    jQuery(document).ready(function($){
        $('#aipd-orders-table').DataTable({
            dom: 'Bfrtip',
            pageLength: 25,
            order: [[0,'desc']],
            buttons: ['copy', 'csv', 'excel', 'print']
        });
    });
    </script>

    <style>
        #aipd-orders-table .button { font-size: 12px; }
        .dt-buttons { margin-bottom: 10px; }
    </style>
    <?php
}
