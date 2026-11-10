<?php
namespace EasyMaintenanceTimer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ajax_Handler {

    public static function init() {
        add_action( 'wp_ajax_emmwt_submit_support', [ __CLASS__, 'handle_support_submission' ] );
        add_action( 'wp_ajax_nopriv_emmwt_subscribe_email', [ __CLASS__, 'handle_subscribe_email' ] );
        add_action( 'wp_ajax_emmwt_subscribe_email', [ __CLASS__, 'handle_subscribe_email' ] );
        
        // Contact Form AJAX Action
        add_action( 'wp_ajax_nopriv_emmwt_submit_contact', [ __CLASS__, 'handle_contact_submission' ] );
        add_action( 'wp_ajax_emmwt_submit_contact', [ __CLASS__, 'handle_contact_submission' ] );
    }

    public static function handle_support_submission() {
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

    public static function handle_subscribe_email() {
        check_ajax_referer( 'emmwt_subscribe_nonce', 'security' );

        // Honeypot Spam Check
        if ( ! empty( $_POST['emmwt_hp'] ) ) {
            wp_send_json_success( __( 'Thank you for subscribing! We will notify you.', 'easy-maintenance-timer' ) );
        }

        $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

        if ( ! is_email( $email ) ) {
            wp_send_json_error( __( 'Please enter a valid email address.', 'easy-maintenance-timer' ) );
        }

        if ( strlen( $email ) > 100 ) {
            wp_send_json_error( __( 'Email address exceeds maximum length of 100 characters.', 'easy-maintenance-timer' ) );
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'emmwt_subscribers';

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

            // 1. Webhook Notification (Zapier, Make, Mailchimp, CRM) - SSRF Protected
            $webhook_url = get_option( 'emmwt_webhook_url', '' );
            if ( ! empty( $webhook_url ) && wp_http_validate_url( $webhook_url ) ) {
                $payload = array(
                    'event'         => 'new_subscriber',
                    'email'         => $email,
                    'site_name'     => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
                    'site_url'      => site_url(),
                    'subscribed_at' => current_time( 'mysql' ),
                );
                wp_remote_post( $webhook_url, array(
                    'timeout'            => 5,
                    'redirection'        => 2,
                    'blocking'           => false, // Asynchronous non-blocking call
                    'reject_unsafe_urls' => true,  // Mitigate SSRF attacks targeting internal IPs
                    'headers'            => array( 'Content-Type' => 'application/json; charset=utf-8' ),
                    'body'               => wp_json_encode( $payload ),
                ) );
            }

            // 2. Subscriber Confirmation Auto-Responder Email
            if ( get_option( 'emmwt_enable_subscriber_email', 0 ) ) {
                $site_name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
                $site_url  = site_url();
                
                /* translators: %s: site name */
                $default_subject = sprintf( __( '[%s] Thank you for subscribing!', 'easy-maintenance-timer' ), $site_name );
                $subject = get_option( 'emmwt_subscriber_email_subject', $default_subject );
                if ( empty( $subject ) ) {
                    $subject = $default_subject;
                } else {
                    $subject = str_replace( array( '{site_name}', '{site_url}' ), array( $site_name, $site_url ), $subject );
                }

                $default_body = "Hello,\n\nThank you for subscribing to our updates! We are currently working on our website and will notify you the moment we launch.\n\nBest regards,\n" . $site_name . "\n" . $site_url;
                $body = get_option( 'emmwt_subscriber_email_body', $default_body );
                if ( empty( $body ) ) {
                    $body = $default_body;
                } else {
                    $body = str_replace( array( '{site_name}', '{site_url}', '{email}' ), array( $site_name, $site_url, $email ), $body );
                }

                $clean_subject = trim( str_replace( array( "\r", "\n" ), '', (string) $subject ) );
                $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
                wp_mail( $email, $clean_subject, $body, $headers );
            }

            wp_send_json_success( __( 'Thank you for subscribing! We will notify you.', 'easy-maintenance-timer' ) );
        } else {
            wp_send_json_error( __( 'Something went wrong. Please try again.', 'easy-maintenance-timer' ) );
        }
    }

    public static function handle_contact_submission() {
        check_ajax_referer( 'emmwt_contact_nonce', 'security' );

        // Honeypot Spam Check
        if ( ! empty( $_POST['emmwt_hp'] ) ) {
            wp_send_json_success( __( 'Your message has been sent successfully!', 'easy-maintenance-timer' ) );
        }

        $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
        $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

        if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
            wp_send_json_error( __( 'All fields are required.', 'easy-maintenance-timer' ) );
        }

        if ( ! is_email( $email ) ) {
            wp_send_json_error( __( 'Please enter a valid email address.', 'easy-maintenance-timer' ) );
        }

        $to = get_option( 'emmwt_contact_email' );
        if ( empty( $to ) || ! is_email( $to ) ) {
            $to = get_option( 'admin_email' );
        }
        $subject = sprintf( '[Easy Maintenance Contact] Message from %s', $name );
        $body    = "Name: $name\n";
        $body   .= "Email: $email\n\n";
        $body   .= "Message:\n$message\n";
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8', 
            'Reply-To: ' . $email
        );

        $sent = wp_mail( $to, $subject, $body, $headers );

        if ( $sent ) {
            wp_send_json_success( __( 'Your message has been sent successfully!', 'easy-maintenance-timer' ) );
        } else {
            wp_send_json_error( __( 'Failed to send message. Please try again later.', 'easy-maintenance-timer' ) );
        }
    }
}
