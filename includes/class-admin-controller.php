<?php
namespace EasyMaintenanceTimer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Controller {

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'register_settings_page' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
        add_action( 'admin_init', [ __CLASS__, 'handle_export_import' ] );
        add_action( 'admin_post_emmwt_export_settings', [ __CLASS__, 'handle_export_action' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_settings_enqueue' ] );
        add_action( 'admin_bar_menu', [ __CLASS__, 'add_admin_bar_node' ], 100 );
        add_action( 'admin_post_emmwt_toggle_maintenance', [ __CLASS__, 'handle_admin_bar_toggle' ] );
        add_action( 'admin_notices', [ __CLASS__, 'display_review_notice' ] );
        add_action( 'wp_ajax_emmwt_dismiss_review', [ __CLASS__, 'handle_dismiss_review' ] );
    }

    public static function register_settings_page() {
        add_menu_page(
            esc_html__( 'Easy Maintenance Settings', 'easy-maintenance-timer' ),
            esc_html__( 'Easy Maintenance', 'easy-maintenance-timer' ),
            'manage_options',
            'emmwt_settings',
            [ __CLASS__, 'settings_page_callback' ],
            'dashicons-clock',
            80
        );
    }

    public static function register_settings() {
        register_setting( 'emmwt_settings_group', 'emmwt_countdown_date', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_maint_message', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_logo_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bg_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enabled', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bypass_roles', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_array' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_social', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_social_email', [ 'sanitize_callback' => 'sanitize_email' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_social_fb', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_social_tw', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_social_wa', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_social_li', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_social_ig', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_delete_on_uninstall', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bypass_ips', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_ips' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_block_rest_api', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_custom_css', [ 'sanitize_callback' => 'wp_strip_all_tags' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_maint_description', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_msg_color', [ 'sanitize_callback' => 'sanitize_hex_color' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_desc_color', [ 'sanitize_callback' => 'sanitize_hex_color' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_font_family', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_seo_title', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_seo_meta_desc', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_status_type', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_custom_scripts', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_scripts' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bg_overlay_color', [ 'sanitize_callback' => 'sanitize_hex_color' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bg_overlay_opacity', [ 'sanitize_callback' => 'absint' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_subscribe', [ 'sanitize_callback' => 'absint' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bg_animation', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_notify_admin', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );

        // New Premium Settings
        register_setting( 'emmwt_settings_group', 'emmwt_bypass_password', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_gdpr', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_gdpr_text', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_progress', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_progress_percentage', [ 'sanitize_callback' => 'absint' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_font_google', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bg_video_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_share', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_page_exclusions', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );

        // Automation, Schedules, Contact Form, and Redirect Settings
        register_setting( 'emmwt_settings_group', 'emmwt_redirect_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_schedule_start', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_schedule_end', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_contact', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_contact_email', [ 'sanitize_callback' => 'sanitize_email' ] );

        // Layout presets and Traffic counter
        register_setting( 'emmwt_settings_group', 'emmwt_layout_preset', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_views_count', [ 'sanitize_callback' => 'absint' ] );

        // v1.04 Advanced Features
        register_setting( 'emmwt_settings_group', 'emmwt_bypass_bots', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_webhook_url', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_subscriber_email', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
        register_setting( 'emmwt_settings_group', 'emmwt_subscriber_email_subject', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_subscriber_email_body', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_og_title', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_og_desc', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_og_image', [ 'sanitize_callback' => 'esc_url_raw' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_bypass_secret_token', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'emmwt_settings_group', 'emmwt_enable_credit', [ 'sanitize_callback' => [ __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ] ] );
    }

    public static function admin_settings_enqueue( $hook ) {
        if ( 'toplevel_page_emmwt_settings' !== $hook ) return;

        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'flatpickr-css', EMMWT_PLUGIN_URL . 'assets/css/flatpickr.min.css', array(), '4.6.13' );
        wp_enqueue_script( 'flatpickr-js', EMMWT_PLUGIN_URL . 'assets/js/flatpickr.min.js', array('jquery'), '4.6.13', true );
        wp_enqueue_style( 'emmwt-admin-css', EMMWT_PLUGIN_URL . 'assets/css/admin.css', array(), EMMWT_VERSION );
        wp_enqueue_script( 'emmwt-admin-settings-js', EMMWT_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'flatpickr-js', 'wp-color-picker' ), EMMWT_VERSION, true );
        
        wp_localize_script( 'emmwt-admin-settings-js', 'emmwt_admin', array(
            'title_logo' => esc_html__( 'Select or Upload Logo', 'easy-maintenance-timer' ),
            'title_bg'   => esc_html__( 'Select or Upload Background', 'easy-maintenance-timer' ),
            'title_og'   => esc_html__( 'Select or Upload Social Preview Image', 'easy-maintenance-timer' ),
        ) );
    }

    public static function settings_page_callback() {
        $bg_animation = (string) get_option( 'emmwt_bg_animation', 'none' );
        $bg_overlay_color   = (string) get_option( 'emmwt_bg_overlay_color', '#000000' );
        $bg_overlay_opacity = (int) get_option( 'emmwt_bg_overlay_opacity', 50 ); 
        $default_date = gmdate( 'Y-m-d H:i', strtotime( '+1 day' ) );
        $saved_ips  = (string) get_option( 'emmwt_bypass_ips', '' );
        
        $current_ip = '';
        if ( class_exists( __NAMESPACE__ . '\\Frontend_Controller' ) ) {
            $current_ip = Frontend_Controller::get_visitor_ip();
        }

        $value_msg   = (string) get_option( 'emmwt_maint_message', 'Site Under Maintenance. Please check back soon.' );
        $value_logo  = (string) get_option( 'emmwt_logo_url', '' );
        $value_bg    = (string) get_option( 'emmwt_bg_url', '' ); 
        $value_date  = (string) get_option( 'emmwt_countdown_date', $default_date );
        $saved_roles = (array) get_option( 'emmwt_bypass_roles', ['editor'] );
        $value_desc = (string) get_option( 'emmwt_maint_description', '' );
        $msg_color  = (string) get_option( 'emmwt_msg_color', '#000000' );
        $desc_color = (string) get_option( 'emmwt_desc_color', '#50575e' );
        $font_family = (string) get_option( 'emmwt_font_family', 'system' );
        $secret_token = class_exists( __NAMESPACE__ . '\\Frontend_Controller' ) ? Frontend_Controller::get_bypass_secret_token() : '';
        $bypass_url  = add_query_arg( 'emmwt_bypass', $secret_token, site_url() );
        $preview_url = add_query_arg( 'emmwt_preview', 'true', site_url() ); 
        $seo_title = (string) get_option( 'emmwt_seo_title', '' );
        $seo_desc  = (string) get_option( 'emmwt_seo_meta_desc', '' );
        $status_type = (string) get_option( 'emmwt_status_type', 'maintenance' );

        // Fetch Subscribers
        global $wpdb;
        $table_name = $wpdb->prefix . 'emmwt_subscribers';
        $subscribers = [];
        
        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );
        
        $total_subscribers = 0;
        if ( $table_exists === $table_name ) {
            // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $total_subscribers = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name}" );
            $cache_key = 'emmwt_subscribers_list';
            $subscribers = wp_cache_get( $cache_key, 'emmwt' );
            
            if ( false === $subscribers ) {
                // Limit list to latest 100 entries for optimal dashboard memory performance
                // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                $subscribers = $wpdb->get_results( "SELECT id, email, subscribed_at FROM {$table_name} ORDER BY subscribed_at DESC LIMIT 100" );
                wp_cache_set( $cache_key, $subscribers, 'emmwt', HOUR_IN_SECONDS );
            }
        }
        
        global $wp_roles;
        if ( ! isset( $wp_roles ) ) $wp_roles = new \WP_Roles();
        $all_roles = $wp_roles->get_names();

        // Load visual settings page view
        $view_file = EMMWT_PLUGIN_DIR . 'templates/settings-page-view.php';
        if ( file_exists( $view_file ) ) {
            include $view_file;
        }
    }

    public static function handle_export_import() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // --- Import settings ---
        if ( isset( $_POST['emmwt_import_settings'] ) && check_admin_referer( 'emmwt_import_nonce_action', 'emmwt_import_nonce' ) ) {
            if ( empty( $_FILES['emmwt_import_file']['tmp_name'] ) || empty( $_FILES['emmwt_import_file']['name'] ) ) {
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_error', __( 'Please choose a configuration file to upload.', 'easy-maintenance-timer' ), 'error' );
                return;
            }

            $tmp_name = sanitize_text_field( wp_unslash( $_FILES['emmwt_import_file']['tmp_name'] ) );
            $filename = sanitize_file_name( wp_unslash( $_FILES['emmwt_import_file']['name'] ) );
            $filesize = isset( $_FILES['emmwt_import_file']['size'] ) ? (int) $_FILES['emmwt_import_file']['size'] : 0;
            $file_err = isset( $_FILES['emmwt_import_file']['error'] ) ? (int) $_FILES['emmwt_import_file']['error'] : UPLOAD_ERR_OK;

            // 1. Verify standard file upload integrity
            if ( $file_err !== UPLOAD_ERR_OK || ! is_uploaded_file( $tmp_name ) ) {
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_error', __( 'Upload validation failed. Please try again.', 'easy-maintenance-timer' ), 'error' );
                return;
            }

            // 2. Validate file size (max 1MB)
            if ( $filesize > 1048576 ) {
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_error', __( 'File exceeds maximum allowed size (1 MB).', 'easy-maintenance-timer' ), 'error' );
                return;
            }

            // 3. Validate JSON file extension
            $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
            if ( 'json' !== $ext ) {
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_error', __( 'Invalid file format. Only .json configuration files are permitted.', 'easy-maintenance-timer' ), 'error' );
                return;
            }

            // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
            $json = file_get_contents( $tmp_name );
            if ( false === $json ) {
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_error', __( 'Could not read uploaded file content.', 'easy-maintenance-timer' ), 'error' );
                return;
            }

            $settings = json_decode( $json, true );
            if ( ! is_array( $settings ) || empty( $settings ) ) {
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_error', __( 'Invalid or corrupted configuration JSON.', 'easy-maintenance-timer' ), 'error' );
                return;
            }

            // 4. Strict Whitelist of Allowed Setting Keys and their designated sanitizers
            $whitelist_sanitizers = array(
                'emmwt_enabled'                  => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_status_type'              => 'sanitize_text_field',
                'emmwt_layout_preset'            => 'sanitize_text_field',
                'emmwt_countdown_date'           => 'sanitize_text_field',
                'emmwt_notify_admin'             => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_maint_message'            => 'sanitize_text_field',
                'emmwt_maint_description'        => 'sanitize_textarea_field',
                'emmwt_msg_color'                => 'sanitize_hex_color',
                'emmwt_desc_color'               => 'sanitize_hex_color',
                'emmwt_font_family'              => 'sanitize_text_field',
                'emmwt_font_google'              => 'sanitize_text_field',
                'emmwt_logo_url'                 => 'esc_url_raw',
                'emmwt_bg_url'                   => 'esc_url_raw',
                'emmwt_bg_video_url'             => 'esc_url_raw',
                'emmwt_bg_overlay_color'         => 'sanitize_hex_color',
                'emmwt_bg_overlay_opacity'       => 'absint',
                'emmwt_bg_animation'             => 'sanitize_text_field',
                'emmwt_enable_progress'          => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_progress_percentage'      => 'absint',
                'emmwt_seo_title'                => 'sanitize_text_field',
                'emmwt_seo_meta_desc'            => 'sanitize_textarea_field',
                'emmwt_custom_css'               => 'wp_strip_all_tags',
                'emmwt_custom_scripts'           => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_scripts' ),
                'emmwt_bypass_roles'             => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_array' ),
                'emmwt_bypass_ips'               => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_ips' ),
                'emmwt_bypass_password'          => 'sanitize_text_field',
                'emmwt_page_exclusions'          => 'sanitize_textarea_field',
                'emmwt_block_rest_api'           => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_enable_social'            => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_social_email'             => 'sanitize_email',
                'emmwt_social_fb'                => 'esc_url_raw',
                'emmwt_social_tw'                => 'esc_url_raw',
                'emmwt_social_wa'                => 'esc_url_raw',
                'emmwt_social_li'                => 'esc_url_raw',
                'emmwt_social_ig'                => 'esc_url_raw',
                'emmwt_enable_share'             => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_enable_subscribe'         => 'absint',
                'emmwt_enable_gdpr'              => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_gdpr_text'                => 'sanitize_text_field',
                'emmwt_enable_contact'           => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_contact_email'            => 'sanitize_email',
                'emmwt_redirect_url'             => 'esc_url_raw',
                'emmwt_schedule_start'           => 'sanitize_text_field',
                'emmwt_schedule_end'             => 'sanitize_text_field',
                'emmwt_bypass_bots'              => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_webhook_url'              => 'esc_url_raw',
                'emmwt_enable_subscriber_email'  => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' ),
                'emmwt_subscriber_email_subject' => 'sanitize_text_field',
                'emmwt_subscriber_email_body'    => 'sanitize_textarea_field',
                'emmwt_og_title'                 => 'sanitize_text_field',
                'emmwt_og_desc'                  => 'sanitize_textarea_field',
                'emmwt_og_image'                 => 'esc_url_raw',
                'emmwt_delete_on_uninstall'      => array( __NAMESPACE__ . '\\Database_Manager', 'sanitize_checkbox' )
            );

            $imported_count = 0;
            foreach ( $settings as $key => $value ) {
                if ( isset( $whitelist_sanitizers[ $key ] ) ) {
                    $sanitizer   = $whitelist_sanitizers[ $key ];
                    $clean_value = call_user_func( $sanitizer, $value );
                    update_option( $key, $clean_value );
                    $imported_count++;
                }
            }

            if ( $imported_count > 0 ) {
                /* translators: %d: number of imported settings */
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_success', sprintf( __( '%d settings successfully imported and verified!', 'easy-maintenance-timer' ), $imported_count ), 'updated' );
            } else {
                add_settings_error( 'emmwt_settings_group', 'emmwt_import_error', __( 'No recognized settings found in the uploaded file.', 'easy-maintenance-timer' ), 'error' );
            }
        }
    }

    public static function handle_export_action() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized access.', 'easy-maintenance-timer' ) );
        }
        check_admin_referer( 'emmwt_export_nonce_action', 'emmwt_export_nonce' );

        global $wpdb;
        // Fetch all options that start with "emmwt_"
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $results = $wpdb->get_results( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'emmwt\_%'" );
        
        $settings = array();
        foreach ( $results as $row ) {
            // Exclude secret internal bypass tokens from JSON exports
            if ( 'emmwt_bypass_secret_token' === $row->option_name ) {
                continue;
            }
            $settings[ $row->option_name ] = maybe_unserialize( $row->option_value );
        }

        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=easy-maintenance-timer-settings.json' );
        echo wp_json_encode( $settings );
        exit;
    }

    /**
     * Add quick status indicator and toggle to the WordPress Admin Bar.
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     */
    public static function add_admin_bar_node( $wp_admin_bar ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $is_enabled = (bool) get_option( 'emmwt_enabled', 0 );
        $toggle_nonce = wp_create_nonce( 'emmwt_admin_bar_toggle' );
        $current_url  = is_admin() ? admin_url( 'admin.php?page=emmwt_settings' ) : home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );

        $toggle_url = add_query_arg(
            array(
                'action'   => 'emmwt_toggle_maintenance',
                '_wpnonce' => $toggle_nonce,
                'redirect' => rawurlencode( $current_url ),
            ),
            admin_url( 'admin-post.php' )
        );

        $status_label = $is_enabled ? esc_html__( 'Maintenance ON', 'easy-maintenance-timer' ) : esc_html__( 'Maintenance OFF', 'easy-maintenance-timer' );
        $status_color = $is_enabled ? '#ef4444' : '#22c55e';

        $title = sprintf(
            '<span class="ab-icon dashicons dashicons-clock" style="color:%s;font-size:16px;line-height:26px;"></span><span class="ab-label" style="font-weight:600;color:%s;">%s</span>',
            esc_attr( $status_color ),
            esc_attr( $status_color ),
            esc_html( $status_label )
        );

        $wp_admin_bar->add_node( array(
            'id'    => 'emmwt_maintenance_bar',
            'title' => $title,
            'href'  => admin_url( 'admin.php?page=emmwt_settings' ),
            'meta'  => array(
                'title' => $is_enabled ? esc_attr__( 'Maintenance Mode is currently active', 'easy-maintenance-timer' ) : esc_attr__( 'Site is currently live to the public', 'easy-maintenance-timer' ),
            ),
        ) );

        $wp_admin_bar->add_node( array(
            'parent' => 'emmwt_maintenance_bar',
            'id'     => 'emmwt_bar_toggle',
            'title'  => $is_enabled ? esc_html__( '🟢 Turn Site Live (Disable)', 'easy-maintenance-timer' ) : esc_html__( '🔴 Turn Maintenance ON', 'easy-maintenance-timer' ),
            'href'   => $toggle_url,
        ) );

        $wp_admin_bar->add_node( array(
            'parent' => 'emmwt_maintenance_bar',
            'id'     => 'emmwt_bar_preview',
            'title'  => esc_html__( '👁️ Preview Maintenance Screen', 'easy-maintenance-timer' ),
            'href'   => add_query_arg( 'emmwt_preview', 'true', home_url() ),
            'meta'   => array( 'target' => '_blank' ),
        ) );

        $wp_admin_bar->add_node( array(
            'parent' => 'emmwt_maintenance_bar',
            'id'     => 'emmwt_bar_settings',
            'title'  => esc_html__( '⚙️ Maintenance Settings', 'easy-maintenance-timer' ),
            'href'   => admin_url( 'admin.php?page=emmwt_settings' ),
        ) );
    }

    /**
     * Handle quick-toggle action from the Admin Bar.
     */
    public static function handle_admin_bar_toggle() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized access.', 'easy-maintenance-timer' ) );
        }

        check_admin_referer( 'emmwt_admin_bar_toggle' );

        $current_state = (int) get_option( 'emmwt_enabled', 0 );
        $new_state     = $current_state ? 0 : 1;

        update_option( 'emmwt_enabled', $new_state );

        if ( class_exists( __NAMESPACE__ . '\\Database_Manager' ) ) {
            Database_Manager::purge_known_caches();
        }

        $redirect = ! empty( $_GET['redirect'] ) ? esc_url_raw( wp_unslash( $_GET['redirect'] ) ) : admin_url( 'admin.php?page=emmwt_settings' );
        wp_safe_redirect( $redirect );
        exit;
    }

    /**
     * Display a non-intrusive 5-star review request on the plugin settings screen.
     */
    public static function display_review_notice() {
        $screen = get_current_screen();
        if ( ! $screen || 'toplevel_page_emmwt_settings' !== $screen->id ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( (int) get_option( 'emmwt_review_dismissed', 0 ) === 1 ) {
            return;
        }

        $snooze_until = (int) get_option( 'emmwt_review_snooze_until', 0 );
        if ( $snooze_until > time() ) {
            return;
        }

        $installed = (int) get_option( 'emmwt_installed_time', 0 );
        if ( ! $installed ) {
            $installed = time();
            update_option( 'emmwt_installed_time', $installed );
        }

        $is_enabled  = (bool) get_option( 'emmwt_enabled', 0 );
        $days_active = ( time() - $installed ) / DAY_IN_SECONDS;

        // Show after 2 days of installation, OR if maintenance mode is enabled
        if ( $days_active < 2 && ! $is_enabled ) {
            return;
        }

        $review_url = 'https://wordpress.org/support/plugin/easy-maintenance-timer/reviews/#new-post';
        $nonce      = wp_create_nonce( 'emmwt_review_nonce' );
        ?>
        <div id="emmwt-review-notice" class="notice notice-info is-dismissible" style="border-left-color: #6366f1; padding: 16px 20px; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin: 20px 0;">
            <div style="display: flex; align-items: flex-start; gap: 16px;">
                <div style="font-size: 32px; line-height: 1;">⭐</div>
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #1e293b;">
                        <?php esc_html_e( 'Loving Easy Maintenance Timer?', 'easy-maintenance-timer' ); ?>
                    </h4>
                    <p style="margin: 0 0 12px 0; font-size: 13px; color: #475569; line-height: 1.5;">
                        <?php esc_html_e( 'If this plugin is helping you build or update your website, could you spare 30 seconds to leave a 5-star rating on WordPress.org? Your review helps keep this plugin 100% free with zero bloat and no paywalls!', 'easy-maintenance-timer' ); ?>
                    </p>
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <a href="<?php echo esc_url( $review_url ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary emmwt-review-btn" data-action="dismiss" style="background: #6366f1; border-color: #4f46e5; font-weight: 600;">
                            ⭐ <?php esc_html_e( 'Leave a 5-Star Review', 'easy-maintenance-timer' ); ?>
                        </a>
                        <button type="button" class="button emmwt-review-btn" data-action="dismiss" style="color: #475569;">
                            <?php esc_html_e( 'I Already Did', 'easy-maintenance-timer' ); ?>
                        </button>
                        <button type="button" class="button emmwt-review-btn" data-action="snooze" style="color: #64748b;">
                            <?php esc_html_e( 'Maybe Later', 'easy-maintenance-timer' ); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('.emmwt-review-btn, #emmwt-review-notice .notice-dismiss').on('click', function(e) {
                var actionType = $(this).data('action') || 'dismiss';
                if ($(this).attr('target') !== '_blank') {
                    e.preventDefault();
                }
                $('#emmwt-review-notice').slideUp(200);
                $.post(ajaxurl, {
                    action: 'emmwt_dismiss_review',
                    review_action: actionType,
                    nonce: '<?php echo esc_js( $nonce ); ?>'
                });
            });
        });
        </script>
        <?php
    }

    /**
     * AJAX handler to dismiss or snooze the review prompt.
     */
    public static function handle_dismiss_review() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ), 403 );
        }

        check_ajax_referer( 'emmwt_review_nonce', 'nonce' );

        $action_type = isset( $_POST['review_action'] ) ? sanitize_key( $_POST['review_action'] ) : 'dismiss';

        if ( 'snooze' === $action_type ) {
            update_option( 'emmwt_review_snooze_until', time() + ( 7 * DAY_IN_SECONDS ) );
        } else {
            update_option( 'emmwt_review_dismissed', 1 );
        }

        wp_send_json_success();
    }
}
