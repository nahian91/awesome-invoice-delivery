<?php
if (!defined('ABSPATH')) exit;

function aipd_general_page() {

    // Helper to get option with default
    $defaults = [
        'company_name'    => 'My Company',
        'company_email'   => '',
        'company_phone'   => '',
        'company_address' => '',
        'company_logo'    => '',
        'company_website' => '',
        'header_color'    => '#0073aa',
        'footer_color'    => '#333333',
        'invoice_prefix'  => 'INV',
        'invoice_start'   => 1000,
        'auto_increment'  => 1,
        'footer_text'     => '',
        'date_format'     => 'd/m/Y',
        'show_sku'        => 1,
        'enable_tax'      => 0,
        'enable_discount' => 0,
        'enable_email'    => 0,
        'email_subject'   => 'Your Invoice from Company',
        'email_body'      => 'Hello {customer_name}, Please find your invoice attached.',
        'enable_barcode'  => 0,
        'paper_size'      => 'A4',
        'reset_invoice'   => 'never',

        // Style tab defaults
        'style_font_family' => 'Arial, sans-serif',
        'style_font_size'   => '14px',
        'style_text_color'  => '#333333',
        'style_background'  => '#ffffff',
        'style_header_color' => '#0073aa',
        'style_footer_color' => '#333333'
    ];
    $get = function($key) use ($defaults) {
        return get_option('aipd_'.$key, $defaults[$key]);
    };

    ?>
    <style>
        /* Tabs & toggles */
        .aipd-tabs { margin-top:20px; }
        .aipd-tab-buttons button { background:#f1f1f1; border:1px solid #ccc; padding:10px 20px; cursor:pointer; margin-right:2px; border-radius:4px 4px 0 0; }
        .aipd-tab-buttons button.active { background:#0073aa; color:#fff; }
        .aipd-tab-content { border:1px solid #ccc; padding:20px; display:none; border-radius:0 4px 4px 4px; background:#fff; }
        .aipd-tab-content.active { display:block; }
        .aipd-toggle{position:relative;display:inline-block;width:50px;height:24px;}
        .aipd-toggle input{display:none;}
        .aipd-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;transition:.4s;border-radius:24px;}
        .aipd-slider:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background:white;transition:.4s;border-radius:50%;}
        input:checked + .aipd-slider{background:#0073aa;}
        input:checked + .aipd-slider:before{transform:translateX(26px);}
        .form-table th{width:220px;padding:12px 10px;vertical-align:middle;}
        .form-table td{padding:10px;}
        .form-table input[type=text], .form-table input[type=email], .form-table input[type=number], .form-table textarea, .form-table select{width:100%;padding:8px;border-radius:4px;border:1px solid #ccc;}
        @media(max-width:768px){ .form-table th, .form-table td{ display:block; width:100%; } .form-table th{ margin-top:10px; } }
        #aipd_logo_preview{max-width:150px;margin-top:5px; display:block;}
    </style>

    <div class="wrap">
        <h1>General Settings</h1>
        <div class="aipd-tabs">
            <div class="aipd-tab-buttons">
                <button class="active" data-tab="company">Company Info</button>
                <button data-tab="invoice">Invoice Settings</button>
                <button data-tab="advanced">Advanced Settings</button>
                <button data-tab="email">Email Notification</button>
                <button data-tab="style">Style</button> <!-- New Style Tab -->
            </div>

            <form method="post">
                <?php wp_nonce_field('aipd_save_general','aipd_nonce'); ?>

                <!-- Company Info -->
                <div id="company" class="aipd-tab-content active">
                    <h2>Company Info</h2>
                    <table class="form-table">
                        <tr><th>Company Name</th><td><input type="text" name="aipd_company_name" value="<?php echo esc_attr($get('company_name')); ?>"></td></tr>
                        <tr><th>Email</th><td><input type="email" name="aipd_company_email" value="<?php echo esc_attr($get('company_email')); ?>"></td></tr>
                        <tr><th>Phone</th><td><input type="text" name="aipd_company_phone" value="<?php echo esc_attr($get('company_phone')); ?>"></td></tr>
                        <tr><th>Address</th><td><textarea name="aipd_company_address" rows="3"><?php echo esc_textarea($get('company_address')); ?></textarea></td></tr>
                        <tr><th>Logo</th><td>
                            <input type="text" id="aipd_company_logo" name="aipd_company_logo" value="<?php echo esc_url($get('company_logo')); ?>" style="width:80%;" readonly>
                            <button type="button" class="button" id="aipd_upload_logo">Upload</button>
                            <?php if($get('company_logo')): ?>
                                <img id="aipd_logo_preview" src="<?php echo esc_url($get('company_logo')); ?>">
                            <?php else: ?>
                                <img id="aipd_logo_preview" style="display:none;">
                            <?php endif; ?>
                        </td></tr>
                        <tr><th>Website</th><td><input type="text" name="aipd_company_website" value="<?php echo esc_url($get('company_website')); ?>"></td></tr>
                    </table>
                </div>

                <!-- Invoice Settings -->
                <div id="invoice" class="aipd-tab-content">
                    <h2>Invoice Settings</h2>
                    <table class="form-table">
                        <tr><th>Prefix</th><td><input type="text" name="aipd_invoice_prefix" value="<?php echo esc_attr($get('invoice_prefix')); ?>"></td></tr>
                        <tr><th>Start Number</th><td><input type="number" name="aipd_invoice_start" value="<?php echo esc_attr($get('invoice_start')); ?>"></td></tr>
                        <tr><th>Auto Increment</th><td><label class="aipd-toggle"><input type="checkbox" name="aipd_auto_increment" <?php checked($get('auto_increment'),1); ?>><span class="aipd-slider"></span></label></td></tr>
                        <tr><th>Footer Text</th><td><input type="text" name="aipd_footer_text" value="<?php echo esc_attr($get('footer_text')); ?>"></td></tr>
                        <tr><th>Date Format</th><td><input type="text" name="aipd_date_format" value="<?php echo esc_attr($get('date_format')); ?>" placeholder="d/m/Y"></td></tr>
                        <tr><th>Show SKU</th><td><label class="aipd-toggle"><input type="checkbox" name="aipd_show_sku" <?php checked($get('show_sku'),1); ?>><span class="aipd-slider"></span></label></td></tr>
                    </table>
                </div>

                <!-- Advanced Settings -->
                <div id="advanced" class="aipd-tab-content">
                    <h2>Advanced Settings</h2>
                    <table class="form-table">
                        <tr><th>Enable Tax</th><td><label class="aipd-toggle"><input type="checkbox" name="aipd_enable_tax" <?php checked($get('enable_tax'),1); ?>><span class="aipd-slider"></span></label></td></tr>
                        <tr><th>Enable Discount</th><td><label class="aipd-toggle"><input type="checkbox" name="aipd_enable_discount" <?php checked($get('enable_discount'),1); ?>><span class="aipd-slider"></span></label></td></tr>
                        <tr><th>Enable Barcode/QR</th><td><label class="aipd-toggle"><input type="checkbox" name="aipd_enable_barcode" <?php checked($get('enable_barcode'),1); ?>><span class="aipd-slider"></span></label></td></tr>
                        <tr><th>Paper Size</th><td>
                            <select name="aipd_paper_size">
                                <option value="A4" <?php selected($get('paper_size'),'A4'); ?>>A4</option>
                                <option value="Letter" <?php selected($get('paper_size'),'Letter'); ?>>Letter</option>
                            </select>
                        </td></tr>
                        <tr><th>Reset Invoice</th><td>
                            <select name="aipd_reset_invoice">
                                <option value="never" <?php selected($get('reset_invoice'),'never'); ?>>Never</option>
                                <option value="monthly" <?php selected($get('reset_invoice'),'monthly'); ?>>Monthly</option>
                                <option value="yearly" <?php selected($get('reset_invoice'),'yearly'); ?>>Yearly</option>
                            </select>
                        </td></tr>
                    </table>
                </div>

                <!-- Email Settings -->
                <div id="email" class="aipd-tab-content">
                    <h2>Email Notification</h2>
                    <table class="form-table">
                        <tr>
                            <th>Enable Email</th>
                            <td><label class="aipd-toggle"><input type="checkbox" id="aipd_enable_email" name="aipd_enable_email" <?php checked($get('enable_email'),1); ?>><span class="aipd-slider"></span></label></td>
                        </tr>
                        <tr class="aipd-email-fields">
                            <th>Email Subject</th>
                            <td><input type="text" name="aipd_email_subject" value="<?php echo esc_attr($get('email_subject')); ?>"></td>
                        </tr>
                        <tr class="aipd-email-fields">
                            <th>Email Body</th>
                            <td><textarea name="aipd_email_body" rows="4"><?php echo esc_textarea($get('email_body')); ?></textarea></td>
                        </tr>
                    </table>
                </div>

                <!-- Style Tab -->
                <div id="style" class="aipd-tab-content">
                    <h2>Style Settings</h2>
                    <table class="form-table">
                        <tr><th>Font Family</th><td>
                            <select name="aipd_style_font_family">
                                <option value="Arial, sans-serif" <?php selected($get('style_font_family'),'Arial, sans-serif'); ?>>Arial</option>
                                <option value="Tahoma, sans-serif" <?php selected($get('style_font_family'),'Tahoma, sans-serif'); ?>>Tahoma</option>
                                <option value="Verdana, sans-serif" <?php selected($get('style_font_family'),'Verdana, sans-serif'); ?>>Verdana</option>
                                <option value="Helvetica, sans-serif" <?php selected($get('style_font_family'),'Helvetica, sans-serif'); ?>>Helvetica</option>
                                <option value="Georgia, serif" <?php selected($get('style_font_family'),'Georgia, serif'); ?>>Georgia</option>
                            </select>
                        </td></tr>
                        <tr><th>Font Size</th><td><input type="text" name="aipd_style_font_size" value="<?php echo esc_attr($get('style_font_size')); ?>" placeholder="14px"></td></tr>
                        <tr><th>Text Color</th><td><input type="text" name="aipd_style_text_color" class="aipd-color-picker" value="<?php echo esc_attr($get('style_text_color')); ?>"></td></tr>
                        <tr><th>Background Color</th><td><input type="text" name="aipd_style_background" class="aipd-color-picker" value="<?php echo esc_attr($get('style_background')); ?>"></td></tr>
                        <tr><th>Header Color</th><td><input type="text" name="aipd_style_header_color" class="aipd-color-picker" value="<?php echo esc_attr($get('style_header_color')); ?>"></td></tr>
                        <tr><th>Footer Color</th><td><input type="text" name="aipd_style_footer_color" class="aipd-color-picker" value="<?php echo esc_attr($get('style_footer_color')); ?>"></td></tr>
                    </table>
                </div>

                <?php submit_button(); ?>
            </form>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($){
            // Tabs with last active tab memory
            const tabButtons = $('.aipd-tab-buttons button');
            const tabContents = $('.aipd-tab-content');
            tabButtons.click(function(){
                const tab = $(this).data('tab');
                localStorage.setItem('aipd_active_tab', tab);
                tabButtons.removeClass('active'); tabContents.removeClass('active');
                $(this).addClass('active'); $('#'+tab).addClass('active');
            });
            const activeTab = localStorage.getItem('aipd_active_tab') || 'company';
            $('.aipd-tab-buttons button[data-tab="'+activeTab+'"]').click();

            // Logo Upload & Preview
            var mediaUploader;
            $('#aipd_upload_logo').click(function(e){
                e.preventDefault();
                if(mediaUploader){ mediaUploader.open(); return; }
                mediaUploader = wp.media({ title: 'Choose Logo', button: { text: 'Choose Logo' }, multiple: false });
                mediaUploader.on('select', function(){
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#aipd_company_logo').val(attachment.url);
                    $('#aipd_logo_preview').attr('src', attachment.url).show();
                });
                mediaUploader.open();
            });

            // Color Picker
            $('.aipd-color-picker').wpColorPicker();

            // Show/hide email fields
            function toggleEmailFields(){
                if($('#aipd_enable_email').is(':checked')){
                    $('.aipd-email-fields').slideDown();
                } else {
                    $('.aipd-email-fields').slideUp();
                }
            }
            toggleEmailFields();
            $('#aipd_enable_email').change(toggleEmailFields);
        });
    </script>
<?php
}
