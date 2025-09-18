<?php
/**
 * Plugin Name: Awesome Invoice, Delivery Notes & Packing Slips
 * Description: WooCommerce Invoice, Packing Slip & Delivery Note Generator (Professional Version)
 * Version: 1.5
 * Author: Abdullah Nahian
 * Text Domain: awesome-invoice-delivery-notes-packing-slips
 */

if (!defined('ABSPATH')) exit;


if ( ! function_exists( 'aidnps_fs' ) ) {
    // Create a helper function for easy SDK access.
    function aidnps_fs() {
        global $aidnps_fs;

        if ( ! isset( $aidnps_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $aidnps_fs = fs_dynamic_init( array(
                'id'                  => '20792',
                'slug'                => 'awesome-invoice-delivery-notes-packing-slips',
                'type'                => 'plugin',
                'public_key'          => 'pk_d2bf083a1564f3855657e8b882a0c',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'account'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $aidnps_fs;
    }

    // Init Freemius.
    aidnps_fs();
    // Signal that SDK was initiated.
    do_action( 'aidnps_fs_loaded' );
}

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

