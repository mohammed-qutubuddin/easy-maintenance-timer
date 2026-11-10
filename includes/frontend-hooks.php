<?php
/**
 * Backwards Compatibility Wrappers for Frontend Hooks.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

use EasyMaintenanceTimer\Frontend_Controller;

function emmwt_get_visitor_ip() {
    return Frontend_Controller::get_visitor_ip();
}

function emmwt_check_bypass_access() {
    return Frontend_Controller::check_bypass_access();
}

function emmwt_disable_rest_api( $result ) {
    return Frontend_Controller::disable_rest_api( $result );
}

function emmwt_output_custom_css() {
    Frontend_Controller::output_custom_css();
}

function emmwt_output_custom_scripts() {
    Frontend_Controller::output_custom_scripts();
}

function emmwt_enqueue_frontend_assets() {
    Frontend_Controller::enqueue_frontend_assets();
}

function emmwt_check_auto_live() {
    Frontend_Controller::check_auto_live();
}