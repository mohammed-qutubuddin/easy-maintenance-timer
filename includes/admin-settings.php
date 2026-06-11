<?php
/**
 * Settings page rendering and registration.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

function emmwt_register_settings_page() {
    add_options_page(
        esc_html__( 'Easy Maintenance Settings', 'easy-maintenance-timer' ),
        esc_html__( 'Maintenance Mode', 'easy-maintenance-timer' ),
        'manage_options',
        'emmwt_settings',
        'emmwt_settings_page_callback'
    );
}
add_action( 'admin_menu', 'emmwt_register_settings_page' );

function emmwt_register_settings() {
    register_setting( 'emmwt_settings_group', 'emmwt_countdown_date', [ 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_maint_message', [ 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_logo_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_bg_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_enabled', [ 'sanitize_callback' => 'emmwt_sanitize_checkbox' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_bypass_roles', [ 'sanitize_callback' => 'emmwt_sanitize_array' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_enable_social', [ 'sanitize_callback' => 'emmwt_sanitize_checkbox' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_email', [ 'sanitize_callback' => 'sanitize_email' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_fb', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_tw', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_wa', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_li', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_ig', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_delete_on_uninstall', [ 'sanitize_callback' => 'emmwt_sanitize_checkbox' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_bypass_ips', [ 'sanitize_callback' => 'emmwt_sanitize_ips' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_block_rest_api', [ 'sanitize_callback' => 'emmwt_sanitize_checkbox' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_custom_css', [ 'sanitize_callback' => 'wp_strip_all_tags' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_maint_description', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_msg_color', [ 'sanitize_callback' => 'sanitize_hex_color' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_desc_color', [ 'sanitize_callback' => 'sanitize_hex_color' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_font_family', [ 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_seo_title', [ 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_seo_meta_desc', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_status_type', [ 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_custom_scripts', [ 'sanitize_callback' => 'emmwt_sanitize_scripts' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_bg_overlay_color', [ 'sanitize_callback' => 'sanitize_hex_color' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_bg_overlay_opacity', [ 'sanitize_callback' => 'absint' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_enable_subscribe', [ 'sanitize_callback' => 'absint' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_bg_animation', [ 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_notify_admin', [ 'sanitize_callback' => 'emmwt_sanitize_checkbox' ] );
}
add_action( 'admin_init', 'emmwt_register_settings' );

function emmwt_admin_settings_enqueue( $hook ) {
    if ( 'settings_page_emmwt_settings' !== $hook ) return;

    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'flatpickr-css', EMMWT_PLUGIN_URL . 'assets/css/flatpickr.min.css', array(), '4.6.13' );
    wp_enqueue_script( 'flatpickr-js', EMMWT_PLUGIN_URL . 'assets/js/flatpickr.min.js', array('jquery'), '4.6.13', true );
    wp_enqueue_style( 'emmwt-admin-css', EMMWT_PLUGIN_URL . 'assets/css/admin.css', array(), EMMWT_VERSION );
    wp_enqueue_script( 'emmwt-admin-settings-js', EMMWT_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'flatpickr-js', 'wp-color-picker' ), EMMWT_VERSION, true );
    
    wp_localize_script( 'emmwt-admin-settings-js', 'emmwt_admin', array(
        'title_logo' => esc_html__( 'Select or Upload Logo', 'easy-maintenance-timer' ),
        'title_bg'   => esc_html__( 'Select or Upload Background', 'easy-maintenance-timer' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'emmwt_admin_settings_enqueue' );

/**
 * Render the plugin settings page.
 */
