<?php
if (!defined('ABSPATH')) exit;

// Templates admin page
function aipd_templates_page() {

    // Save selected templates
    if (isset($_POST['aipd_nonce']) && wp_verify_nonce($_POST['aipd_nonce'], 'aipd_save_templates')) {
        $types = ['invoice','delivery','package'];
        foreach($types as $type) {
            if(isset($_POST['aipd_template_'.$type])) {
                update_option('aipd_template_'.$type, sanitize_text_field($_POST['aipd_template_'.$type]));
            }
        }
        echo '<div class="notice notice-success"><p>Templates saved successfully.</p></div>';
    }

    $get = function($key,$default=''){ return get_option('aipd_'.$key,$default); };

    // Prebuilt templates
    $templates = [
        'invoice' => [
            'default' => ['name'=>'Default Invoice','preview'=>plugins_url('../assets/previews/invoice-default.png',__FILE__)],
            'modern'  => ['name'=>'Modern Invoice','preview'=>plugins_url('../assets/previews/invoice-modern.png',__FILE__)],
        ],
        'delivery' => [
            'default' => ['name'=>'Default Delivery','preview'=>plugins_url('../assets/previews/delivery-default.png',__FILE__)],
            'compact' => ['name'=>'Compact Delivery','preview'=>plugins_url('../assets/previews/delivery-compact.png',__FILE__)],
        ],
        'package' => [
            'default' => ['name'=>'Default Package','preview'=>plugins_url('../assets/previews/package-default.png',__FILE__)],
            'minimal' => ['name'=>'Minimal Package','preview'=>plugins_url('../assets/previews/package-minimal.png',__FILE__)],
        ]
    ];
    ?>
    <style>
        .aipd-section { background:#fff;border:1px solid #ddd;padding:20px;margin-bottom:20px;border-radius:6px; }
        .aipd-section h2 { color:#0073aa;border-bottom:1px solid #eee;padding-bottom:5px;margin-top:0;}
        .template-option { display:inline-block; width:200px; margin:10px; vertical-align:top; text-align:center; }
        .template-option img { width:100%; height:auto; border:1px solid #ccc; border-radius:4px; margin-bottom:5px; }
        .aipd-toggle{position:relative;display:inline-block;width:50px;height:24px;}
        .aipd-toggle input{display:none;}
        .aipd-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;transition:.4s;border-radius:24px;}
        .aipd-slider:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background:white;transition:.4s;border-radius:50%;}
        input:checked + .aipd-slider{background:#0073aa;}
        input:checked + .aipd-slider:before{transform:translateX(26px);}
        /* Modal Styles */
        .aipd-modal { display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background:rgba(0,0,0,0.6);}
        .aipd-modal-content { background:#fff; margin:50px auto; padding:20px; border-radius:6px; width:80%; max-width:900px; position:relative;}
        .aipd-close { color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer; position:absolute; top:10px; right:20px;}
        .aipd-close:hover { color:#000; }
    </style>

    <form method="post">
        <?php wp_nonce_field('aipd_save_templates','aipd_nonce'); ?>

        <?php foreach($templates as $type => $tpls): ?>
            <div class="aipd-section">
                <h2><?php echo ucfirst($type); ?> Templates</h2>
                <?php foreach($tpls as $id=>$tpl): ?>
                    <div class="template-option">
                        <label>
                            <input type="radio" name="aipd_template_<?php echo esc_attr($type); ?>" value="<?php echo esc_attr($id); ?>" <?php checked($get('template_'.$type), $id); ?>>
                            <?php echo esc_html($tpl['name']); ?>
                        </label>
                        <img src="<?php echo esc_url($tpl['preview']); ?>" alt="">
                        <button type="button" class="button aipd-preview-btn" data-template="<?php echo esc_attr($id); ?>" data-type="<?php echo esc_attr($type); ?>">Preview</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div id="aipd-template-modal" class="aipd-modal">
            <div class="aipd-modal-content">
                <span class="aipd-close">&times;</span>
                <div id="aipd-modal-body"></div>
            </div>
        </div>

        <?php submit_button('Save Templates'); ?>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('aipd-template-modal');
        const modalBody = document.getElementById('aipd-modal-body');
        const closeBtn = modal.querySelector('.aipd-close');

        document.querySelectorAll('.aipd-preview-btn').forEach(btn=>{
            btn.addEventListener('click', function(){
                const type = this.dataset.type;
                const tpl  = this.dataset.template;
                modalBody.innerHTML = '<p>Loading preview...</p>';
                fetch('<?php echo admin_url("admin-ajax.php"); ?>?action=aipd_preview_template&type='+type+'&template='+tpl)
                    .then(res=>res.text())
                    .then(html=>modalBody.innerHTML = html);
                modal.style.display = 'block';
            });
        });

        closeBtn.addEventListener('click', ()=> modal.style.display='none');
        window.addEventListener('click', e => { if(e.target==modal) modal.style.display='none'; });
    });
    </script>
<?php
}

// AJAX handler to render template preview
add_action('wp_ajax_aipd_preview_template', function(){
    $type = sanitize_text_field($_GET['type'] ?? '');
    $tpl  = sanitize_text_field($_GET['template'] ?? '');
    $file = plugin_dir_path(__FILE__)."../templates/{$type}/{$tpl}.php";
    if(file_exists($file)){
        include $file;
    } else {
        echo '<p>Template preview not found.</p>';
    }
    wp_die();
});
