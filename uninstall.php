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
    
    // 1. Array of ALL options used by Easy Maintenance Timer (Fully Updated for v1.03)
    $emmwt_options = array(
        'emmwt_enabled',
        'emmwt_status_type',
        'emmwt_layout_style',
        'emmwt_countdown_date',
        'emmwt_notify_admin', // Added
        'emmwt_maint_message',
        'emmwt_maint_description',
        'emmwt_msg_color',
        'emmwt_desc_color',
        'emmwt_font_family',
        'emmwt_logo_url',
        'emmwt_bg_url',
        'emmwt_bg_overlay_color',
        'emmwt_bg_overlay_opacity',
        'emmwt_bg_animation', // Added
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

    // Loop through and delete all options safely
    foreach ( $emmwt_options as $emmwt_option ) {
        delete_option( $emmwt_option );
    }

    // Clear plugin specific object cache
    wp_cache_delete( 'emmwt_subscribers_list', 'emmwt' );

    // 2. Safely DROP the custom subscribers table
    global $wpdb;
    
    // PCP FIX: Prefixed variable name to avoid global namespace conflicts
    $emmwt_table_name = $wpdb->prefix . 'emmwt_subscribers';
    
    // PCP FIX: Whitelist intentional schema change and direct queries during the uninstall process
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
    $wpdb->query( "DROP TABLE IF EXISTS {$emmwt_table_name}" );
    // phpcs:enable
}