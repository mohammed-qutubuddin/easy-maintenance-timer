<?php
/*
Plugin Name: Easy Maintenance Mode with Countdown
Description: Enable maintenance mode with countdown, custom logo and message.
Version: 1.0
Author: Abdul Nasir
*/

if ( ! defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/frontend-maintenance.php';

// Quick "Settings" link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=emm_settings') . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
});
