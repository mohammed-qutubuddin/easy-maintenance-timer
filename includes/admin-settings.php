<?php
// Settings Page & Registration
add_action('admin_menu', function() {
    add_options_page(
        'EMM Settings', 'Maintenance Mode', 'manage_options', 'emm_settings', 'emm_settings_page_callback'
    );
});

add_action('admin_init', function() {
    register_setting('emm_settings_group', 'emm_countdown_date');
    register_setting('emm_settings_group', 'emm_maint_message');
    register_setting('emm_settings_group', 'emm_logo_url');
    register_setting('emm_settings_group', 'emm_enabled');
});


function emm_settings_page_callback() {
$default_msg  = "Site Under Maintenance. Please check back soon.";
$default_logo = plugin_dir_url(__FILE__).'../assets/img/default-logo.jpg';
$default_date = date('Y-m-d\TH:i', strtotime('+1 day'));
$value_msg = get_option('emm_maint_message', $default_msg);
if (!get_option('emm_maint_message')) {
    $value_msg = $default_msg;
}
$value_logo = get_option('emm_logo_url', $default_logo);
if (!get_option('emm_logo_url')) {
    $value_logo = $default_logo;
}
$value_date = get_option('emm_countdown_date', $default_date);
if (!$value_date) {
    $value_date = $default_date;
}
?>
    <div class="wrap">
        <h2>Easy Maintenance Mode Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('emm_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th>Enable Maintenance Mode</th>
                    <td>
                        <input type="checkbox" id="emm_enabled" name="emm_enabled" value="1" <?php checked(1, get_option('emm_enabled', 0)); ?> />
                        <label for="emm_enabled">ON/OFF</label>
                    </td>
                </tr>
                <tr>                    
                    <th>Countdown End Date/Time</th>
                    <td>
                        <input type="datetime-local" name="emm_countdown_date" class="emm-dependent" 
                        min="<?php echo date('Y-m-d\TH:i'); ?>" 
                        value="<?php echo esc_attr($value_date); ?>" 
                        required />
                    </td>
                </tr>
                <tr>
                    <th>Maintenance Message</th>
                    <td>
                        
                        <input type="text" name="emm_maint_message" class="emm-dependent" value="<?php echo esc_attr($value_msg); ?>" size="50" />
                    </td>
                </tr>
                <tr>
                    <th>Logo URL</th>
                    <td>
                        <input type="text" class="emm-dependent" id="emm_logo_url" name="emm_logo_url" value="<?php echo esc_url($value_logo); ?>" />
                        <button class="emm-dependent" type="button" class="button" id="emm_logo_upload">Upload / Select</button>
                        <br>
                        <small>Upload Your Custom Logo</small>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var chk = document.getElementById('emm_enabled');
                function toggleFields() {
                    var deps = document.querySelectorAll('.emm-dependent');
                    for(var i=0; i<deps.length; i++) {
                        deps[i].disabled = !chk.checked;
                    }
                }
                chk.addEventListener('change', toggleFields);
                toggleFields(); // page load par bhi
            });
            </script>
    </div>
<?php
}
add_action('admin_enqueue_scripts', function($hook) {
    if($hook != 'settings_page_emm_settings') return;
    wp_enqueue_media();
    wp_enqueue_script('emm-admin-script', plugin_dir_url(__FILE__).'../assets/js/admin.js', array('jquery'), null, true);
});
