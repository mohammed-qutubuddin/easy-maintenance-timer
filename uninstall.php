<?php
/**
 * Fired when the plugin is completely deleted.
 * Checks user preference before cleaning up the database.
 */

// If uninstall is not called from WordPress, exit safely
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Only delete data if the user explicitly enabled the "Clean Data on Uninstall" toggle
if ( get_option( 'emmwt_delete_on_uninstall', 0 ) ) {
    
    // 1. Array of ALL options used by Easy Maintenance Timer (Updated for v1.03)
    $emmwt_options = array(
        'emmwt_enabled',
        'emmwt_status_type',
        'emmwt_layout_style',
        'emmwt_countdown_date',
        'emmwt_maint_message',
        'emmwt_maint_description',
        'emmwt_msg_color',
        'emmwt_desc_color',
        'emmwt_font_family',
        'emmwt_logo_url',
        'emmwt_bg_url',
        'emmwt_bg_overlay_color',
        'emmwt_bg_overlay_opacity',
        'emmwt_seo_title',
        'emmwt_seo_meta_desc',
        'emmwt_custom_css',
        'emmwt_custom_scripts',
        'emmwt_bypass_roles',
        'emmwt_bypass_ips',
        'emmwt_block_rest_api',
        'emmwt_enable_social',
        'emmwt_social_email',
        'emmwt_social_fb',
        'emmwt_social_tw',
        'emmwt_social_wa',
        'emmwt_social_li',
        'emmwt_social_ig',
        'emmwt_enable_subscribe',
        'emmwt_delete_on_uninstall'
    );

    // Loop through and delete all options
    foreach ( $emmwt_options as $emmwt_option ) {
        delete_option( $emmwt_option );
    }

    // 2. Safely DROP the custom subscribers table
    global $wpdb;
    $table_name = $wpdb->prefix . 'emmwt_subscribers';
    
    // Direct query is acceptable here since we are dropping a specific, known table during uninstall
    $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
}