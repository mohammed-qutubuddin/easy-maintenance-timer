<?php
/**
 * Frontend Access Control, IP Checks, and Hooks
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// 1. Securely fetch real IP
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

// 2. Check Bypass Access
function emmwt_check_bypass_access() {
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( isset( $_GET['emmwt_preview'] ) && $_GET['emmwt_preview'] === 'true' && current_user_can( 'manage_options' ) ) {
        return false; 
    }

    if ( current_user_can( 'manage_options' ) ) return true;
    if ( isset( $_COOKIE['emmwt_bypass_token'] ) && $_COOKIE['emmwt_bypass_token'] === '1' ) return true;

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
        $visitor_ip  = emmwt_get_visitor_ip();
        
        if ( in_array( $visitor_ip, $allowed_ips, true ) ) {
            return true;
        }
    }

    return false;
}

// 3. Block REST API
function emmwt_disable_rest_api( $result ) {
    if ( ! empty( $result ) ) return $result;

    if ( get_option( 'emmwt_enabled', 0 ) && get_option( 'emmwt_block_rest_api', 0 ) ) {
        if ( ! emmwt_check_bypass_access() ) {
            return new WP_Error( 'rest_forbidden', __( 'Site is currently under maintenance.', 'easy-maintenance-timer' ), array( 'status' => 503 ) );
        }
    }
    return $result;
}
add_filter( 'rest_authentication_errors', 'emmwt_disable_rest_api' );

// 4. Output Custom CSS
function emmwt_output_custom_css() {
    if ( ! get_option( 'emmwt_enabled', 0 ) ) return;
    $custom_css = get_option( 'emmwt_custom_css', '' );
    
    if ( ! empty( $custom_css ) ) {
        echo '<style id="emmwt-custom-css">' . "\n";
        echo wp_strip_all_tags( $custom_css ) . "\n";
        echo '</style>' . "\n";
    }
}
add_action( 'wp_head', 'emmwt_output_custom_css' );

// 5. Output Custom Tracking Scripts
function emmwt_output_custom_scripts() {
    if ( ! get_option( 'emmwt_enabled', 0 ) ) return;
    $scripts = get_option( 'emmwt_custom_scripts', '' );
    
    if ( ! empty( $scripts ) ) {
        echo "\n\n";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $scripts . "\n";
    }
}
add_action( 'wp_head', 'emmwt_output_custom_scripts', 99 );

// 6. Enqueue Frontend Assets
function emmwt_enqueue_frontend_assets() {
    if ( ! get_option( 'emmwt_enabled', 0 ) || emmwt_check_bypass_access() ) return;

    wp_enqueue_style( 'emmwt-frontend-style', EMMWT_PLUGIN_URL . 'assets/css/frontend.css', array(), EMMWT_VERSION );
    wp_enqueue_script( 'emmwt-countdown', EMMWT_PLUGIN_URL . 'assets/js/countdown.js', array(), EMMWT_VERSION, true );

    $date = (string) get_option( 'emmwt_countdown_date', gmdate( 'Y-m-d H:i' ) );
    wp_localize_script( 'emmwt-countdown', 'emmwt_data', array(
        'date'          => esc_js( $date ),
        'complete_text' => esc_html__( 'Maintenance complete!', 'easy-maintenance-timer' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'emmwt_enqueue_frontend_assets' );