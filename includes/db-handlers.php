<?php
/**
 * Database, Sanitizers, and Export Handlers
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// 1. Sanitizers
function emmwt_sanitize_ips( $value ) {
    $ips = explode( "\n", str_replace( "\r", "", $value ) );
    $clean_ips = array_filter( array_map( 'trim', $ips ), function( $ip ) {
        return filter_var( $ip, FILTER_VALIDATE_IP );
    } );
    return implode( "\n", array_unique( $clean_ips ) );
}

function emmwt_sanitize_scripts( $value ) {
    if ( current_user_can( 'unfiltered_html' ) ) {
        return $value;
    }
    return wp_kses_post( $value ); 
}

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

// 2. Database Creation (Subscribers)
function emmwt_create_subscribers_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'emmwt_subscribers';
    
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) !== $table_name ) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(100) NOT NULL,
            subscribed_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY email (email)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }
}
add_action( 'admin_init', 'emmwt_create_subscribers_table' );

// 3. Export CSV Handlers
function emmwt_export_subscribers_csv() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Unauthorized access.', 'easy-maintenance-timer' ) );
    }
    check_admin_referer( 'emmwt_export_nonce' );

    global $wpdb;
    $table_name = $wpdb->prefix . 'emmwt_subscribers';
    
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) !== $table_name ) {
        wp_die( esc_html__( 'Database table missing.', 'easy-maintenance-timer' ) );
    }

    $subscribers = $wpdb->get_results( "SELECT email, subscribed_at FROM $table_name ORDER BY subscribed_at DESC", ARRAY_A );

    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=easy-maintenance-leads-' . gmdate( 'Y-m-d' ) . '.csv' );

    $output = fopen( 'php://output', 'w' );
    fputs( $output, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ) );
    fputcsv( $output, array( 'Email Address', 'Date Subscribed' ) );

    if ( ! empty( $subscribers ) ) {
        foreach ( $subscribers as $row ) {
            fputcsv( $output, $row );
        }
    }
    fclose( $output );
    exit;
}
add_action( 'admin_post_emmwt_export_csv', 'emmwt_export_subscribers_csv' );