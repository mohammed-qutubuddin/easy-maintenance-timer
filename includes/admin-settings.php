<?php
/**
 * Add settings page to WP Admin Menu.
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
    
    // New Feature: Bypass Roles
    register_setting( 'emmwt_settings_group', 'emmwt_bypass_roles', [ 'sanitize_callback' => 'emmwt_sanitize_array' ] );

    // New Feature: Social & Contact
    register_setting( 'emmwt_settings_group', 'emmwt_enable_social', [ 'sanitize_callback' => 'emmwt_sanitize_checkbox' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_email', [ 'sanitize_callback' => 'sanitize_email' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_fb', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_tw', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_wa', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_li', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_social_ig', [ 'sanitize_callback' => 'esc_url_raw' ] );
    register_setting( 'emmwt_settings_group', 'emmwt_delete_on_uninstall', [ 'sanitize_callback' => 'emmwt_sanitize_checkbox' ] );
}
add_action( 'admin_init', 'emmwt_register_settings' );

function emmwt_sanitize_checkbox( $value ) {
    return ( $value === '1' ) ? '1' : '0';
}

function emmwt_sanitize_array( $value ) {
    return is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : [];
}

function emmwt_flush_caches_on_toggle( $old_value, $value, $option ) {
    if ( $old_value !== $value ) {
        if ( function_exists( 'rocket_clean_domain' ) ) rocket_clean_domain();
        
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        if ( has_action( 'litespeed_purge_all' ) ) do_action( 'litespeed_purge_all' );
        
        if ( function_exists( 'w3tc_flush_all' ) ) w3tc_flush_all();
        wp_cache_flush();
    }
}
add_action( 'update_option_emmwt_enabled', 'emmwt_flush_caches_on_toggle', 10, 3 );

/**
 * Render the plugin settings page.
 */
function emmwt_settings_page_callback() {
    $default_date = gmdate( 'Y-m-d H:i', strtotime( '+1 day' ) );
    
    // Values
    $value_msg   = (string) get_option( 'emmwt_maint_message', 'Site Under Maintenance. Please check back soon.' );
    $value_logo  = (string) get_option( 'emmwt_logo_url', '' );
    $value_bg    = (string) get_option( 'emmwt_bg_url', '' ); 
    $value_date  = (string) get_option( 'emmwt_countdown_date', $default_date );
    $saved_roles = (array) get_option( 'emmwt_bypass_roles', ['editor'] );

    $bypass_url  = add_query_arg( 'emmwt_bypass', 'true', site_url() );
    
    // Get all WP roles for the checkboxes
    global $wp_roles;
    if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
    $all_roles = $wp_roles->get_names();
    ?>
    
    <div class="wrap emmwt-wrap">
        <h2><?php esc_html_e( 'Easy Maintenance Mode Settings', 'easy-maintenance-timer' ); ?></h2>
        
        <form method="post" action="options.php">
            <?php settings_fields( 'emmwt_settings_group' ); ?>

            <div class="emmwt-card">
                <h3><?php esc_html_e( '1. General Settings', 'easy-maintenance-timer' ); ?></h3>
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
                        <th scope="row"><?php esc_html_e( 'Countdown End Date/Time', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="text" id="emmwt_datepicker" name="emmwt_countdown_date" class="emmwt-dependent" value="<?php echo esc_attr( $value_date ); ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Maintenance Message', 'easy-maintenance-timer' ); ?></th>
                        <td><input type="text" name="emmwt_maint_message" class="emmwt-dependent regular-text" value="<?php echo esc_attr( $value_msg ); ?>" /></td>
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
                        <th scope="row"><?php esc_html_e( 'Clean Data on Uninstall', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_delete_on_uninstall">
                                <input type="checkbox" id="emmwt_delete_on_uninstall" name="emmwt_delete_on_uninstall" value="1" <?php checked( 1, get_option( 'emmwt_delete_on_uninstall', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <span class="emmwt-toggle-label"><?php esc_html_e( 'ON/OFF', 'easy-maintenance-timer' ); ?></span>
                            <p class="description"><?php esc_html_e( 'If enabled, all plugin settings will be permanently deleted from your database if you delete the plugin.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="emmwt-card">
                <h3><?php esc_html_e( '2. Access Control (Bypass)', 'easy-maintenance-timer' ); ?></h3>
                
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
                                if ( $role_slug === 'administrator' ) continue; // Skip admin
                                $checked = in_array( $role_slug, $saved_roles ) ? 'checked' : '';
                                echo '<label style="display:block; margin-bottom:5px;">';
                                // PCP FIX: Output Escaped
                                echo '<input type="checkbox" class="emmwt-dependent" name="emmwt_bypass_roles[]" value="' . esc_attr( $role_slug ) . '" ' . esc_attr( $checked ) . '>';
                                echo ' ' . esc_html( translate_user_role( $role_name ) );
                                echo '</label>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="emmwt-card">
                <h3><?php esc_html_e( '3. Social & Contact Links', 'easy-maintenance-timer' ); ?></h3>
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
                                <?php 
                                // Strip old HTTP data stuck in the database
                                $clean_wa = str_replace( array('http://', 'https://'), '', get_option('emmwt_social_wa', '') ); 
                                ?>
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

            <p class="submit">
                <?php submit_button( '', 'primary', 'submit', false ); ?>
            </p>
        </form>
    </div>
    <?php
}

function emmwt_admin_settings_enqueue( $hook ) {
    if ( 'settings_page_emmwt_settings' !== $hook ) return;

    wp_enqueue_media();
    
    // PCP FIX: External CDN scripts are not allowed. Download these and place them in assets/css and assets/js
    wp_enqueue_style( 'flatpickr-css', EMMWT_PLUGIN_URL . 'assets/css/flatpickr.min.css', array(), '4.6.13' );
    wp_enqueue_script( 'flatpickr-js', EMMWT_PLUGIN_URL . 'assets/js/flatpickr.min.js', array('jquery'), '4.6.13', true );
    
    wp_enqueue_style( 'emmwt-admin-css', EMMWT_PLUGIN_URL . 'assets/css/admin.css', array(), EMMWT_VERSION );
    wp_enqueue_script( 'emmwt-admin-settings-js', EMMWT_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'flatpickr-js' ), EMMWT_VERSION, true );

    wp_localize_script( 'emmwt-admin-settings-js', 'emmwt_admin', array(
        'title_logo' => esc_html__( 'Select or Upload Logo', 'easy-maintenance-timer' ),
        'title_bg'   => esc_html__( 'Select or Upload Background', 'easy-maintenance-timer' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'emmwt_admin_settings_enqueue' );