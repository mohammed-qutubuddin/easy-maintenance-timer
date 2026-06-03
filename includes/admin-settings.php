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
        'sanitize_callback' => 'sanitize_text_field', 
    ]);

    register_setting( 'emmwt_settings_group', 'emmwt_logo_url', [
        'sanitize_callback' => 'esc_url_raw',
    ]);

    register_setting( 'emmwt_settings_group', 'emmwt_bg_url', [
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
    
    // Default logo logic: If empty, show the default URL in the field
    $saved_logo = get_option( 'emmwt_logo_url', '' );
    $value_logo = ! empty( $saved_logo ) ? $saved_logo : $default_logo;
    
    $value_bg   = get_option( 'emmwt_bg_url', '' ); 
    $value_date = get_option( 'emmwt_countdown_date', $default_date );
    ?>
    
    <style>
        /* Modern Plugin UI Styles */
        .emmwt-wrap {
            max-width: 800px;
            margin-top: 20px;
        }
        .emmwt-card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            padding: 30px;
            margin-top: 15px;
        }
        .emmwt-card h2 {
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f1;
            margin-bottom: 25px;
        }
        /* CSS Toggle Switch */
        .emmwt-toggle {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
            vertical-align: middle;
        }
        .emmwt-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .emmwt-slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .emmwt-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .emmwt-slider {
            background-color: #2271b1; /* WP Blue */
        }
        input:checked + .emmwt-slider:before {
            transform: translateX(22px);
        }
        .emmwt-toggle-label {
            margin-left: 10px;
            vertical-align: middle;
            font-weight: 600;
        }
        /* Improve input spacing */
        .emmwt-card .form-table th {
            width: 250px;
            font-weight: 600;
        }
        .emmwt-card input[type="text"], 
        .emmwt-card input[type="datetime-local"] {
            width: 100%;
            max-width: 400px;
        }
        .emmwt-logo-preview {
            margin-top: 10px;
            max-width: 120px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px;
            display: block;
        }
    </style>

    <div class="wrap emmwt-wrap">
        <div class="emmwt-card">
            <h2><?php echo esc_html__( 'Easy Maintenance Mode Settings', 'easy-maintenance-timer' ); ?></h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'emmwt_settings_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Enable Maintenance Mode', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enabled">
                                <input type="checkbox" id="emmwt_enabled" name="emmwt_enabled" value="1" <?php checked( 1, get_option( 'emmwt_enabled', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <span class="emmwt-toggle-label"><?php echo esc_html__( 'ON/OFF', 'easy-maintenance-timer' ); ?></span>
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
                                   class="emmwt-dependent regular-text" 
                                   value="<?php echo esc_attr( $value_msg ); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Logo URL', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:10px; align-items:flex-start;">
                                <div>
                                    <input type="text" 
                                           class="emmwt-dependent regular-text" 
                                           id="emmwt_logo_url" 
                                           name="emmwt_logo_url" 
                                           value="<?php echo esc_url( $value_logo ); ?>" />
                                    <p class="description"><?php echo esc_html__( 'Leave empty to use the default plugin logo.', 'easy-maintenance-timer' ); ?></p>
                                </div>
                                <button class="emmwt-dependent button" type="button" id="emmwt_logo_upload">
                                    <?php echo esc_html__( 'Upload / Select', 'easy-maintenance-timer' ); ?>
                                </button>
                            </div>
                            <?php if ( $value_logo ) : ?>
                                <img src="<?php echo esc_url( $value_logo ); ?>" class="emmwt-logo-preview" alt="Logo Preview" />
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Background Image (Optional)', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:10px; align-items:flex-start;">
                                <div>
                                    <input type="text" 
                                           class="emmwt-dependent regular-text" 
                                           id="emmwt_bg_url" 
                                           name="emmwt_bg_url" 
                                           value="<?php echo esc_url( $value_bg ); ?>" />
                                    <p class="description"><?php echo esc_html__( 'Leave blank to use the default animated background.', 'easy-maintenance-timer' ); ?></p>
                                </div>
                                <button class="emmwt-dependent button" type="button" id="emmwt_bg_upload">
                                    <?php echo esc_html__( 'Upload / Select', 'easy-maintenance-timer' ); ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <?php submit_button( '', 'primary', 'submit', false ); ?>
                </p>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Enqueue admin scripts.
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
        '1.0.1', 
        true
    );

    wp_localize_script( 'emmwt-admin-script', 'emmwt_admin', array(
        'title_logo' => __( 'Select or Upload Logo', 'easy-maintenance-timer' ),
        'title_bg'   => __( 'Select or Upload Background', 'easy-maintenance-timer' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'emmwt_admin_enqueue' );