<?php
namespace EasyMaintenanceTimer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Frontend_Controller {

    public static function init() {
        add_action( 'rest_authentication_errors', [ __CLASS__, 'disable_rest_api' ] );
        add_action( 'wp_head', [ __CLASS__, 'output_custom_css' ] );
        add_action( 'wp_head', [ __CLASS__, 'output_custom_scripts' ], 99 );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_frontend_assets' ] );
        add_action( 'init', [ __CLASS__, 'check_auto_live' ] );
        add_action( 'template_redirect', [ __CLASS__, 'frontend_maintenance_redirect' ] );
        add_action( 'admin_bar_menu', [ __NAMESPACE__ . '\\Admin_Controller', 'add_admin_bar_node' ], 100 );
    }

    /**
     * Retrieve or generate a cryptographically secure client bypass secret token.
     *
     * @return string
     */
    public static function get_bypass_secret_token() {
        $token = (string) get_option( 'emmwt_bypass_secret_token', '' );
        if ( empty( $token ) ) {
            $token = wp_generate_password( 32, false );
            update_option( 'emmwt_bypass_secret_token', $token );
        }
        return $token;
    }

    /**
     * Generate the expected cryptographic cookie authentication hash.
     *
     * @return string
     */
    public static function get_bypass_cookie_hash() {
        $secret = self::get_bypass_secret_token();
        return wp_hash( 'emmwt_bypass_auth_' . $secret );
    }

    /**
     * Set the authenticated bypass cookie with strict security flags.
     */
    public static function set_bypass_cookie() {
        $hash     = self::get_bypass_cookie_hash();
        $expire   = time() + ( 86400 * 7 ); // 7 days
        $secure   = is_ssl();
        $httponly = true;
        setcookie( 'emmwt_bypass_token', $hash, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, $httponly );
    }

