<?php
if (!defined('ABSPATH')) exit;

// Generate Invoice
add_action('admin_post_aipd_generate_invoice', 'aipd_generate_invoice');
add_action('admin_post_nopriv_aipd_generate_invoice', 'aipd_generate_invoice');

function aipd_generate_invoice() {
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'aipd_generate_document_'.$_GET['order_id'])) {
        wp_die('Nonce verification failed');
    }

    $order_id = intval($_GET['order_id']);
    $order = wc_get_order($order_id);
    if (!$order) wp_die('Order not found');

    // Get selected template from options
    $template = get_option('aipd_template_invoice', 'default');

    // Load template file
    $template_file = plugin_dir_path(__FILE__)."templates/invoice-{$template}.php";
    if (!file_exists($template_file)) $template_file = plugin_dir_path(__FILE__)."templates/invoice-default.php";

    // Load template
    include $template_file;
    exit;
}

// Generate Delivery Note
add_action('admin_post_aipd_generate_delivery_note', 'aipd_generate_delivery_note');
add_action('admin_post_nopriv_aipd_generate_delivery_note', 'aipd_generate_delivery_note');

function aipd_generate_delivery_note() {
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'aipd_generate_document_'.$_GET['order_id'])) {
        wp_die('Nonce verification failed');
    }

    $order_id = intval($_GET['order_id']);
    $order = wc_get_order($order_id);
    if (!$order) wp_die('Order not found');

    $template = get_option('aipd_template_delivery', 'default');
    $template_file = plugin_dir_path(__FILE__)."templates/delivery-{$template}.php";
    if (!file_exists($template_file)) $template_file = plugin_dir_path(__FILE__)."templates/delivery-default.php";

    include $template_file;
    exit;
}

// Generate Packing Slip
add_action('admin_post_aipd_generate_packing_slip', 'aipd_generate_packing_slip');
add_action('admin_post_nopriv_aipd_generate_packing_slip', 'aipd_generate_packing_slip');

function aipd_generate_packing_slip() {
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'aipd_generate_document_'.$_GET['order_id'])) {
        wp_die('Nonce verification failed');
    }

    $order_id = intval($_GET['order_id']);
    $order = wc_get_order($order_id);
    if (!$order) wp_die('Order not found');

    $template = get_option('aipd_template_package', 'default');
    $template_file = plugin_dir_path(__FILE__)."templates/package-{$template}.php";
    if (!file_exists($template_file)) $template_file = plugin_dir_path(__FILE__)."templates/package-default.php";

    include $template_file;
    exit;
}
