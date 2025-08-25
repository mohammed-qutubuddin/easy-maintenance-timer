<?php
/**
 * Frontend Maintenance Mode
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

function emmwt_frontend_maintenance_redirect() {
    if ( ! get_option( 'emmwt_enabled', 0 ) ) {
        return;
    }

    // Allow admins to view site
    if ( current_user_can( 'manage_options' ) ) {
        return;
    }

    // Send proper maintenance header
    status_header( 503 );
    header( 'Retry-After: 3600' ); // 1 hour

    $date = get_option( 'emmwt_countdown_date', '2025-08-01 12:00' );
    $msg  = get_option( 'emmwt_maint_message', __( 'Site Under Maintenance. Please check back soon.', 'easy-maintenance-timer' ) );
    $logo = get_option( 'emmwt_logo_url', '' );

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php bloginfo( 'name' ); ?> - <?php esc_html_e( 'Maintenance', 'easy-maintenance-timer' ); ?></title>
        <?php
        // Enqueue countdown script properly
        wp_enqueue_script(
            'emmwt-countdown',
            plugin_dir_url( __DIR__ ) . 'assets/js/countdown.js',
            array(),
            '1.0.0',
            true
        );

        // Localize data (send PHP → JS)
        wp_localize_script( 'emmwt-countdown', 'emmwt_data', array(
            'date'          => $date,
            'complete_text' => __( 'Maintenance complete!', 'easy-maintenance-timer' ),
        ) );

        wp_head(); // required for WP styles/scripts
        ?>
    </head>
    <body <?php body_class( 'emmwt-maintenance-mode' ); ?> style="text-align:center;margin-top:100px;">
        <?php if ( $logo ) : ?>
            <img src="<?php echo esc_url( $logo ); ?>" 
                 alt="<?php esc_attr_e( 'Maintenance Logo', 'easy-maintenance-timer' ); ?>" 
                 style="max-width:150px;">
            <br><br>
        <?php endif; ?>

        <h1><?php echo esc_html( $msg ); ?></h1>
        <div id="emmwt_countdown" style="font-size:2em;"></div>

        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    exit;
}
add_action( 'template_redirect', 'emmwt_frontend_maintenance_redirect' );
