<?php
/**
 * Plugin Name: Awesome Invoice, Delivery Notes & Packing Slips
 * Plugin URI:  https://devnahian.com/awesome-invoice-delivery
 * Description: Generate professional Invoices, Packing Slips, and Delivery Notes from WooCommerce orders.
 * Version:     1.0
 * Author:      Abdullah Nahian
 * Author URI:  https://devnahian.com
 * License:     GPL2
 * Text Domain: awesome-invoice-delivery
 */

if (!defined('ABSPATH')) exit;

// ---------------- WooCommerce Check ----------------
add_action('plugins_loaded', function() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>' . esc_html__('Awesome Invoices:', 'awesome-invoice-delivery') . '</strong> ' . esc_html__('WooCommerce plugin not found. Please install & activate WooCommerce.', 'awesome-invoice-delivery') . '</p></div>';
        });
        return;
    }
    add_action('admin_menu', 'aipd_register_admin_menu');
});

// ---------------- Admin Menu ----------------
function aipd_register_admin_menu() {
    add_menu_page(
        esc_html__('Awesome Invoices', 'awesome-invoice-delivery'),
        esc_html__('Invoices', 'awesome-invoice-delivery'),
        'manage_options',
        'awesome-invoice-delivery',
        'aipd_main_page',
        'dashicons-media-spreadsheet',
        56
    );

    add_submenu_page(
        'awesome-invoice-delivery',
        esc_html__('Settings', 'awesome-invoice-delivery'),
        esc_html__('Settings', 'awesome-invoice-delivery'),
        'manage_options',
        'awesome-invoice-delivery-settings',
        'aipd_settings_page'
    );
}

// ---------------- Settings Page ----------------
function aipd_settings_page() {
    if (isset($_POST['aipd_save_settings'])) {
        check_admin_referer('aipd_save_settings_verify');

        update_option('aipd_company_name', isset($_POST['aipd_company_name']) ? sanitize_text_field(wp_unslash($_POST['aipd_company_name'])) : '');
        update_option('aipd_company_address', isset($_POST['aipd_company_address']) ? sanitize_textarea_field(wp_unslash($_POST['aipd_company_address'])) : '');
        update_option('aipd_company_email', isset($_POST['aipd_company_email']) ? sanitize_email(wp_unslash($_POST['aipd_company_email'])) : '');
        update_option('aipd_company_phone', isset($_POST['aipd_company_phone']) ? sanitize_text_field(wp_unslash($_POST['aipd_company_phone'])) : '');
        update_option('aipd_company_logo', isset($_POST['aipd_company_logo']) ? esc_url_raw(wp_unslash($_POST['aipd_company_logo'])) : '');

        echo '<div class="notice notice-success"><p>' . esc_html__('Settings saved.', 'awesome-invoice-delivery') . '</p></div>';
    }

    $company_name = get_option('aipd_company_name', 'My Company Ltd.');
    $company_address = get_option('aipd_company_address', '123 Street, City, Country');
    $company_email = get_option('aipd_company_email', 'info@mycompany.com');
    $company_phone = get_option('aipd_company_phone', '+880123456789');
    $company_logo = get_option('aipd_company_logo', 'https://via.placeholder.com/150x50?text=Logo');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Awesome Invoices Settings', 'awesome-invoice-delivery'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('aipd_save_settings_verify'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="aipd_company_name"><?php esc_html_e('Company Name', 'awesome-invoice-delivery'); ?></label></th>
                    <td><input type="text" id="aipd_company_name" name="aipd_company_name" value="<?php echo esc_attr($company_name); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="aipd_company_address"><?php esc_html_e('Company Address', 'awesome-invoice-delivery'); ?></label></th>
                    <td><textarea id="aipd_company_address" name="aipd_company_address" rows="3" class="large-text"><?php echo esc_textarea($company_address); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="aipd_company_email"><?php esc_html_e('Company Email', 'awesome-invoice-delivery'); ?></label></th>
                    <td><input type="email" id="aipd_company_email" name="aipd_company_email" value="<?php echo esc_attr($company_email); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="aipd_company_phone"><?php esc_html_e('Company Phone', 'awesome-invoice-delivery'); ?></label></th>
                    <td><input type="text" id="aipd_company_phone" name="aipd_company_phone" value="<?php echo esc_attr($company_phone); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="aipd_company_logo"><?php esc_html_e('Company Logo URL', 'awesome-invoice-delivery'); ?></label></th>
                    <td><input type="text" id="aipd_company_logo" name="aipd_company_logo" value="<?php echo esc_attr($company_logo); ?>" class="regular-text"></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="aipd_save_settings" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'awesome-invoice-delivery'); ?>"></p>
        </form>
    </div>
    <?php
}

