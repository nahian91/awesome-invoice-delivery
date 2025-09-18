<?php
if(!defined('ABSPATH')) exit;

add_action('admin_menu', 'aipd_admin_menu');
function aipd_admin_menu() {
    add_menu_page(
        'Awesome Invoice',
        'Awesome Invoice',
        'manage_woocommerce',
        'aipd_main',
        'aipd_main_page',
        'dashicons-media-document',
        56
    );
}

function aipd_main_page() {
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'orders';
    ?>
    <div class="wrap">
        <h1 class="nav-tab-wrapper">
            <a href="?page=aipd_main&tab=general" class="nav-tab <?php echo $tab==='general'?'nav-tab-active':''; ?>">General</a>
            <a href="?page=aipd_main&tab=orders" class="nav-tab <?php echo $tab==='orders'?'nav-tab-active':''; ?>">Orders</a>
            <a href="?page=aipd_main&tab=templates" class="nav-tab <?php echo $tab==='templates'?'nav-tab-active':''; ?>">Templates</a>
            <a href="?page=aipd_main&tab=reports" class="nav-tab <?php echo $tab==='reports'?'nav-tab-active':''; ?>">Reports</a>
        </h1>
        <?php
        if ($tab==='general') aipd_general_page();
        elseif ($tab==='orders') aipd_orders_page();
        elseif ($tab==='reports') aipd_reports_page();
        elseif ($tab==='templates') aipd_templates_page();
        ?>
    </div>
    <?php
}
