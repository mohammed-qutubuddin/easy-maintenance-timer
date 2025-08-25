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

/**
 * Register plugin settings.
 */
function emmwt_register_settings() {
    register_setting( 'emmwt_settings_group', 'emmwt_countdown_date', [
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_setting( 'emmwt_settings_group', 'emmwt_maint_message', [
        'sanitize_callback' => 'sanitize_text_field', // Change to wp_kses_post if HTML allowed
    ]);

    register_setting( 'emmwt_settings_group', 'emmwt_logo_url', [
        'sanitize_callback' => 'esc_url_raw',
    ]);

    register_setting( 'emmwt_settings_group', 'emmwt_enabled', [
        'sanitize_callback' => 'emmwt_sanitize_checkbox',
    ]);
}
add_action( 'admin_init', 'emmwt_register_settings' );

/**
 * Sanitize checkbox values.
 */
function emmwt_sanitize_checkbox( $value ) {
    return ( $value === '1' ) ? '1' : '0';
}

/**
 * Render the plugin settings page.
 */
function emmwt_settings_page_callback() {
    $default_msg  = esc_html__( 'Site Under Maintenance. Please check back soon.', 'easy-maintenance-timer' );
    $default_logo = trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'assets/img/default-logo.jpg';
    $default_date = gmdate( 'Y-m-d\TH:i', strtotime( '+1 day' ) );

    $value_msg  = get_option( 'emmwt_maint_message', $default_msg );
    $value_logo = get_option( 'emmwt_logo_url', $default_logo );
    $value_date = get_option( 'emmwt_countdown_date', $default_date );
    ?>
    <div class="wrap">
        <h2><?php echo esc_html__( 'Easy Maintenance Mode Settings', 'easy-maintenance-timer' ); ?></h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'emmwt_settings_group' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Enable Maintenance Mode', 'easy-maintenance-timer' ); ?></th>
                    <td>
                        <label for="emmwt_enabled">
                            <input type="checkbox" id="emmwt_enabled" name="emmwt_enabled" value="1" <?php checked( 1, get_option( 'emmwt_enabled', 0 ) ); ?> />
                            <?php echo esc_html__( 'ON/OFF', 'easy-maintenance-timer' ); ?>
                        </label>
                    </td>
                </tr>
                <tr>                    
                    <th scope="row"><?php echo esc_html__( 'Countdown End Date/Time', 'easy-maintenance-timer' ); ?></th>
                    <td>
                        <input type="datetime-local" 
                               name="emmwt_countdown_date" 
                               class="emmwt-dependent"
                               min="<?php echo esc_attr( gmdate( 'Y-m-d\TH:i' ) ); ?>"
                               value="<?php echo esc_attr( $value_date ); ?>" 
                               required />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Maintenance Message', 'easy-maintenance-timer' ); ?></th>
                    <td>
                        <input type="text" 
                               name="emmwt_maint_message" 
                               class="emmwt-dependent" 
                               value="<?php echo esc_attr( $value_msg ); ?>" 
                               size="50" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Logo URL', 'easy-maintenance-timer' ); ?></th>
                    <td>
                        <input type="text" 
                               class="emmwt-dependent" 
                               id="emmwt_logo_url" 
                               name="emmwt_logo_url" 
                               value="<?php echo esc_url( $value_logo ); ?>" />
                        <button class="emmwt-dependent button" type="button" id="emmwt_logo_upload">
                            <?php echo esc_html__( 'Upload / Select', 'easy-maintenance-timer' ); ?>
                        </button>
                        <br>
                        <small><?php echo esc_html__( 'Upload your custom logo.', 'easy-maintenance-timer' ); ?></small>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Enqueue admin scripts (only for settings page).
 */
function emmwt_admin_enqueue( $hook ) {
    if ( 'settings_page_emmwt_settings' !== $hook ) {
        return;
    }

    wp_enqueue_media();

    wp_enqueue_script(
        'emmwt-admin-script',
        trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'assets/js/admin.js',
        array( 'jquery' ),
        '1.0.0',
        true
    );

    // Localize (PHP → JS data)
    wp_localize_script( 'emmwt-admin-script', 'emmwt_admin', array(
        'title' => __( 'Select or Upload Logo', 'easy-maintenance-timer' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'emmwt_admin_enqueue' );

