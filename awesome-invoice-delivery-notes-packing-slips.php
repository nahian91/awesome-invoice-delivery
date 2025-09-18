<?php
/**
 * Plugin Name: Awesome Invoice, Delivery Notes & Packing Slips
 * Description: WooCommerce Invoice, Packing Slip & Delivery Note Generator (Professional Version)
 * Version: 1.5
 * Author: Abdullah Nahian
 * Text Domain: awesome-invoice-delivery
 */

if (!defined('ABSPATH')) exit;

// Include all admin files
require_once plugin_dir_path(__FILE__).'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__).'includes/orders-page.php';
require_once plugin_dir_path(__FILE__).'includes/general-page.php';
require_once plugin_dir_path(__FILE__).'includes/reports-page.php';
require_once plugin_dir_path(__FILE__).'includes/templates-page.php';
require_once plugin_dir_path(__FILE__).'includes/document-generator.php';

// Enqueue admin assets
add_action('admin_enqueue_scripts', function($hook){
    if(strpos($hook,'aipd')!==false){
        wp_enqueue_style('aipd-admin', plugins_url('assets/css/admin.css', __FILE__), [], '1.0.0');
        wp_enqueue_script('aipd-admin', plugins_url('assets/js/admin.js', __FILE__), ['jquery'], '1.0.0', true);
    }
});

