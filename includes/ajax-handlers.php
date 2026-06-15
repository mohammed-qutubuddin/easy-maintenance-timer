<?php
/**
 * AJAX Handlers for Support Tickets and Lead Capture
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// 1. Support Form Handler
function emmwt_handle_support_submission() {
    check_ajax_referer( 'emmwt_support_nonce', 'security' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( __( 'Unauthorized access.', 'easy-maintenance-timer' ) );
    }

    $type    = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

    if ( empty( $message ) ) {
        wp_send_json_error( __( 'Message field cannot be empty.', 'easy-maintenance-timer' ) );
    }

    $current_user = wp_get_current_user();
    $site_url     = site_url();
    $to           = 'muhammed.qutubuddin786+plugin@gmail.com'; 
    $subject      = sprintf( '[Easy Maintenance Timer] %s from %s', $type, $site_url );
    
    $body  = "Type of Inquiry: $type\n";
    $body .= "Website: $site_url\n";
    $body .= "Sender Email: {$current_user->user_email}\n\n";
    $body .= "Message:\n$message\n";
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8', 
        'Reply-To: ' . $current_user->user_email
    );

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( __( 'Your message has been sent successfully! We will get back to you soon.', 'easy-maintenance-timer' ) );
    } else {
        wp_send_json_error( __( 'Failed to send message. Please check your server email configurations.', 'easy-maintenance-timer' ) );
    }
}
add_action( 'wp_ajax_emmwt_submit_support', 'emmwt_handle_support_submission' );

// 2. Email Subscriber Handler
function emmwt_handle_subscribe_email() {
    check_ajax_referer( 'emmwt_subscribe_nonce', 'security' );

    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

    if ( ! is_email( $email ) ) {
        wp_send_json_error( __( 'Please enter a valid email address.', 'easy-maintenance-timer' ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'emmwt_subscribers';

    // PCP FIX: Strict Inline Prepare and Ignores
    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}emmwt_subscribers WHERE email = %s", $email ) );

    if ( $exists ) {
        wp_send_json_error( __( 'You are already subscribed!', 'easy-maintenance-timer' ) );
    }

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $inserted = $wpdb->insert(
        $table_name,
        array( 'email' => $email ),
        array( '%s' ) 
    );

    if ( $inserted ) {
        wp_cache_delete( 'emmwt_subscribers_list', 'emmwt' );
        wp_send_json_success( __( 'Thank you for subscribing! We will notify you.', 'easy-maintenance-timer' ) );
    } else {
        wp_send_json_error( __( 'Something went wrong. Please try again.', 'easy-maintenance-timer' ) );
    }
}
add_action( 'wp_ajax_nopriv_emmwt_subscribe_email', 'emmwt_handle_subscribe_email' );
add_action( 'wp_ajax_emmwt_subscribe_email', 'emmwt_handle_subscribe_email' );