function emmwt_settings_page_callback() {
    $bg_animation = (string) get_option( 'emmwt_bg_animation', 'none' );
    $bg_overlay_color   = (string) get_option( 'emmwt_bg_overlay_color', '#000000' );
    $bg_overlay_opacity = (int) get_option( 'emmwt_bg_overlay_opacity', 50 ); 
    $default_date = gmdate( 'Y-m-d H:i', strtotime( '+1 day' ) );
    $saved_ips  = (string) get_option( 'emmwt_bypass_ips', '' );
    $current_ip = function_exists( 'emmwt_get_visitor_ip' ) ? emmwt_get_visitor_ip() : '';
    $value_msg   = (string) get_option( 'emmwt_maint_message', 'Site Under Maintenance. Please check back soon.' );
    $value_logo  = (string) get_option( 'emmwt_logo_url', '' );
    $value_bg    = (string) get_option( 'emmwt_bg_url', '' ); 
    $value_date  = (string) get_option( 'emmwt_countdown_date', $default_date );
    $saved_roles = (array) get_option( 'emmwt_bypass_roles', ['editor'] );
    $value_desc = (string) get_option( 'emmwt_maint_description', '' );
    $msg_color  = (string) get_option( 'emmwt_msg_color', '#000000' );
    $desc_color = (string) get_option( 'emmwt_desc_color', '#50575e' );
    $font_family = (string) get_option( 'emmwt_font_family', 'system' );
    $bypass_url  = add_query_arg( 'emmwt_bypass', 'true', site_url() );
    $preview_url = add_query_arg( 'emmwt_preview', 'true', site_url() ); 
    $seo_title = (string) get_option( 'emmwt_seo_title', '' );
    $seo_desc  = (string) get_option( 'emmwt_seo_meta_desc', '' );
    $status_type = (string) get_option( 'emmwt_status_type', 'maintenance' );

    // Fetch Subscribers for TAB 6
    global $wpdb;
    $table_name = $wpdb->prefix . 'emmwt_subscribers';
    $subscribers = [];
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) === $table_name ) {
        $subscribers = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY subscribed_at DESC" );
    }
    
    // Get all WP roles
    global $wp_roles;
    if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
    $all_roles = $wp_roles->get_names();
    ?>
    
    <div class="wrap emmwt-wrap">
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; margin-top: 10px; flex-wrap: wrap; gap: 10px;">
            <h2 style="margin: 0;"><?php esc_html_e( 'Easy Maintenance Mode Settings', 'easy-maintenance-timer' ); ?></h2>
            
            <a href="<?php echo esc_url( $preview_url ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary" title="<?php esc_attr_e( 'Make sure to Save Changes before previewing!', 'easy-maintenance-timer' ); ?>">
                <span class="dashicons dashicons-visibility" style="vertical-align: middle; margin-top: -17px;"></span> 
                <span style="vertical-align: middle;"><?php esc_html_e( 'Live Preview', 'easy-maintenance-timer' ); ?></span>
            </a>
        </div>
        
        <h2 class="nav-tab-wrapper emmwt-nav-tabs" style="margin-bottom: 20px;">
            <a href="#tab-general" class="nav-tab nav-tab-active"><?php esc_html_e( 'General Settings', 'easy-maintenance-timer' ); ?></a>
            <a href="#tab-access" class="nav-tab"><?php esc_html_e( 'Access Control', 'easy-maintenance-timer' ); ?></a>
            <a href="#tab-social" class="nav-tab"><?php esc_html_e( 'Social & Contact', 'easy-maintenance-timer' ); ?></a>
            <a href="#tab-css" class="nav-tab"><?php esc_html_e( 'Custom CSS & Scripts', 'easy-maintenance-timer' ); ?></a>
            <a href="#tab-subscribers" class="nav-tab"><?php esc_html_e( 'Subscribers', 'easy-maintenance-timer' ); ?></a>
            <a href="#tab-support" class="nav-tab"><?php esc_html_e( 'Support', 'easy-maintenance-timer' ); ?></a>
        </h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'emmwt_settings_group' ); ?>

            <div id="tab-general" class="emmwt-tab-pane" style="display: block;">
                <div class="emmwt-card">
                    <h3><?php esc_html_e( 'General Settings', 'easy-maintenance-timer' ); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Enable Maintenance Mode', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <label class="emmwt-toggle" for="emmwt_enabled">
                                    <input type="checkbox" id="emmwt_enabled" name="emmwt_enabled" value="1" <?php checked( 1, get_option( 'emmwt_enabled', 0 ) ); ?> />
                                    <span class="emmwt-slider"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Mode Type', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <select name="emmwt_status_type" class="emmwt-dependent regular-text">
                                    <option value="maintenance" <?php selected( $status_type, 'maintenance' ); ?>><?php esc_html_e( 'Maintenance Mode (503 Error - SEO Safe)', 'easy-maintenance-timer' ); ?></option>
                                    <option value="coming_soon" <?php selected( $status_type, 'coming_soon' ); ?>><?php esc_html_e( 'Coming Soon (200 OK - Indexable)', 'easy-maintenance-timer' ); ?></option>
                                </select>
                                <p class="description">
                                    <?php esc_html_e( 'Use "Maintenance" for temporary downtime. Use "Coming Soon" if your site is brand new and you want Google to index it.', 'easy-maintenance-timer' ); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>                    
                            <th scope="row"><?php esc_html_e( 'Countdown End Date/Time', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <input type="text" id="emmwt_datepicker" name="emmwt_countdown_date" class="emmwt-dependent" value="<?php echo esc_attr( $value_date ); ?>" required />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Auto-Live Email Notification', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <label class="emmwt-toggle" for="emmwt_notify_admin">
                                    <input type="checkbox" id="emmwt_notify_admin" name="emmwt_notify_admin" value="1" <?php checked( 1, get_option( 'emmwt_notify_admin', 1 ) ); ?> />
                                    <span class="emmwt-slider"></span>
                                </label>
                                <p class="description"><?php esc_html_e( 'Send an automatic email to the site admin when the timer ends and the site goes live.', 'easy-maintenance-timer' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Maintenance Message', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
                                    <input type="text" name="emmwt_maint_message" class="emmwt-dependent regular-text" value="<?php echo esc_attr( $value_msg ); ?>" />
                                    <input type="text" name="emmwt_msg_color" class="emmwt-color-picker emmwt-dependent" value="<?php echo esc_attr( $msg_color ); ?>" data-default-color="#000000" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Description', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <div style="display:flex; gap:15px; align-items:flex-start; flex-wrap:wrap;">
                                    <textarea name="emmwt_maint_description" class="emmwt-dependent large-text" rows="3" placeholder="<?php esc_attr_e( 'Enter a brief description... e.g. We are upgrading our systems.', 'easy-maintenance-timer' ); ?>"><?php echo esc_textarea( $value_desc ); ?></textarea>
                                    <div style="margin-top: 2px;">
                                        <input type="text" name="emmwt_desc_color" class="emmwt-color-picker emmwt-dependent" value="<?php echo esc_attr( $desc_color ); ?>" data-default-color="#50575e" />
                                    </div>
                                </div>
                            </td>
                        </tr>   
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Typography Style', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <select name="emmwt_font_family" class="emmwt-dependent regular-text">
                                    <option value="system" <?php selected( $font_family, 'system' ); ?>><?php esc_html_e( 'System Default (Fastest)', 'easy-maintenance-timer' ); ?></option>
                                    <option value="sans-serif" <?php selected( $font_family, 'sans-serif' ); ?>><?php esc_html_e( 'Modern Sans-Serif', 'easy-maintenance-timer' ); ?></option>
                                    <option value="serif" <?php selected( $font_family, 'serif' ); ?>><?php esc_html_e( 'Classic Serif', 'easy-maintenance-timer' ); ?></option>
                                    <option value="monospace" <?php selected( $font_family, 'monospace' ); ?>><?php esc_html_e( 'Monospace / Code', 'easy-maintenance-timer' ); ?></option>
                                </select>
                                <p class="description"><?php esc_html_e( 'Native OS fonts that load instantly without external requests (Zero Bloat).', 'easy-maintenance-timer' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'SEO Page Title', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <input type="text" name="emmwt_seo_title" class="emmwt-dependent regular-text" value="<?php echo esc_attr( $seo_title ); ?>" placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) . ' - ' . __( 'Maintenance', 'easy-maintenance-timer' ) ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'SEO Meta Description', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <textarea name="emmwt_seo_meta_desc" class="emmwt-dependent large-text" rows="2" placeholder="<?php esc_attr_e( 'Brief description for search engine results to maintain SEO during downtime...', 'easy-maintenance-timer' ); ?>"><?php echo esc_textarea( $seo_desc ); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Logo URL', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <div style="display:flex; gap:10px; align-items:flex-start;">
                                    <input type="text" class="emmwt-dependent regular-text" id="emmwt_logo_url" name="emmwt_logo_url" value="<?php echo esc_url( $value_logo ); ?>" placeholder="Leave blank for clean SVG icon" />
                                    <button class="emmwt-dependent button" type="button" id="emmwt_logo_upload"><?php esc_html_e( 'Select', 'easy-maintenance-timer' ); ?></button>
                                </div>
                                <?php if ( $value_logo ) : ?>
                                    <img src="<?php echo esc_url( $value_logo ); ?>" class="emmwt-logo-preview" alt="Logo" />
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Background Image', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <div style="display:flex; gap:10px; align-items:flex-start;">
                                    <input type="text" class="emmwt-dependent regular-text" id="emmwt_bg_url" name="emmwt_bg_url" value="<?php echo esc_url( $value_bg ); ?>" />
                                    <button class="emmwt-dependent button" type="button" id="emmwt_bg_upload"><?php esc_html_e( 'Select', 'easy-maintenance-timer' ); ?></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Background Overlay', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
                                    <input type="text" name="emmwt_bg_overlay_color" class="emmwt-color-picker emmwt-dependent" value="<?php echo esc_attr( $bg_overlay_color ); ?>" data-default-color="#000000" />
                                    
                                    <div style="display:flex; align-items:center; gap:5px;">
                                        <label for="emmwt_bg_overlay_opacity"><?php esc_html_e( 'Opacity:', 'easy-maintenance-timer' ); ?></label>
                                        <input type="number" id="emmwt_bg_overlay_opacity" name="emmwt_bg_overlay_opacity" class="small-text emmwt-dependent" min="0" max="100" value="<?php echo esc_attr( $bg_overlay_opacity ); ?>" /> %
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Background Animation', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <select name="emmwt_bg_animation" class="emmwt-dependent regular-text">
                                    <option value="none" <?php selected( $bg_animation, 'none' ); ?>><?php esc_html_e( 'None (Static)', 'easy-maintenance-timer' ); ?></option>
                                    <option value="zoom" <?php selected( $bg_animation, 'zoom' ); ?>><?php esc_html_e( 'Slow Zoom (Ken Burns Effect - Needs Image)', 'easy-maintenance-timer' ); ?></option>
                                    <option value="gradient" <?php selected( $bg_animation, 'gradient' ); ?>><?php esc_html_e( 'Animated Color Gradient (Image ignored)', 'easy-maintenance-timer' ); ?></option>
                                </select>
                                <p class="description"><?php esc_html_e( 'Add a modern, 100% pure CSS animation to your background without slowing down the site.', 'easy-maintenance-timer' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Clean Data on Uninstall', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <label class="emmwt-toggle" for="emmwt_delete_on_uninstall">
                                    <input type="checkbox" id="emmwt_delete_on_uninstall" name="emmwt_delete_on_uninstall" value="1" <?php checked( 1, get_option( 'emmwt_delete_on_uninstall', 0 ) ); ?> />
                                    <span class="emmwt-slider"></span>
                                </label>
                                <span class="emmwt-toggle-label"><?php esc_html_e( 'ON/OFF', 'easy-maintenance-timer' ); ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="tab-access" class="emmwt-tab-pane" style="display: none;">
                <div class="emmwt-card">
                    <h3><?php esc_html_e( 'Access Control (Bypass)', 'easy-maintenance-timer' ); ?></h3>
                    
                    <div style="background: #f0f6fc; border-left: 4px solid #72aee6; padding: 12px 15px; margin-bottom: 20px;">
                        <strong><?php esc_html_e( 'Secret Client Bypass URL:', 'easy-maintenance-timer' ); ?></strong>
                        <p style="margin: 5px 0 0 0;"><?php esc_html_e( 'Share this link with clients to bypass the screen without an account.', 'easy-maintenance-timer' ); ?></p>
                        <code style="display: block; margin-top: 10px; padding: 8px; background: #fff; border: 1px solid #ccc;"><?php echo esc_url( $bypass_url ); ?></code>
                    </div>

                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Allow these roles to bypass:', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <label style="display:block; margin-bottom:5px;">
                                    <input type="checkbox" checked disabled> Administrator (Always Allowed)
                                </label>
                                <?php 
                                foreach ( $all_roles as $role_slug => $role_name ) {
                                    if ( $role_slug === 'administrator' ) continue;
                                    $checked = in_array( $role_slug, $saved_roles ) ? 'checked' : '';
                                    echo '<label style="display:block; margin-bottom:5px;">';
                                    echo '<input type="checkbox" class="emmwt-dependent" name="emmwt_bypass_roles[]" value="' . esc_attr( $role_slug ) . '" ' . esc_attr( $checked ) . '>';
                                    echo ' ' . esc_html( translate_user_role( $role_name ) );
                                    echo '</label>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php esc_html_e( 'IP Whitelist', 'easy-maintenance-timer' ); ?><br>
                                <small style="font-weight:normal; color:#666;"><?php esc_html_e( 'Enter one IP address per line.', 'easy-maintenance-timer' ); ?></small>
                            </th>
                            <td>
                                <textarea name="emmwt_bypass_ips" class="emmwt-dependent large-text" rows="4" placeholder="e.g. 192.168.1.1"><?php echo esc_textarea( $saved_ips ); ?></textarea>
                                <?php if ( $current_ip ) : ?>
                                    <p class="description">
                                        <?php esc_html_e( 'Your current IP address is:', 'easy-maintenance-timer' ); ?> 
                                        <strong id="emmwt-current-ip"><?php echo esc_html( $current_ip ); ?></strong>
                                        <button type="button" class="button button-small" id="emmwt-add-my-ip" style="margin-left: 10px;"><?php esc_html_e( 'Add My IP', 'easy-maintenance-timer' ); ?></button>
                                    </p>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Block REST API', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <label class="emmwt-toggle" for="emmwt_block_rest_api">
                                    <input type="checkbox" id="emmwt_block_rest_api" name="emmwt_block_rest_api" value="1" <?php checked( 1, get_option( 'emmwt_block_rest_api', 0 ) ); ?> />
                                    <span class="emmwt-slider"></span>
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="tab-social" class="emmwt-tab-pane" style="display: none;">
                <div class="emmwt-card">
                    <h3><?php esc_html_e( 'Social & Contact Links', 'easy-maintenance-timer' ); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Enable Contact Icons', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <label class="emmwt-toggle" for="emmwt_enable_social">
                                    <input type="checkbox" id="emmwt_enable_social" class="emmwt-dependent" name="emmwt_enable_social" value="1" <?php checked( 1, get_option( 'emmwt_enable_social', 0 ) ); ?> />
                                    <span class="emmwt-slider"></span>
                                </label>
                            </td>
                        </tr>
                    </table>
                    
                    <div id="emmwt-social-wrapper" style="display: <?php echo get_option('emmwt_enable_social', 0) ? 'block' : 'none'; ?>; padding-top: 15px; border-top: 1px solid #f0f0f1;">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Email Address', 'easy-maintenance-timer' ); ?></th>
                                <td><input type="email" name="emmwt_social_email" class="regular-text" value="<?php echo esc_attr( get_option('emmwt_social_email', '') ); ?>" placeholder="hello@yoursite.com" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'WhatsApp Number', 'easy-maintenance-timer' ); ?></th>
                                <td>
                                    <?php $clean_wa = str_replace( array('http://', 'https://'), '', get_option('emmwt_social_wa', '') ); ?>
                                    <input type="text" name="emmwt_social_wa" class="regular-text" value="<?php echo esc_attr( $clean_wa ); ?>" placeholder="e.g. +1234567890" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Facebook URL', 'easy-maintenance-timer' ); ?></th>
                                <td><input type="url" name="emmwt_social_fb" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_fb', '') ); ?>" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'X (Twitter) URL', 'easy-maintenance-timer' ); ?></th>
                                <td><input type="url" name="emmwt_social_tw" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_tw', '') ); ?>" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'LinkedIn URL', 'easy-maintenance-timer' ); ?></th>
                                <td><input type="url" name="emmwt_social_li" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_li', '') ); ?>" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Instagram URL', 'easy-maintenance-timer' ); ?></th>
                                <td><input type="url" name="emmwt_social_ig" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_ig', '') ); ?>" /></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tab-css" class="emmwt-tab-pane" style="display: none;">
                <div class="emmwt-card">
                    <h3><?php esc_html_e( 'Custom CSS & Scripts', 'easy-maintenance-timer' ); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Custom Styles', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <textarea name="emmwt_custom_css" class="large-text" rows="6" placeholder="body.emmwt-maintenance-mode { background-color: #000; }"><?php echo esc_textarea( get_option( 'emmwt_custom_css', '' ) ); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Custom Tracking Scripts', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <textarea name="emmwt_custom_scripts" class="large-text" rows="5" placeholder="<?php esc_attr_e( '<script>...your tracking code...</script>', 'easy-maintenance-timer' ); ?>"><?php echo esc_textarea( get_option( 'emmwt_custom_scripts', '' ) ); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="tab-subscribers" class="emmwt-tab-pane" style="display: none;">
                <div class="emmwt-card">
                    <h3><?php esc_html_e( 'Lead Capture (Email Subscribers)', 'easy-maintenance-timer' ); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Enable Subscription Form', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <label class="emmwt-toggle" for="emmwt_enable_subscribe">
                                    <input type="checkbox" id="emmwt_enable_subscribe" name="emmwt_enable_subscribe" value="1" <?php checked( 1, get_option( 'emmwt_enable_subscribe', 0 ) ); ?> />
                                    <span class="emmwt-slider"></span>
                                </label>
                            </td>
                        </tr>
                    </table>

                    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e2e4e7;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0;"><?php esc_html_e( 'Subscribers List', 'easy-maintenance-timer' ); ?></h4>
                        <?php if ( ! empty( $subscribers ) ) : ?>
                            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=emmwt_export_csv' ), 'emmwt_export_nonce' ) ); ?>" class="button button-primary">
                                <span class="dashicons dashicons-download" style="vertical-align: middle; margin-top: -17px;"></span> 
                                <span style="vertical-align: middle;"><?php esc_html_e( 'Export CSV', 'easy-maintenance-timer' ); ?></span>
                            </a>
                        <?php else : ?>
                            <button type="button" class="button button-secondary" disabled>
                                <span class="dashicons dashicons-download" style="vertical-align: middle; margin-top: -17px;"></span> 
                                <span style="vertical-align: middle;"><?php esc_html_e( 'Export CSV', 'easy-maintenance-timer' ); ?></span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th style="width: 60px;"><?php esc_html_e( 'ID', 'easy-maintenance-timer' ); ?></th>
                                <th><?php esc_html_e( 'Email Address', 'easy-maintenance-timer' ); ?></th>
                                <th><?php esc_html_e( 'Date Subscribed', 'easy-maintenance-timer' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty( $subscribers ) ) : ?>
                                <?php foreach ( $subscribers as $sub ) : ?>
                                    <tr>
                                        <td><?php echo esc_html( $sub->id ); ?></td>
                                        <td><strong><?php echo esc_html( $sub->email ); ?></strong></td>
                                        <td><?php echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $sub->subscribed_at ) ) ); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 20px;">
                                        <?php esc_html_e( 'No subscribers yet.', 'easy-maintenance-timer' ); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tab-support" class="emmwt-tab-pane" style="display: none;">
                <div class="emmwt-card" style="border-left: 4px solid #2271b1; padding: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1; min-width: 300px;">
                        <h3 style="margin-top: 0; font-size: 1.1em;"><?php esc_html_e( 'Need Help or Want to Contribute?', 'easy-maintenance-timer' ); ?></h3>
                        <p style="margin: 5px 0 0; color: #50575e;">
                            <?php esc_html_e( 'If you encounter any issues, have a feature request, or want to review the code, check out our repository or reach out directly!', 'easy-maintenance-timer' ); ?>
                        </p>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <a href="<?php echo esc_url( 'https://github.com/abdulnasir1995/easy-maintenance-timer' ); ?>" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                            <span class="dashicons dashicons-editor-code" style="vertical-align: middle; margin-top: -17px;"></span> 
                            <span style="vertical-align: middle;"><?php esc_html_e( 'GitHub Repository', 'easy-maintenance-timer' ); ?></span>
                        </a>
                    </div>
                </div>

                <div class="emmwt-card">
                    <h3><?php esc_html_e( 'Submit a Support Ticket', 'easy-maintenance-timer' ); ?></h3>
                    <div id="emmwt-support-notice" style="display:none; padding:10px; margin: 15px 0; border-left:4px solid;"></div>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="emmwt_support_type"><?php esc_html_e( 'Type of Inquiry', 'easy-maintenance-timer' ); ?></label></th>
                            <td>
                                <select id="emmwt_support_type" class="regular-text">
                                    <option value="Bug Report"><?php esc_html_e( 'Bug Report', 'easy-maintenance-timer' ); ?></option>
                                    <option value="Feature Request"><?php esc_html_e( 'Feature Request', 'easy-maintenance-timer' ); ?></option>
                                    <option value="General Support"><?php esc_html_e( 'General Support', 'easy-maintenance-timer' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="emmwt_support_message"><?php esc_html_e( 'Your Message', 'easy-maintenance-timer' ); ?></label></th>
                            <td>
                                <textarea id="emmwt_support_message" class="large-text" rows="5" placeholder="<?php esc_attr_e( 'Please describe your issue or feature request in detail...', 'easy-maintenance-timer' ); ?>"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <button type="button" id="emmwt_submit_support" class="button button-primary">
                                    <span class="dashicons dashicons-email-alt" style="vertical-align: middle; margin-top: -17px;"></span> 
                                    <span style="vertical-align: middle;"><?php esc_html_e( 'Send Message', 'easy-maintenance-timer' ); ?></span>
                                </button>
                                <span id="emmwt-support-spinner" class="spinner" style="float: none; margin-top: 4px;"></span>
                            </td>
                        </tr>
                    </table>
                    <?php wp_nonce_field( 'emmwt_support_nonce', 'emmwt_support_nonce_field' ); ?>
                </div>
            </div>

            <!-- Premium Sticky Save Bar -->
            <div class="emmwt-sticky-footer" style="position: sticky; bottom: 0; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(8px); border-top: 1px solid #c3c4c7; box-shadow: 0 -4px 20px rgba(0,0,0,0.06); padding: 15px 20px; margin: 30px -20px -20px -20px; z-index: 999; display: flex; justify-content: space-between; align-items: center;">
                <div style="color: #646970; font-size: 13px;">
                    <span class="dashicons dashicons-info" style="vertical-align: middle; margin-top: -3px; font-size: 18px;"></span>
                    <span style="vertical-align: middle; margin-left: 5px;"><?php esc_html_e( 'Changes take effect immediately after saving.', 'easy-maintenance-timer' ); ?></span>
                </div>
                <div>
                    <?php 
                    submit_button( 
                        __( 'Save All Changes', 'easy-maintenance-timer' ), 
                        'primary', 
                        'submit', 
                        false, 
                        array( 'style' => 'background: #2271b1; border: none; box-shadow: 0 4px 12px rgba(34,113,177,0.3); border-radius: 6px; padding: 0 35px; font-size: 15px; font-weight: 500; height: 42px; line-height: 42px; cursor: pointer; transition: all 0.3s ease;' ) 
                    ); 
                    ?>
                </div>
            </div>

        </form>
    </div>
    <?php
}