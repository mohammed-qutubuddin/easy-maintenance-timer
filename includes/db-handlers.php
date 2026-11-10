<?php
/**
 * Backwards Compatibility Wrappers for Database Handlers.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

use EasyMaintenanceTimer\Database_Manager;

function emmwt_sanitize_ips( $value ) {
    return Database_Manager::sanitize_ips( $value );
}

function emmwt_sanitize_scripts( $value ) {
    return Database_Manager::sanitize_scripts( $value );
}

function emmwt_sanitize_checkbox( $value ) {
    return Database_Manager::sanitize_checkbox( $value );
}

function emmwt_sanitize_array( $value ) {
    return Database_Manager::sanitize_array( $value );
}

function emmwt_create_subscribers_table() {
    Database_Manager::create_subscribers_table();
}