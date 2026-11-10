<?php
namespace EasyMaintenanceTimer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Deactivation_Feedback {

    public static function init() {
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_deactivation_assets' ] );
        add_action( 'admin_footer', [ __CLASS__, 'deactivation_popup_html' ] );
        add_action( 'wp_ajax_emmwt_submit_deactivation_reason', [ __CLASS__, 'handle_deactivation_feedback' ] );
    }

    public static function enqueue_admin_deactivation_assets( $hook ) {
        if ( 'plugins.php' !== $hook && 'plugins-network.php' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'emmwt-admin-css', EMMWT_PLUGIN_URL . 'assets/css/admin.css', array(), EMMWT_VERSION );
        wp_enqueue_script( 'emmwt-admin-js', EMMWT_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), EMMWT_VERSION, true );

        wp_localize_script( 'emmwt-admin-js', 'emmwt_deactivation_data', array(
            'ajax_url'    => admin_url( 'admin-ajax.php' ),
            'plugin_slug' => 'easy-maintenance-timer/easy-maintenance-timer.php',
            'nonce'       => wp_create_nonce( 'emmwt_deactivation_nonce' )
        ) );
    }

    public static function deactivation_popup_html() {
        $current_screen = get_current_screen();
        if ( ! $current_screen || ( 'plugins' !== $current_screen->id && 'plugins-network' !== $current_screen->id ) ) {
            return;
        }
        ?>
        <div id="emmwt-deactivate-overlay" style="display: none;">
            <div class="emmwt-modal-backdrop"></div>
            <div id="emmwt-deactivate-modal">
                <div class="emmwt-modal-header">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-clock" style="font-size: 22px; width: 22px; height: 22px; color: #2563eb;"></span>
                        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">
                            <?php esc_html_e( 'Quick Feedback', 'easy-maintenance-timer' ); ?>
                        </h3>
                    </div>
                    <span id="emmwt-close-deactivate-modal" class="dashicons dashicons-no-alt" style="cursor: pointer; font-size: 20px; color: #64748b;"></span>
                </div>
                
                <div class="emmwt-modal-body">
                    <p style="margin: 0 0 16px 0; color: #475569; font-size: 13.5px; line-height: 1.5;">
                        <?php esc_html_e( 'If you have a moment, please let us know why you are deactivating Easy Maintenance Timer:', 'easy-maintenance-timer' ); ?>
                    </p>
                    
                    <form id="emmwt-deactivate-form">
                        <label class="emmwt-reason-option">
                            <input type="radio" name="emmwt_reason" value="I stopped using Easy Maintenance Timer on my site" checked>
                            <span><?php esc_html_e( 'Maintenance / launch finished, no longer needed', 'easy-maintenance-timer' ); ?></span>
                        </label>
                        <label class="emmwt-reason-option">
                            <input type="radio" name="emmwt_reason" value="It is only temporary">
                            <span><?php esc_html_e( 'It is only temporary (debugging or updates)', 'easy-maintenance-timer' ); ?></span>
                        </label>
                        <label class="emmwt-reason-option">
                            <input type="radio" name="emmwt_reason" value="I switched to another plugin">
                            <span><?php esc_html_e( 'I switched to another plugin', 'easy-maintenance-timer' ); ?></span>
                        </label>
                        <label class="emmwt-reason-option">
                            <input type="radio" name="emmwt_reason" value="Technical Issue" id="emmwt-reason-technical">
                            <span><?php esc_html_e( 'Technical issue / Bug encountered', 'easy-maintenance-timer' ); ?></span>
                        </label>
                        <label class="emmwt-reason-option">
                            <input type="radio" name="emmwt_reason" value="I miss a feature">
                            <span><?php esc_html_e( 'I miss a specific feature', 'easy-maintenance-timer' ); ?></span>
                        </label>
                        <label class="emmwt-reason-option">
                            <input type="radio" name="emmwt_reason" value="Other reason">
                            <span><?php esc_html_e( 'Other reason', 'easy-maintenance-timer' ); ?></span>
                        </label>
                        
                        <div id="emmwt-tech-details-wrapper" style="display: none; margin: 12px 0 16px 0;">
                            <textarea id="emmwt-tech-desc" name="emmwt_tech_desc" rows="3" placeholder="<?php esc_attr_e( 'Could you tell us more details? Any suggestions help us improve!', 'easy-maintenance-timer' ); ?>"></textarea>
                        </div>
                        
                        <div class="emmwt-modal-footer">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button type="submit" class="button button-primary emmwt-btn-submit" id="emmwt-submit-deactivate">
                                    <?php esc_html_e( 'Submit & Deactivate', 'easy-maintenance-timer' ); ?>
                                </button>
                                <button type="button" class="button button-secondary emmwt-btn-skip" id="emmwt-only-deactivate">
                                    <?php esc_html_e( 'Skip & Deactivate', 'easy-maintenance-timer' ); ?>
                                </button>
                            </div>
                            <a class="emmwt-btn-cancel" id="emmwt-cancel-deactivate" href="javascript:void(0);">
                                <?php esc_html_e( "Don't deactivate", 'easy-maintenance-timer' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    public static function handle_deactivation_feedback() {
        check_ajax_referer( 'emmwt_deactivation_nonce', 'security' );

        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'Unauthorized action.', 'easy-maintenance-timer' ) );
        }

        $reason = isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : 'No reason provided';
        $tech_desc = isset( $_POST['tech_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tech_desc'] ) ) : '';

        $to      = 'muhammed.qutubuddin786+plugin@gmail.com';
        $subject = 'Plugin Deactivation Feedback: Easy Maintenance Timer';
        $message = "A user has deactivated Easy Maintenance Timer on their site.\n\n";
        $message .= "Site URL: " . esc_url( site_url() ) . "\n";
        $message .= "Reason provided: " . $reason . "\n";
        
        if ( 'Technical Issue' === $reason && ! empty( $tech_desc ) ) {
            $message .= "Issue Details: \n" . $tech_desc . "\n";
        }

        $headers = array('Content-Type: text/plain; charset=UTF-8');

        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            wp_send_json_success();
        } else {
            wp_send_json_error( __( 'Failed to send feedback.', 'easy-maintenance-timer' ) );
        }
    }
}
