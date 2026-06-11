<?php
/*
Plugin Name: Easy Maintenance Timer
Description: Enable maintenance mode with countdown, custom logo, and message.
Version: 1.03
Author: Abdul Nasir
Text Domain: easy-maintenance-timer
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// Define plugin constants for strict and efficient path referencing
define( 'EMMWT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EMMWT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EMMWT_VERSION', '1.03' );

/**
 * Include required files cleanly.
 * PCP Guideline: Load files conditionally to optimize performance 
 * and avoid loading admin scripts on the frontend.
 */

// 1. Core Handlers (Loaded everywhere for DB sync and AJAX actions)
require_once EMMWT_PLUGIN_DIR . 'includes/db-handlers.php';
require_once EMMWT_PLUGIN_DIR . 'includes/ajax-handlers.php';

// 2. Conditional Loading for True Zero-Bloat
if ( is_admin() ) {
    // Load strictly in the WordPress backend
    require_once EMMWT_PLUGIN_DIR . 'includes/admin-settings.php';
    require_once EMMWT_PLUGIN_DIR . 'includes/deactivation-feedback.php';
} else {
    // Load strictly on the frontend (for Visitors and Live Preview)
    require_once EMMWT_PLUGIN_DIR . 'includes/frontend-hooks.php';
    require_once EMMWT_PLUGIN_DIR . 'includes/frontend-template.php';
}

/**
 * Load plugin textdomain for translations.
 * PCP Guideline: Essential for global repository distribution.
 */
function emmwt_load_textdomain() {
    // phpcs:ignore PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound
    load_plugin_textdomain( 
        'easy-maintenance-timer', 
        false, 
        dirname( plugin_basename( __FILE__ ) ) . '/languages/' 
    );
}
add_action( 'plugins_loaded', 'emmwt_load_textdomain' );

/**
 * Add settings link on plugins page.
 *
 * @param array $links
 * @return array
 */
function emmwt_settings_link( $links ) {
    // Ensuring output is strictly escaped per WP guidelines
    $settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=emmwt_settings' ) ) . '">' . esc_html__( 'Settings', 'easy-maintenance-timer' ) . '</a>';
    
    array_unshift( $links, $settings_link );
    
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'emmwt_settings_link' );