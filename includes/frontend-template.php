<?php
/**
 * Backwards Compatibility Wrappers for Frontend Templates.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

use EasyMaintenanceTimer\Frontend_Controller;

function emmwt_frontend_maintenance_redirect() {
    Frontend_Controller::frontend_maintenance_redirect();
}