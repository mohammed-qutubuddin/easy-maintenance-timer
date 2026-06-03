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

    $date = (string) get_option( 'emmwt_countdown_date', gmdate('Y-m-d H:i') );
    $msg  = (string) get_option( 'emmwt_maint_message', __( 'Site Under Maintenance. Please check back soon.', 'easy-maintenance-timer' ) );
    
    // Frontend Default Logo Logic
    $default_logo = trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'assets/img/default-logo.jpg';
    $saved_logo   = (string) get_option( 'emmwt_logo_url', '' );
    $logo         = ! empty( $saved_logo ) ? $saved_logo : $default_logo;
    
    $bg_url = (string) get_option( 'emmwt_bg_url', '' );

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php bloginfo( 'name' ); ?> - <?php esc_html_e( 'Maintenance', 'easy-maintenance-timer' ); ?></title>
        
        <style>
            /* Default background animation CSS */
            @keyframes emmwtBgAnimation {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            body.emmwt-maintenance-mode {
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                color: #ffffff;
                text-shadow: 0 2px 4px rgba(0,0,0,0.4);
                <?php if ( $bg_url ) : ?>
                    background: url('<?php echo esc_url( $bg_url ); ?>') no-repeat center center fixed; 
                    background-size: cover;
                <?php else : ?>
                    background: linear-gradient(270deg, #1e3c72, #2a5298, #6dd5ed);
                    background-size: 600% 600%;
                    animation: emmwtBgAnimation 15s ease infinite;
                <?php endif; ?>
            }
            .emmwt-content-wrapper {
                background: rgba(0, 0, 0, 0.5);
                padding: 40px;
                border-radius: 12px;
                max-width: 600px;
                width: 90%;
            }
            #emmwt_countdown {
                font-size: 2.5em;
                font-weight: bold;
                margin-top: 20px;
            }
        </style>

        <?php
        wp_enqueue_script(
            'emmwt-countdown',
            plugin_dir_url( __DIR__ ) . 'assets/js/countdown.js',
            array(),
            '1.0.0',
            true
        );

        wp_localize_script( 'emmwt-countdown', 'emmwt_data', array(
            'date'          => $date,
            'complete_text' => __( 'Maintenance complete!', 'easy-maintenance-timer' ),
        ) );

        wp_head();
        ?>
    </head>
    <body <?php body_class( 'emmwt-maintenance-mode' ); ?>>
        <?php wp_body_open(); ?>
        
        <div class="emmwt-content-wrapper">
            <?php if ( $logo ) : ?>
                <img src="<?php echo esc_url( $logo ); ?>" 
                     alt="<?php esc_attr_e( 'Maintenance Logo', 'easy-maintenance-timer' ); ?>" 
                     style="max-width:150px; margin-bottom: 20px;">
            <?php endif; ?>

            <h1><?php echo esc_html( $msg ); ?></h1>
            <div id="emmwt_countdown"></div>
        </div>

        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    exit;
}
add_action( 'template_redirect', 'emmwt_frontend_maintenance_redirect' );