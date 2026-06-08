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
    
    // Array of all options used by Easy Maintenance Timer
    $emmwt_options = array(
        'emmwt_enabled',
        'emmwt_countdown_date',
        'emmwt_maint_message',
        'emmwt_logo_url',
        'emmwt_bg_url',
        'emmwt_bypass_roles',
        'emmwt_enable_social',
        'emmwt_social_email',
        'emmwt_social_fb',
        'emmwt_social_tw',
        'emmwt_social_wa',
        'emmwt_social_li',
        'emmwt_social_ig',
        'emmwt_delete_on_uninstall'
    );

    // PCP FIX: Prefixed the loop variable as $emmwt_option to satisfy global naming conventions
    foreach ( $emmwt_options as $emmwt_option ) {
        delete_option( $emmwt_option );
    }
}