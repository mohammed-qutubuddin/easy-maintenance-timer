<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

// PCP: Securely fetch real IP by checking common proxy headers and sanitizing server variables.
function emmwt_get_visitor_ip() {
    if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
        return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) );
    }
    if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ips = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
        return trim( $ips[0] );
    }
    return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
}

function emmwt_check_bypass_access() {
    // 1. Admin always bypasses
    if ( current_user_can( 'manage_options' ) ) return true;
    
    // 2. Secret Cookie bypass
    if ( isset( $_COOKIE['emmwt_bypass_token'] ) && $_COOKIE['emmwt_bypass_token'] === '1' ) return true;

    // 3. Role-based bypass
    if ( is_user_logged_in() ) {
        $allowed_roles = (array) get_option( 'emmwt_bypass_roles', ['editor'] );
        $user = wp_get_current_user();
        $user_roles = (array) $user->roles;
        
        if ( ! empty( array_intersect( $allowed_roles, $user_roles ) ) ) {
            return true;
        }
    }

    // 4. IP Whitelist Bypass
    $allowed_ips_string = (string) get_option( 'emmwt_bypass_ips', '' );
    if ( ! empty( $allowed_ips_string ) ) {
        $allowed_ips = array_filter( array_map( 'trim', explode( "\n", $allowed_ips_string ) ) );
        $visitor_ip  = emmwt_get_visitor_ip();
        
        if ( in_array( $visitor_ip, $allowed_ips, true ) ) {
            return true;
        }
    }

    return false;
}

/**
 * REST API Protection
 * PCP Standard: Uses 'rest_authentication_errors' to block unauthorized access 
 * and returns a proper WP_Error with a 503 status.
 */
function emmwt_disable_rest_api( $result ) {
    // If a previous authentication check already returned an error, don't override it.
    if ( ! empty( $result ) ) {
        return $result;
    }

    // Check if both Maintenance Mode AND REST API Protection are enabled
    if ( get_option( 'emmwt_enabled', 0 ) && get_option( 'emmwt_block_rest_api', 0 ) ) {
        
        // Allow access if the user meets any bypass condition (Admin, Role, IP, or Cookie)
        if ( ! emmwt_check_bypass_access() ) {
            return new WP_Error(
                'rest_forbidden',
                __( 'Site is currently under maintenance.', 'easy-maintenance-timer' ),
                array( 'status' => 503 )
            );
        }
    }

    return $result;
}
add_filter( 'rest_authentication_errors', 'emmwt_disable_rest_api' );

function emmwt_enqueue_frontend_assets() {
    if ( ! get_option( 'emmwt_enabled', 0 ) || emmwt_check_bypass_access() ) return;

    wp_enqueue_style( 'emmwt-frontend-style', EMMWT_PLUGIN_URL . 'assets/css/frontend.css', array(), EMMWT_VERSION );

    $bg_url = (string) get_option( 'emmwt_bg_url', '' );
    if ( ! empty( $bg_url ) ) {
        $custom_css = "body.emmwt-maintenance-mode { background: url('" . esc_url( $bg_url ) . "') no-repeat center center fixed; background-size: cover; }";
        wp_add_inline_style( 'emmwt-frontend-style', $custom_css );
    }

    wp_enqueue_script( 'emmwt-countdown', EMMWT_PLUGIN_URL . 'assets/js/countdown.js', array(), EMMWT_VERSION, true );

    $date = (string) get_option( 'emmwt_countdown_date', gmdate( 'Y-m-d H:i' ) );
    wp_localize_script( 'emmwt-countdown', 'emmwt_data', array(
        'date'          => esc_js( $date ),
        'complete_text' => esc_html__( 'Maintenance complete!', 'easy-maintenance-timer' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'emmwt_enqueue_frontend_assets' );

function emmwt_frontend_maintenance_redirect() {
    if ( ! get_option( 'emmwt_enabled', 0 ) ) return;

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( isset( $_GET['emmwt_bypass'] ) && $_GET['emmwt_bypass'] === 'true' ) {
        setcookie( 'emmwt_bypass_token', '1', time() + ( 86400 * 7 ), COOKIEPATH, COOKIE_DOMAIN );
        wp_safe_redirect( remove_query_arg( 'emmwt_bypass' ) );
        exit;
    }

    if ( emmwt_check_bypass_access() ) return;

    $date = (string) get_option( 'emmwt_countdown_date', '' );
    $seconds_remaining = 3600; 
    
    if ( ! empty( $date ) ) {
        $expiry_timestamp  = strtotime( $date );
        $current_timestamp = current_time( 'timestamp' );
        
        if ( $expiry_timestamp && $current_timestamp >= $expiry_timestamp ) {
            update_option( 'emmwt_enabled', 0 ); 
            return; 
        }
        $seconds_remaining = $expiry_timestamp - $current_timestamp;
    }

    nocache_headers(); 
    status_header( 503 );
    header( 'Retry-After: ' . max( 60, $seconds_remaining ) ); 

    $msg  = (string) get_option( 'emmwt_maint_message', 'Site Under Maintenance. Please check back soon.' );
    $logo = (string) get_option( 'emmwt_logo_url', '' );

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php bloginfo( 'name' ); ?> - <?php esc_html_e( 'Maintenance', 'easy-maintenance-timer' ); ?></title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class( 'emmwt-maintenance-mode' ); ?>>
        <?php wp_body_open(); ?>
        
        <div class="emmwt-content-wrapper">
            <?php if ( $logo ) : ?>
                <img src="<?php echo esc_url( $logo ); ?>" alt="Logo" style="max-width:150px; margin-bottom: 20px;">
            <?php else : ?>
                <svg style="width:80px; height:80px; margin-bottom:20px; fill:#ffffff; opacity:0.9;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
                    <path d="M13 7h-2v6h6v-2h-4z"></path>
                </svg>
            <?php endif; ?>

            <h1><?php echo esc_html( $msg ); ?></h1>
            <div id="emmwt_countdown"></div>

            <?php if ( get_option( 'emmwt_enable_social', 0 ) ) : ?>
                <div class="emmwt-social-icons">
                    <?php 
                    $socials = [
                        'email' => ['url' => get_option('emmwt_social_email', ''), 'prefix' => 'mailto:', 'svg' => '<path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>'],
                        'wa'    => ['url' => preg_replace('/[^0-9]/', '', get_option('emmwt_social_wa', '')), 'prefix' => 'https://wa.me/', 'svg' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>'],
                        'fb'    => ['url' => get_option('emmwt_social_fb', ''), 'prefix' => '', 'svg' => '<path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/>'],
                        'tw'    => ['url' => get_option('emmwt_social_tw', ''), 'prefix' => '', 'svg' => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>'],
                        'ig'    => ['url' => get_option('emmwt_social_ig', ''), 'prefix' => '', 'svg' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>'],
                        'li'    => ['url' => get_option('emmwt_social_li', ''), 'prefix' => '', 'svg' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>']
                    ];
                    
                    foreach ($socials as $key => $data) {
                        if ( !empty($data['url']) ) {
                            $link = $key === 'email' || $key === 'wa' ? $data['prefix'] . $data['url'] : $data['url'];
                            echo '<a href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer" class="emmwt-social-icon">';
                            echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
                            // PCP FIX: Output Escaped securely for inline SVG
                            echo wp_kses( $data['svg'], array( 'path' => array( 'd' => true ) ) );
                            echo '</svg></a>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    exit;
}
add_action( 'template_redirect', 'emmwt_frontend_maintenance_redirect' );