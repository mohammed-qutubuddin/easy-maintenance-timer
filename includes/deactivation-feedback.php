<?php
/**
 * Deactivation Feedback Modal and Email Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/**
 * Enqueue scripts and styles strictly on the Plugins page.
 */
function emmwt_enqueue_admin_deactivation_assets( $hook ) {
    // Only load on the plugins page
    if ( 'plugins.php' !== $hook ) {
        return;
    }

    // Enqueue Admin CSS
    wp_enqueue_style( 'emmwt-admin-css', EMMWT_PLUGIN_URL . 'assets/css/admin.css', array(), EMMWT_VERSION );

    // Enqueue Admin JS
    wp_enqueue_script( 'emmwt-admin-js', EMMWT_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), EMMWT_VERSION, true );

    // Securely pass data to the JS file
    wp_localize_script( 'emmwt-admin-js', 'emmwt_deactivation_data', array(
        'ajax_url'    => admin_url( 'admin-ajax.php' ),
        'plugin_slug' => 'easy-maintenance-timer/easy-maintenance-timer.php',
        'nonce'       => wp_create_nonce( 'emmwt_deactivation_nonce' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'emmwt_enqueue_admin_deactivation_assets' );

/**
 * Output the raw HTML modal in the admin footer.
 */
function emmwt_deactivation_popup_html() {
    $current_screen = get_current_screen();
    if ( ! $current_screen || 'plugins' !== $current_screen->id ) {
        return;
    }
    ?>
    <div id="emmwt-deactivate-overlay" style="display: none;">
        <div id="emmwt-deactivate-modal">
            <h3><?php esc_html_e( 'If you have a moment, please let us know why you are deactivating:', 'easy-maintenance-timer' ); ?></h3>
            
            <form id="emmwt-deactivate-form">
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="I stopped using Easy Maintenance Timer on my site" checked>
                    <?php esc_html_e( 'I stopped using Easy Maintenance Timer on my site', 'easy-maintenance-timer' ); ?>
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="Other reason">
                    <?php esc_html_e( 'Other reason', 'easy-maintenance-timer' ); ?>
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="It is only temporary">
                    <?php esc_html_e( 'It is only temporary', 'easy-maintenance-timer' ); ?>
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="I switched to another plugin">
                    <?php esc_html_e( 'I switched to another plugin', 'easy-maintenance-timer' ); ?>
                </label>
                
                <!-- Technical Issue Option -->
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="Technical Issue" id="emmwt-reason-technical">
                    <?php esc_html_e( 'Technical Issue', 'easy-maintenance-timer' ); ?>
                </label>
                
                <!-- Hidden Details Box for Technical Issues -->
                <div id="emmwt-tech-details-wrapper" style="display: none;">
                    <textarea id="emmwt-tech-desc" name="emmwt_tech_desc" rows="3" placeholder="<?php esc_attr_e( 'Could you describe the issue? Any suggestions help us improve!', 'easy-maintenance-timer' ); ?>"></textarea>
                </div>

                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="I miss a feature">
                    <?php esc_html_e( 'I miss a feature', 'easy-maintenance-timer' ); ?>
                </label>
                
                <div class="emmwt-modal-footer">
                    <button type="submit" class="emmwt-btn-submit" id="emmwt-submit-deactivate"><?php esc_html_e( 'Submit & Deactivate', 'easy-maintenance-timer' ); ?></button>
                    <button type="button" class="emmwt-btn-skip" id="emmwt-only-deactivate"><?php esc_html_e( 'Only Deactivate', 'easy-maintenance-timer' ); ?></button>
                    <a class="emmwt-btn-cancel" id="emmwt-cancel-deactivate"><?php esc_html_e( "Don't deactivate", 'easy-maintenance-timer' ); ?></a>
                </div>
            </form>
        </div>
    </div>
    <?php
}
add_action( 'admin_footer', 'emmwt_deactivation_popup_html' );

/**
 * Handle the AJAX request and send the email.
 */
function emmwt_handle_deactivation_feedback() {
    check_ajax_referer( 'emmwt_deactivation_nonce', 'security' );

    // Sanitize basic reason
    $reason = isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : 'No reason provided';
    
    // Sanitize the new technical description (allowing multiple lines)
    $tech_desc = isset( $_POST['tech_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tech_desc'] ) ) : '';

    // Configure Email
    $to      = 'muhammed.qutubuddin786+plugin@gmail.com';
    $subject = 'Plugin Deactivation Feedback: Easy Maintenance Timer';
    $message = "A user has deactivated Easy Maintenance Timer on their site.\n\n";
    $message .= "Site URL: " . esc_url( site_url() ) . "\n";
    $message .= "Reason provided: " . $reason . "\n";
    
    // Append the technical description if it exists
    if ( 'Technical Issue' === $reason && ! empty( $tech_desc ) ) {
        $message .= "Issue Details: \n" . $tech_desc . "\n";
    }

    $headers = array('Content-Type: text/plain; charset=UTF-8');

    // Send the email
    wp_mail( $to, $subject, $message, $headers );

    wp_send_json_success();
}
add_action( 'wp_ajax_emmwt_submit_deactivation_reason', 'emmwt_handle_deactivation_feedback' );