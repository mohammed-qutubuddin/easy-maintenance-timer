<?php
/**
 * Backwards Compatibility Wrappers for AJAX Handlers.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

use EasyMaintenanceTimer\Ajax_Handler;

function emmwt_handle_support_submission() {
    Ajax_Handler::handle_support_submission();
}

function emmwt_handle_subscribe_email() {
    Ajax_Handler::handle_subscribe_email();
}