// ---------------- Main Admin Page ----------------
function aipd_main_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Awesome Documents', 'awesome-invoice-delivery'); ?></h1>
        <?php aipd_orders_table(); ?>
    </div>
    <?php
}

// ---------------- Helper ----------------
function aipd_get_orders($limit = 10) {
    return wc_get_orders(['limit'=>$limit,'orderby'=>'date','order'=>'DESC']);
}

// ---------------- Orders Table ----------------
function aipd_orders_table() {
    $orders = aipd_get_orders();
    ?>
    <style>
        .aipd-action-buttons .button { margin-right:5px; margin-bottom:5px; min-width:100px; }
    </style>
    <table class="widefat striped">
        <thead>
            <tr>
                <th><?php esc_html_e('Order ID', 'awesome-invoice-delivery'); ?></th>
                <th><?php esc_html_e('Customer', 'awesome-invoice-delivery'); ?></th>
                <th><?php esc_html_e('Total', 'awesome-invoice-delivery'); ?></th>
                <th><?php esc_html_e('Status', 'awesome-invoice-delivery'); ?></th>
                <th><?php esc_html_e('Action', 'awesome-invoice-delivery'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order): ?>
            <tr>
                <td><?php echo esc_html($order->get_id()); ?></td>
                <td><?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></td>
                <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                <td><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></td>
                <td class="aipd-action-buttons">
                    <?php
                    $order_id = $order->get_id();
                    $invoice_nonce = wp_create_nonce('aipd_generate_document_'.$order_id);
                    $packing_nonce = wp_create_nonce('aipd_generate_document_'.$order_id);
                    $delivery_nonce = wp_create_nonce('aipd_generate_document_'.$order_id);
                    ?>
                    <a href="<?php echo esc_url(admin_url('admin-post.php?action=aipd_generate_invoice&order_id='.$order_id.'&_wpnonce='.$invoice_nonce)); ?>" target="_blank" class="button button-primary">
                        <span class="dashicons dashicons-media-document"></span> <?php esc_html_e('Invoice', 'awesome-invoice-delivery'); ?>
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin-post.php?action=aipd_generate_packing_slip&order_id='.$order_id.'&_wpnonce='.$packing_nonce)); ?>" target="_blank" class="button" style="background:#f39c12;border-color:#f39c12;color:#fff;">
                        <span class="dashicons dashicons-archive"></span> <?php esc_html_e('Packing', 'awesome-invoice-delivery'); ?>
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin-post.php?action=aipd_generate_delivery_note&order_id='.$order_id.'&_wpnonce='.$delivery_nonce)); ?>" target="_blank" class="button" style="background:#27ae60;border-color:#27ae60;color:#fff;">
                        <span class="dashicons dashicons-location"></span> <?php esc_html_e('Delivery', 'awesome-invoice-delivery'); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}

