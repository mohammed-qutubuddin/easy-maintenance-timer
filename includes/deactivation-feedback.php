<?php
/**
 * Backwards Compatibility Wrappers for Deactivation Feedback.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

use EasyMaintenanceTimer\Deactivation_Feedback;

function emmwt_enqueue_admin_deactivation_assets( $hook ) {
    Deactivation_Feedback::enqueue_admin_deactivation_assets( $hook );
}

function emmwt_deactivation_popup_html() {
    Deactivation_Feedback::deactivation_popup_html();
}

function emmwt_handle_deactivation_feedback() {
    Deactivation_Feedback::handle_deactivation_feedback();
}