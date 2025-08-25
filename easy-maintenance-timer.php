<?php
/*
Plugin Name: Easy Maintenance Timer
Plugin URI: https://example.com/easy-maintenance-timer
Description: Enable maintenance mode with countdown, custom logo, and message.
Version: 1.0
Author: Abdul Nasir
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: easy-maintenance-timer
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/**
 * Load plugin textdomain for translations.
 */
function emmwt_load_textdomain() {
    load_plugin_textdomain( 'easy-maintenance-timer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'emmwt_load_textdomain' );

/**
 * Include required files.
 */
function emmwt_init_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/admin-settings.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/frontend-maintenance.php';
}
add_action( 'init', 'emmwt_init_plugin' );

/**
 * Add settings link on plugins page.
 *
 * @param array $links
 * @return array
 */
function emmwt_settings_link( $links ) {
    $settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=emmwt_settings' ) ) . '">' . esc_html__( 'Settings', 'easy-maintenance-timer' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'emmwt_settings_link' );