    /**
     * Resolve visitor client IP securely.
     * Prioritizes authoritative REMOTE_ADDR and prevents client header spoofing.
     *
     * @return string
     */
    public static function get_visitor_ip() {
        // Priority 1: Cloudflare connecting IP (validated)
        if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
            $cf_ip = trim( sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) );
            if ( filter_var( $cf_ip, FILTER_VALIDATE_IP ) ) {
                return $cf_ip;
            }
        }

        // Priority 2: Direct TCP client IP (authoritative and non-spoofable)
        if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
            $remote_ip = trim( sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) );
            if ( filter_var( $remote_ip, FILTER_VALIDATE_IP ) ) {
                return $remote_ip;
            }
        }

        return '';
    }

    public static function check_bypass_access() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['emmwt_preview'] ) && $_GET['emmwt_preview'] === 'true' && current_user_can( 'manage_options' ) ) {
            return false; 
        }

        if ( current_user_can( 'manage_options' ) ) return true;

        // Verify cryptographic cookie auth hash
        if ( ! empty( $_COOKIE['emmwt_bypass_token'] ) ) {
            $expected_hash = self::get_bypass_cookie_hash();
            if ( hash_equals( $expected_hash, sanitize_text_field( wp_unslash( $_COOKIE['emmwt_bypass_token'] ) ) ) ) {
                return true;
            }
        }

        if ( is_user_logged_in() ) {
            $allowed_roles = (array) get_option( 'emmwt_bypass_roles', ['editor'] );
            $user = wp_get_current_user();
            $user_roles = (array) $user->roles;
            
            if ( ! empty( array_intersect( $allowed_roles, $user_roles ) ) ) {
                return true;
            }
        }

        $allowed_ips_string = (string) get_option( 'emmwt_bypass_ips', '' );
        if ( ! empty( $allowed_ips_string ) ) {
            $allowed_ips = array_filter( array_map( 'trim', explode( "\n", $allowed_ips_string ) ) );
            $visitor_ip  = self::get_visitor_ip();
            
            if ( in_array( $visitor_ip, $allowed_ips, true ) ) {
                return true;
            }
        }

        if ( get_option( 'emmwt_bypass_bots', 0 ) && self::is_search_bot() ) {
            return true;
        }

        return false;
    }

    public static function is_search_bot() {
        if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
            return false;
        }
        $ua = strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) );
        $bot_patterns = array(
            'googlebot',
            'bingbot',
            'slurp',
            'duckduckbot',
            'baiduspider',
            'yandexbot',
            'sogou',
            'exabot',
            'facebot',
            'facebookexternalhit',
            'ia_archiver'
        );
        foreach ( $bot_patterns as $bot ) {
            if ( strpos( $ua, $bot ) !== false ) {
                return true;
            }
        }
        return false;
    }

    public static function disable_rest_api( $result ) {
        if ( ! empty( $result ) ) return $result;

        if ( get_option( 'emmwt_enabled', 0 ) && get_option( 'emmwt_block_rest_api', 0 ) ) {
            if ( ! self::check_bypass_access() ) {
                return new \WP_Error( 'rest_forbidden', __( 'Site is currently under maintenance.', 'easy-maintenance-timer' ), array( 'status' => 503 ) );
            }
        }
        return $result;
    }

    public static function output_custom_css() {
        if ( ! get_option( 'emmwt_enabled', 0 ) ) return;
        $custom_css = get_option( 'emmwt_custom_css', '' );
        
        if ( ! empty( $custom_css ) ) {
            echo '<style id="emmwt-custom-css">' . "\n";
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo wp_strip_all_tags( $custom_css ) . "\n";
            echo '</style>' . "\n";
        }
    }

    public static function output_custom_scripts() {
        if ( ! get_option( 'emmwt_enabled', 0 ) ) return;
        $scripts = get_option( 'emmwt_custom_scripts', '' );
        
        if ( ! empty( $scripts ) ) {
            echo "\n\n";
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $scripts . "\n";
        }
    }

    public static function get_timestamp_from_date( $date_string ) {
        if ( empty( $date_string ) ) return 0;

        if ( function_exists( 'wp_timezone' ) ) {
            $dt = date_create_immutable( $date_string, wp_timezone() );
            if ( $dt ) {
                return $dt->getTimestamp();
            }
        }
        return (int) strtotime( $date_string );
    }

    public static function enqueue_frontend_assets() {
        if ( ! get_option( 'emmwt_enabled', 0 ) || self::check_bypass_access() ) return;

        wp_enqueue_script( 'emmwt-countdown', EMMWT_PLUGIN_URL . 'assets/js/countdown.js', array(), EMMWT_VERSION, true );

        $google_font = (string) get_option( 'emmwt_font_google', '' );
        if ( ! empty( $google_font ) ) {
            $font_family = str_replace( ' ', '+', trim( $google_font ) );
            $font_url    = 'https://fonts.googleapis.com/css2?family=' . $font_family . ':wght@400;700&display=swap';
            // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
            wp_enqueue_style( 'emmwt-google-font', esc_url( $font_url ), array(), null );
        }

        $date = (string) get_option( 'emmwt_countdown_date', gmdate( 'Y-m-d H:i' ) );
        $target_timestamp = self::get_timestamp_from_date( $date );
        $redirect_url = (string) get_option( 'emmwt_redirect_url', '' );

        wp_localize_script( 'emmwt-countdown', 'emmwt_data', array(
            'date'          => esc_js( $date ),
            'timestamp'     => $target_timestamp ? ( $target_timestamp * 1000 ) : 0,
            'complete_text' => esc_html__( 'Maintenance complete!', 'easy-maintenance-timer' ),
            'redirect_url'  => esc_url( $redirect_url ),
        ) );
    }

    public static function check_auto_live() {
        // --- 1. Evaluate Scheduled Maintenance Window ---
        $schedule_start = get_option( 'emmwt_schedule_start', '' );
        $schedule_end   = get_option( 'emmwt_schedule_end', '' );
        if ( ! empty( $schedule_start ) && ! empty( $schedule_end ) ) {
            $start_timestamp   = self::get_timestamp_from_date( $schedule_start );
            $end_timestamp     = self::get_timestamp_from_date( $schedule_end );
            $current_timestamp = time();
            
            // Only proceed if both timestamps are valid positive integers and end is strictly after start
            if ( $start_timestamp > 0 && $end_timestamp > 0 && $end_timestamp > $start_timestamp ) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $is_preview = ( isset( $_GET['emmwt_preview'] ) && $_GET['emmwt_preview'] === 'true' && current_user_can( 'manage_options' ) );
                
                if ( ! $is_preview ) {
                    if ( $current_timestamp >= $start_timestamp && $current_timestamp < $end_timestamp ) {
                        if ( ! get_option( 'emmwt_enabled', 0 ) ) {
                            update_option( 'emmwt_enabled', 1 );
                        }
                    } elseif ( $current_timestamp >= $end_timestamp ) {
                        if ( get_option( 'emmwt_enabled', 0 ) ) {
                            update_option( 'emmwt_enabled', 0 );
                            
                            // Clear scheduled dates once window has concluded to prevent repeated triggers
                            update_option( 'emmwt_schedule_start', '' );
                            update_option( 'emmwt_schedule_end', '' );
                            
                            if ( get_option( 'emmwt_notify_admin', 1 ) ) {
                                $to = get_option( 'admin_email' );
                                $site_name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
                                $site_url = site_url();
                                
                                /* translators: %s: site name */
                                $subject = sprintf( __( '[%s] 🚀 Your Website is now LIVE!', 'easy-maintenance-timer' ), $site_name );
                                $message = "Hello,\n\n";
                                $message .= "The scheduled maintenance window has ended!\n\n";
                                $message .= "Maintenance mode has been automatically turned OFF, and your site is now accessible to the public.\n\n";
                                $message .= "View your site here: $site_url\n\n";
                                $message .= "Best regards,\nEasy Maintenance Timer";
                                
                                wp_mail( $to, $subject, $message );
                            }
                        }
                    }
                }
            }
        }

        // --- 2. Evaluate Normal Countdown Expiration ---
        if ( ! get_option( 'emmwt_enabled', 0 ) ) return;

        $date = (string) get_option( 'emmwt_countdown_date', '' );
        if ( empty( $date ) ) return;

        $expiry_timestamp  = self::get_timestamp_from_date( $date );
        $current_timestamp = time();

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $is_preview = ( isset( $_GET['emmwt_preview'] ) && $_GET['emmwt_preview'] === 'true' && current_user_can( 'manage_options' ) );

        if ( $expiry_timestamp && $current_timestamp >= $expiry_timestamp ) {
            if ( $is_preview ) return;

            update_option( 'emmwt_enabled', 0 );

            if ( get_option( 'emmwt_notify_admin', 1 ) ) {
                $to = get_option( 'admin_email' );
                $site_name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
                $site_url = site_url();
                
                $subject = sprintf( '[%s] 🚀 Your Website is now LIVE!', $site_name );
                $message = "Hello,\n\n";
                $message .= "The countdown timer on your website has officially ended!\n\n";
                $message .= "Maintenance mode has been automatically turned OFF, and your site is now accessible to the public.\n\n";
                $message .= "View your site here: $site_url\n\n";
                $message .= "Best regards,\nEasy Maintenance Timer";
                
                wp_mail( $to, $subject, $message );
            }
        }
    }
    public static function frontend_maintenance_redirect() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $is_preview = ( isset( $_GET['emmwt_preview'] ) && $_GET['emmwt_preview'] === 'true' && current_user_can( 'manage_options' ) );

        if ( ! get_option( 'emmwt_enabled', 0 ) && ! $is_preview ) return;

        // --- Page Exclusion Bypass Check ---
        $exclusions_string = (string) get_option( 'emmwt_page_exclusions', '' );
        if ( ! empty( $exclusions_string ) ) {
            $exclusions = array_filter( array_map( 'trim', explode( "\n", $exclusions_string ) ) );
            $current_path = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $current_path_clean = strtok( $current_path, '?' );
            foreach ( $exclusions as $excluded_path ) {
                if ( ! empty( $excluded_path ) && strpos( $current_path_clean, $excluded_path ) !== false ) {
                    return; 
                }
            }
        }

        // --- Passcode Bypass POST Check ---
        if ( isset( $_POST['emmwt_passcode'] ) ) {
            // Verify passcode submission nonce
            if ( ! isset( $_POST['emmwt_passcode_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['emmwt_passcode_nonce'] ) ), 'emmwt_passcode_action' ) ) {
                wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'easy-maintenance-timer' ) );
            }

            $input_password = sanitize_text_field( wp_unslash( $_POST['emmwt_passcode'] ) );
            $saved_password = (string) get_option( 'emmwt_bypass_password', '' );
            if ( ! empty( $saved_password ) && hash_equals( $saved_password, $input_password ) ) {
                self::set_bypass_cookie();
                wp_safe_redirect( remove_query_arg( array( 'emmwt_preview', 'emmwt_bypass' ) ) );
                exit;
            }
        }

        // --- Secret Token Bypass GET Check ---
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['emmwt_bypass'] ) ) {
            $input_token  = sanitize_text_field( wp_unslash( $_GET['emmwt_bypass'] ) );
            $secret_token = self::get_bypass_secret_token();
            if ( ! empty( $secret_token ) && hash_equals( $secret_token, $input_token ) ) {
                self::set_bypass_cookie();
                wp_safe_redirect( remove_query_arg( 'emmwt_bypass' ) );
                exit;
            }
        }

        if ( self::check_bypass_access() ) return;

        // Increment visitor view count (if not a preview)
        if ( ! $is_preview ) {
            $views = (int) get_option( 'emmwt_views_count', 0 );
            update_option( 'emmwt_views_count', $views + 1 );
        }

        if ( $is_preview ) {
            add_filter( 'show_admin_bar', '__return_false', 99 );
            remove_action( 'wp_head', '_admin_bar_bump_cb' );
        }

        $date = (string) get_option( 'emmwt_countdown_date', '' );
        $seconds_remaining = 3600; 
        
        if ( ! empty( $date ) ) {
            $expiry_timestamp = self::get_timestamp_from_date( $date );
            $current_time = time();
            
            if ( $expiry_timestamp && $current_time >= $expiry_timestamp ) {
                $seconds_remaining = 0; 
            } else {
                $seconds_remaining = $expiry_timestamp - $current_time;
            }
        }

        // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
        if ( ! defined( 'DONOTCACHEPAGE' ) ) define( 'DONOTCACHEPAGE', true );
        if ( ! defined( 'DONOTCACHEOBJECT' ) ) define( 'DONOTCACHEOBJECT', true );
        if ( ! defined( 'DONOTMINIFY' ) ) define( 'DONOTMINIFY', true );
        // phpcs:enable

        nocache_headers(); 

        $status_type = get_option( 'emmwt_status_type', 'maintenance' );
        if ( $status_type === 'coming_soon' ) {
            status_header( 200 );
        } else {
            status_header( 503 );
            header( 'Retry-After: ' . max( 60, $seconds_remaining ) ); 
        }
        
        $msg                = (string) get_option( 'emmwt_maint_message', 'Site Under Maintenance. Please check back soon.' );
        $desc               = (string) get_option( 'emmwt_maint_description', '' );
        $msg_color          = (string) get_option( 'emmwt_msg_color', '#000000' );
        $desc_color         = (string) get_option( 'emmwt_desc_color', '#50575e' );
        $logo               = (string) get_option( 'emmwt_logo_url', '' );
        $bg_url             = (string) get_option( 'emmwt_bg_url', '' );
        $bg_overlay_color   = (string) get_option( 'emmwt_bg_overlay_color', '#000000' );
        $bg_overlay_opacity = (int) get_option( 'emmwt_bg_overlay_opacity', 50 );
        $bg_animation       = get_option( 'emmwt_bg_animation', 'none' );

        $seo_title  = (string) get_option( 'emmwt_seo_title', '' );
        $seo_desc   = (string) get_option( 'emmwt_seo_meta_desc', '' );
        $page_title = ! empty( $seo_title ) ? $seo_title : get_bloginfo( 'name' ) . ' - ' . __( 'Maintenance', 'easy-maintenance-timer' );

        $font_setting = get_option( 'emmwt_font_family', 'system' );
        $font_css = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif'; 
        if ( $font_setting === 'sans-serif' ) {
            $font_css = '"Helvetica Neue", Helvetica, Arial, sans-serif';
        } elseif ( $font_setting === 'serif' ) {
            $font_css = 'Georgia, "Times New Roman", Times, serif';
        } elseif ( $font_setting === 'monospace' ) {
            $font_css = 'Menlo, Monaco, Consolas, "Courier New", monospace';
        }

        $hex = ltrim( $bg_overlay_color, '#' );
        if ( empty( $hex ) ) $hex = '000000';
        $r = hexdec( strlen( $hex ) == 3 ? str_repeat( substr( $hex, 0, 1 ), 2 ) : substr( $hex, 0, 2 ) );
        $g = hexdec( strlen( $hex ) == 3 ? str_repeat( substr( $hex, 1, 1 ), 2 ) : substr( $hex, 2, 2 ) );
        $b = hexdec( strlen( $hex ) == 3 ? str_repeat( substr( $hex, 2, 1 ), 2 ) : substr( $hex, 4, 2 ) );
        $alpha = $bg_overlay_opacity / 100;
        $rgba = "rgba($r, $g, $b, $alpha)";

        $has_bg_image = ! empty( $bg_url );
        $is_gradient = ( $bg_animation === 'gradient' );
        $use_glass_wrapper = ( $has_bg_image || $is_gradient );

        if ( $use_glass_wrapper ) {
            $wrapper_style = "width: 90%; max-width: 650px; padding: 45px 30px; box-sizing: border-box; margin: auto; background: rgba(15, 23, 42, 0.65); border-radius: 16px; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);";
            $msg_color = '#ffffff';
            $desc_color = 'rgba(255, 255, 255, 0.85)';
        } else {
            $wrapper_style = "width: 100%; max-width: 800px; padding: 20px; box-sizing: border-box; margin: auto;";
        }

        $btn_hex = ltrim( $msg_color, '#' );
        if ( empty( $btn_hex ) ) $btn_hex = '000000';
        $r_btn = hexdec( strlen( $btn_hex ) == 3 ? str_repeat( substr( $btn_hex, 0, 1 ), 2 ) : substr( $btn_hex, 0, 2 ) );
        $g_btn = hexdec( strlen( $btn_hex ) == 3 ? str_repeat( substr( $btn_hex, 1, 1 ), 2 ) : substr( $btn_hex, 2, 2 ) );
        $b_btn = hexdec( strlen( $btn_hex ) == 3 ? str_repeat( substr( $btn_hex, 2, 1 ), 2 ) : substr( $btn_hex, 4, 2 ) );
        $brightness = ( ( $r_btn * 299 ) + ( $g_btn * 587 ) + ( $b_btn * 114 ) ) / 1000;
        $btn_text_color = ( $brightness > 128 ) ? '#0f172a' : '#ffffff'; 

        // Load the external view clean template
        $view_file = EMMWT_PLUGIN_DIR . 'templates/frontend-view.php';
        if ( file_exists( $view_file ) ) {
            include $view_file;
        }
        exit;
    }
}