// ---------------- Generate HTML Documents ----------------
function aipd_generate_document_html($order_id, $type) {
    if (!current_user_can('manage_options') || !$order_id) wp_die(esc_html__('Access denied', 'awesome-invoice-delivery'));

    $order = wc_get_order($order_id);
    if (!$order) wp_die(esc_html__('Invalid Order', 'awesome-invoice-delivery'));

    $company_name = get_option('aipd_company_name', 'My Company Ltd.');
    $company_address = get_option('aipd_company_address', '123 Street, City, Country');
    $company_email = get_option('aipd_company_email', 'info@mycompany.com');
    $company_phone = get_option('aipd_company_phone', '+880123456789');
    $logo_url = get_option('aipd_company_logo', 'https://via.placeholder.com/150x50?text=Logo');

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?php echo esc_html($type) . ' #' . esc_html($order->get_id()); ?></title>
        <style>
            body { font-family: Arial, sans-serif; padding: 30px; color:#333; }
            h1 { color:#0073aa; margin-bottom: 10px; }
            .company { text-align:right; }
            .company img { max-width:150px; }
            table { width:100%; border-collapse: collapse; margin-top:20px; }
            th, td { border:1px solid #ddd; padding:8px; text-align:left; }
            th { background:#f4f4f4; }
            .totals td { font-weight:bold; }
            .section { margin-top:30px; }
        </style>
    </head>
    <body>
        <div class="company">
            <img src="<?php echo esc_url($logo_url); ?>" alt="Logo"><br>
            <strong><?php echo esc_html($company_name); ?></strong><br>
            <?php echo esc_html($company_address); ?><br>
            <?php echo esc_html($company_phone); ?> | <?php echo esc_html($company_email); ?>
        </div>
        <h1><?php echo esc_html($type) . ' #' . esc_html($order->get_id()); ?></h1>
        <div class="section">
            <strong><?php esc_html_e('Customer:', 'awesome-invoice-delivery'); ?></strong> <?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?><br>
            <strong><?php esc_html_e('Email:', 'awesome-invoice-delivery'); ?></strong> <?php echo esc_html($order->get_billing_email()); ?><br>
            <strong><?php esc_html_e('Phone:', 'awesome-invoice-delivery'); ?></strong> <?php echo esc_html($order->get_billing_phone()); ?>
        </div>
        <?php if($type !== 'Delivery Note'): ?>
        <div class="section">
            <strong><?php esc_html_e('Billing Address:', 'awesome-invoice-delivery'); ?></strong><br>
            <?php echo esc_html($order->get_billing_address_1() . ', ' . $order->get_billing_city() . ', ' . $order->get_billing_state() . ' ' . $order->get_billing_postcode() . ', ' . $order->get_billing_country()); ?>
        </div>
        <?php endif; ?>
        <?php if($type !== 'Invoice'): ?>
        <div class="section">
            <strong><?php esc_html_e('Shipping Address:', 'awesome-invoice-delivery'); ?></strong><br>
            <?php echo esc_html($order->get_shipping_address_1() . ', ' . $order->get_shipping_city() . ', ' . $order->get_shipping_state() . ' ' . $order->get_shipping_postcode() . ', ' . $order->get_shipping_country()); ?>
        </div>
        <?php endif; ?>
        <div class="section">
            <table>
                <tr>
                    <th><?php esc_html_e('Product', 'awesome-invoice-delivery'); ?></th>
                    <th><?php esc_html_e('Quantity', 'awesome-invoice-delivery'); ?></th>
                    <?php if($type==='Invoice'): ?><th><?php esc_html_e('Total', 'awesome-invoice-delivery'); ?></th><?php endif; ?>
                </tr>
                <?php foreach($order->get_items() as $item): ?>
                <tr>
                    <td><?php echo esc_html($item->get_name()); ?></td>
                    <td><?php echo esc_html($item->get_quantity()); ?></td>
                    <?php if($type==='Invoice'): ?><td><?php echo esc_html(wc_price($item->get_total())); ?></td><?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if($type==='Invoice'): ?>
                <tr class="totals">
                    <td colspan="2"><?php esc_html_e('Subtotal', 'awesome-invoice-delivery'); ?></td>
                    <td><?php echo esc_html(wc_price($order->get_subtotal())); ?></td>
                </tr>
                <tr class="totals">
                    <td colspan="2"><?php esc_html_e('Total', 'awesome-invoice-delivery'); ?></td>
                    <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// ---------------- Admin Post Handlers ----------------
add_action('admin_post_aipd_generate_invoice', function(){
    $order_id = isset($_GET['order_id']) ? intval(wp_unslash($_GET['order_id'])) : 0;
    $nonce    = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
    
    if ( ! wp_verify_nonce($nonce, 'aipd_generate_document_'.$order_id) ) {
        wp_die(esc_html__('Security check failed', 'awesome-invoice-delivery'));
    }
    aipd_generate_document_html($order_id, 'Invoice');
});

add_action('admin_post_aipd_generate_packing_slip', function(){
    $order_id = isset($_GET['order_id']) ? intval(wp_unslash($_GET['order_id'])) : 0;
    $nonce    = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
    
    if ( ! wp_verify_nonce($nonce, 'aipd_generate_document_'.$order_id) ) {
        wp_die(esc_html__('Security check failed', 'awesome-invoice-delivery'));
    }
    aipd_generate_document_html($order_id, 'Packing Slip');
});

add_action('admin_post_aipd_generate_delivery_note', function(){
    $order_id = isset($_GET['order_id']) ? intval(wp_unslash($_GET['order_id'])) : 0;
    $nonce    = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
    
    if ( ! wp_verify_nonce($nonce, 'aipd_generate_document_'.$order_id) ) {
        wp_die(esc_html__('Security check failed', 'awesome-invoice-delivery'));
    }
    aipd_generate_document_html($order_id, 'Delivery Note');
});
