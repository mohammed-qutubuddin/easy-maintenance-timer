<?php
/**
 * Backwards Compatibility Wrappers for Admin Settings.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

use EasyMaintenanceTimer\Admin_Controller;

function emmwt_register_settings_page() {
    Admin_Controller::register_settings_page();
}

function emmwt_register_settings() {
    Admin_Controller::register_settings();
}

function emmwt_admin_settings_enqueue( $hook ) {
    Admin_Controller::admin_settings_enqueue( $hook );
}

function emmwt_settings_page_callback() {
    Admin_Controller::settings_page_callback();
}