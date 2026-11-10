<?php
/**
 * Admin Settings Page View
 *
 * Variables passed from controller:
 * @var string $preview_url
 * @var string $value_msg
 * @var string $value_desc
 * @var string $value_logo
 * @var string $value_bg
 * @var string $value_date
 * @var array  $saved_roles
 * @var string $saved_ips
 * @var string $current_ip
 * @var string $font_family
 * @var string $bg_animation
 * @var string $bg_overlay_color
 * @var int    $bg_overlay_opacity
 * @var string $msg_color
 * @var string $desc_color
 * @var string $seo_title
 * @var string $seo_desc
 * @var string $status_type
 * @var array  $all_roles
 * @var array  $subscribers
 * @var string $bypass_url
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap emmwt-wrap">
    
    <div class="emmwt-header">
        <h2><?php esc_html_e( 'Easy Maintenance Mode Settings', 'easy-maintenance-timer' ); ?></h2>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" id="emmwt-open-sandbox-btn" class="button button-secondary" title="<?php esc_attr_e( 'Preview the maintenance page directly inside the dashboard', 'easy-maintenance-timer' ); ?>">
                <span class="dashicons dashicons-desktop"></span> 
                <span><?php esc_html_e( 'Preview Sandbox', 'easy-maintenance-timer' ); ?></span>
            </button>
            <a href="<?php echo esc_url( $preview_url ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary" title="<?php esc_attr_e( 'Make sure to Save Changes before previewing!', 'easy-maintenance-timer' ); ?>">
                <span class="dashicons dashicons-visibility"></span> 
                <span><?php esc_html_e( 'Live Preview (New Tab)', 'easy-maintenance-timer' ); ?></span>
            </a>
        </div>
    </div>
    
    <div class="emmwt-layout-wrapper">
        
        <!-- Left Vertical Navigation Menu -->
        <div class="emmwt-sidebar-nav">
            <a href="#tab-general" class="emmwt-nav-item emmwt-nav-item-active">
                <span class="dashicons dashicons-admin-generic"></span>
                <span><?php esc_html_e( 'General Settings', 'easy-maintenance-timer' ); ?></span>
            </a>
            <a href="#tab-access" class="emmwt-nav-item">
                <span class="dashicons dashicons-lock"></span>
                <span><?php esc_html_e( 'Access Control', 'easy-maintenance-timer' ); ?></span>
            </a>
            <a href="#tab-social" class="emmwt-nav-item">
                <span class="dashicons dashicons-share"></span>
                <span><?php esc_html_e( 'Social & Sharing', 'easy-maintenance-timer' ); ?></span>
            </a>
            <a href="#tab-css" class="emmwt-nav-item">
                <span class="dashicons dashicons-admin-customizer"></span>
                <span><?php esc_html_e( 'CSS & Tools', 'easy-maintenance-timer' ); ?></span>
            </a>
            <a href="#tab-subscribers" class="emmwt-nav-item">
                <span class="dashicons dashicons-groups"></span>
                <span><?php esc_html_e( 'Subscribers', 'easy-maintenance-timer' ); ?></span>
            </a>
            <a href="#tab-support" class="emmwt-nav-item">
                <span class="dashicons dashicons-testimonial"></span>
                <span><?php esc_html_e( 'Support & Help', 'easy-maintenance-timer' ); ?></span>
            </a>

            <!-- Status Indicator Card in Sidebar -->
            <div style="margin-top: 24px; padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; margin-bottom: 8px;">
                    <?php esc_html_e( 'Maintenance Status', 'easy-maintenance-timer' ); ?>
                </div>
                <?php if ( get_option( 'emmwt_enabled', 0 ) ) : ?>
                    <span class="emmwt-status-indicator active">
                        <span class="emmwt-pulse-dot"></span> <?php esc_html_e( 'Active (Locked)', 'easy-maintenance-timer' ); ?>
                    </span>
                <?php else : ?>
                    <span class="emmwt-status-indicator inactive">
                        <span class="emmwt-pulse-dot"></span> <?php esc_html_e( 'Inactive (Public)', 'easy-maintenance-timer' ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Content Settings Panel -->
        <div class="emmwt-settings-panel">
            <form method="post" action="options.php">
                <?php settings_fields( 'emmwt_settings_group' ); ?>

                <div id="tab-general" class="emmwt-tab-pane" style="display: block;">
                    <div class="emmwt-card">
                        <div class="emmwt-card-header">
                            <h3><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'General Settings', 'easy-maintenance-timer' ); ?></h3>
                        </div>
                        <div class="emmwt-card-body">
                            <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Maintenance Mode', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enabled">
                                <input type="checkbox" id="emmwt_enabled" name="emmwt_enabled" value="1" <?php checked( 1, get_option( 'emmwt_enabled', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Mode Type', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <select name="emmwt_status_type" class="emmwt-dependent regular-text">
                                <option value="maintenance" <?php selected( $status_type, 'maintenance' ); ?>><?php esc_html_e( 'Maintenance Mode (503 Error - SEO Safe)', 'easy-maintenance-timer' ); ?></option>
                                <option value="coming_soon" <?php selected( $status_type, 'coming_soon' ); ?>><?php esc_html_e( 'Coming Soon (200 OK - Indexable)', 'easy-maintenance-timer' ); ?></option>
                            </select>
                            <p class="description">
                                <?php esc_html_e( 'Use "Maintenance" for temporary downtime. Use "Coming Soon" if your site is brand new and you want Google to index it.', 'easy-maintenance-timer' ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Layout Preset Style', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <?php $emmwt_layout_preset = get_option( 'emmwt_layout_preset', 'default' ); ?>
                            <select name="emmwt_layout_preset" class="emmwt-dependent regular-text">
                                <option value="default" <?php selected( $emmwt_layout_preset, 'default' ); ?>><?php esc_html_e( 'Default Centered (Modern Card)', 'easy-maintenance-timer' ); ?></option>
                                <option value="split" <?php selected( $emmwt_layout_preset, 'split' ); ?>><?php esc_html_e( 'Left-Split Screen Page', 'easy-maintenance-timer' ); ?></option>
                                <option value="minimal" <?php selected( $emmwt_layout_preset, 'minimal' ); ?>><?php esc_html_e( 'Minimalist Focus Page', 'easy-maintenance-timer' ); ?></option>
                            </select>
                            <p class="description">
                                <?php esc_html_e( 'Select the visual layout preset layout style for the frontend maintenance page.', 'easy-maintenance-timer' ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>                    
                        <th scope="row"><?php esc_html_e( 'Countdown End Date/Time', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="text" id="emmwt_datepicker" name="emmwt_countdown_date" class="emmwt-dependent" value="<?php echo esc_attr( $value_date ); ?>" required />
                        </td>
                    </tr>
                    <tr>                    
                        <th scope="row"><?php esc_html_e( 'Redirect URL on Expiry', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="url" name="emmwt_redirect_url" class="emmwt-dependent regular-text" value="<?php echo esc_url( get_option( 'emmwt_redirect_url', '' ) ); ?>" placeholder="e.g. https://yoursite.com/welcome" />
                            <p class="description"><?php esc_html_e( 'If set, visitors are automatically redirected to this URL client-side once the countdown finishes.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>                    
                        <th scope="row"><?php esc_html_e( 'Scheduled Maintenance Window', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
                                <div>
                                    <label for="emmwt_schedule_start">Start:</label>
                                    <input type="text" id="emmwt_schedule_start" name="emmwt_schedule_start" class="emmwt-datetimepicker" value="<?php echo esc_attr( get_option( 'emmwt_schedule_start', '' ) ); ?>" placeholder="Select Start Date/Time..." />
                                </div>
                                <div>
                                    <label for="emmwt_schedule_end">End:</label>
                                    <input type="text" id="emmwt_schedule_end" name="emmwt_schedule_end" class="emmwt-datetimepicker" value="<?php echo esc_attr( get_option( 'emmwt_schedule_end', '' ) ); ?>" placeholder="Select End Date/Time..." />
                                </div>
                            </div>
                            <p class="description"><?php esc_html_e( 'Automatically activate and deactivate maintenance mode between these dates. Leaves manual override enabled if blank.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Auto-Live Email Notification', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_notify_admin">
                                <input type="checkbox" id="emmwt_notify_admin" name="emmwt_notify_admin" value="1" <?php checked( 1, get_option( 'emmwt_notify_admin', 1 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <p class="description"><?php esc_html_e( 'Send an automatic email to the site admin when the timer ends and the site goes live.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Maintenance Message', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
                                <input type="text" name="emmwt_maint_message" class="emmwt-dependent regular-text" value="<?php echo esc_attr( $value_msg ); ?>" />
                                <input type="text" name="emmwt_msg_color" class="emmwt-color-picker emmwt-dependent" value="<?php echo esc_attr( $msg_color ); ?>" data-default-color="#000000" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Description', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:15px; align-items:flex-start; flex-wrap:wrap;">
                                <textarea name="emmwt_maint_description" class="emmwt-dependent large-text" rows="3" placeholder="<?php esc_attr_e( 'Enter a brief description... e.g. We are upgrading our systems.', 'easy-maintenance-timer' ); ?>"><?php echo esc_textarea( $value_desc ); ?></textarea>
                                <div style="margin-top: 2px;">
                                    <input type="text" name="emmwt_desc_color" class="emmwt-color-picker emmwt-dependent" value="<?php echo esc_attr( $desc_color ); ?>" data-default-color="#50575e" />
                                </div>
                            </div>
                        </td>
                    </tr>   
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Typography Style', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <select name="emmwt_font_family" class="emmwt-dependent regular-text">
                                <option value="system" <?php selected( $font_family, 'system' ); ?>><?php esc_html_e( 'System Default (Fastest)', 'easy-maintenance-timer' ); ?></option>
                                <option value="sans-serif" <?php selected( $font_family, 'sans-serif' ); ?>><?php esc_html_e( 'Modern Sans-Serif', 'easy-maintenance-timer' ); ?></option>
                                <option value="serif" <?php selected( $font_family, 'serif' ); ?>><?php esc_html_e( 'Classic Serif', 'easy-maintenance-timer' ); ?></option>
                                <option value="monospace" <?php selected( $font_family, 'monospace' ); ?>><?php esc_html_e( 'Monospace / Code', 'easy-maintenance-timer' ); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e( 'Native OS fonts that load instantly without external requests (Zero Bloat).', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Google Font Integration', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <?php $emmwt_google_font = get_option( 'emmwt_font_google', '' ); ?>
                            <select name="emmwt_font_google" class="emmwt-dependent regular-text">
                                <option value="" <?php selected( $emmwt_google_font, '' ); ?>><?php esc_html_e( 'None (Use typography style above)', 'easy-maintenance-timer' ); ?></option>
                                <option value="Inter" <?php selected( $emmwt_google_font, 'Inter' ); ?>>Inter</option>
                                <option value="Poppins" <?php selected( $emmwt_google_font, 'Poppins' ); ?>>Poppins</option>
                                <option value="Montserrat" <?php selected( $emmwt_google_font, 'Montserrat' ); ?>>Montserrat</option>
                                <option value="Roboto" <?php selected( $emmwt_google_font, 'Roboto' ); ?>>Roboto</option>
                                <option value="Playfair Display" <?php selected( $emmwt_google_font, 'Playfair Display' ); ?>>Playfair Display</option>
                                <option value="Lora" <?php selected( $emmwt_google_font, 'Lora' ); ?>>Lora</option>
                                <option value="Oswald" <?php selected( $emmwt_google_font, 'Oswald' ); ?>>Oswald</option>
                            </select>
                            <p class="description"><?php esc_html_e( 'Loads the selected web font dynamically from Google Fonts on the landing page.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'SEO Page Title', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="text" name="emmwt_seo_title" class="emmwt-dependent regular-text" value="<?php echo esc_attr( $seo_title ); ?>" placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) . ' - ' . __( 'Maintenance', 'easy-maintenance-timer' ) ); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'SEO Meta Description', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <textarea name="emmwt_seo_meta_desc" class="emmwt-dependent large-text" rows="2" placeholder="<?php esc_attr_e( 'Brief description for search engine results to maintain SEO during downtime...', 'easy-maintenance-timer' ); ?>"><?php echo esc_textarea( $seo_desc ); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Logo URL', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:10px; align-items:flex-start;">
                                <input type="text" class="emmwt-dependent regular-text" id="emmwt_logo_url" name="emmwt_logo_url" value="<?php echo esc_url( $value_logo ); ?>" placeholder="<?php esc_attr_e( 'Leave blank for clean SVG icon', 'easy-maintenance-timer' ); ?>" />
                                <button class="emmwt-dependent button" type="button" id="emmwt_logo_upload"><?php esc_html_e( 'Select', 'easy-maintenance-timer' ); ?></button>
                            </div>
                            <?php if ( $value_logo ) : ?>
                                <img src="<?php echo esc_url( $value_logo ); ?>" class="emmwt-logo-preview" alt="Logo" />
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Background Image', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:10px; align-items:flex-start;">
                                <input type="text" class="emmwt-dependent regular-text" id="emmwt_bg_url" name="emmwt_bg_url" value="<?php echo esc_url( $value_bg ); ?>" />
                                <button class="emmwt-dependent button" type="button" id="emmwt_bg_upload"><?php esc_html_e( 'Select', 'easy-maintenance-timer' ); ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Background Video URL', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="text" class="emmwt-dependent regular-text" name="emmwt_bg_video_url" value="<?php echo esc_url( get_option( 'emmwt_bg_video_url', '' ) ); ?>" placeholder="e.g. https://www.youtube.com/watch?v=... or direct MP4 link" />
                            <p class="description"><?php esc_html_e( 'Supports YouTube video links or direct links to hosted MP4 video files.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Background Overlay', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
                                <input type="text" name="emmwt_bg_overlay_color" class="emmwt-color-picker emmwt-dependent" value="<?php echo esc_attr( $bg_overlay_color ); ?>" data-default-color="#000000" />
                                
                                <div style="display:flex; align-items:center; gap:5px;">
                                    <label for="emmwt_bg_overlay_opacity"><?php esc_html_e( 'Opacity:', 'easy-maintenance-timer' ); ?></label>
                                    <input type="number" id="emmwt_bg_overlay_opacity" name="emmwt_bg_overlay_opacity" class="small-text emmwt-dependent" min="0" max="100" value="<?php echo esc_attr( $bg_overlay_opacity ); ?>" /> %
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Background Animation', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <select name="emmwt_bg_animation" class="emmwt-dependent regular-text">
                                <option value="none" <?php selected( $bg_animation, 'none' ); ?>><?php esc_html_e( 'None (Static)', 'easy-maintenance-timer' ); ?></option>
                                <option value="zoom" <?php selected( $bg_animation, 'zoom' ); ?>><?php esc_html_e( 'Slow Zoom (Ken Burns Effect - Needs Image)', 'easy-maintenance-timer' ); ?></option>
                                <option value="gradient" <?php selected( $bg_animation, 'gradient' ); ?>><?php esc_html_e( 'Animated Color Gradient (Image ignored)', 'easy-maintenance-timer' ); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e( 'Add a modern, 100% pure CSS animation to your background without slowing down the site.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Progress Bar Indicator', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_progress">
                                <input type="checkbox" id="emmwt_enable_progress" name="emmwt_enable_progress" value="1" <?php checked( 1, get_option( 'emmwt_enable_progress', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <div style="display:inline-block; margin-left: 20px;">
                                <label for="emmwt_progress_percentage"><?php esc_html_e( 'Progress Percentage:', 'easy-maintenance-timer' ); ?></label>
                                <input type="number" id="emmwt_progress_percentage" name="emmwt_progress_percentage" class="small-text emmwt-dependent" min="0" max="100" value="<?php echo esc_attr( get_option( 'emmwt_progress_percentage', 50 ) ); ?>" /> %
                            </div>
                            <p class="description"><?php esc_html_e( 'Displays a visual completion progress bar on your landing page.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Clean Data on Uninstall', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_delete_on_uninstall">
                                <input type="checkbox" id="emmwt_delete_on_uninstall" name="emmwt_delete_on_uninstall" value="1" <?php checked( 1, get_option( 'emmwt_delete_on_uninstall', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <span class="emmwt-toggle-label"><?php esc_html_e( 'ON/OFF', 'easy-maintenance-timer' ); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="tab-access" class="emmwt-tab-pane" style="display: none;">
        <div class="emmwt-card">
            <div class="emmwt-card-header">
                <h3><span class="dashicons dashicons-lock"></span> <?php esc_html_e( 'Access Control (Bypass Rules)', 'easy-maintenance-timer' ); ?></h3>
            </div>
            <div class="emmwt-card-body">
                
                <div style="background: #f0f6fc; border-left: 4px solid #72aee6; padding: 14px 18px; border-radius: 6px; margin-bottom: 24px;">
                    <strong style="color: #1e293b;"><?php esc_html_e( 'Secret Client Bypass URL:', 'easy-maintenance-timer' ); ?></strong>
                    <p style="margin: 5px 0 0 0; color: #475569;"><?php esc_html_e( 'Share this unique cryptographic token link with clients or stakeholders to bypass the maintenance screen without requiring a WordPress user login.', 'easy-maintenance-timer' ); ?></p>
                    <code style="display: block; margin-top: 10px; padding: 8px 12px; background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; color: #0f172a; word-break: break-all;"><?php echo esc_url( $bypass_url ); ?></code>
                </div>

                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Allow these roles to bypass:', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label style="display:block; margin-bottom:5px;">
                                <input type="checkbox" checked disabled> Administrator (Always Allowed)
                            </label>
                            <?php 
                            foreach ( $all_roles as $emmwt_role_slug => $emmwt_role_name ) {
                                if ( $emmwt_role_slug === 'administrator' ) continue;
                                $emmwt_checked = in_array( $emmwt_role_slug, $saved_roles, true ) ? 'checked' : '';
                                echo '<label style="display:block; margin-bottom:5px;">';
                                echo '<input type="checkbox" class="emmwt-dependent" name="emmwt_bypass_roles[]" value="' . esc_attr( $emmwt_role_slug ) . '" ' . esc_attr( $emmwt_checked ) . '>';
                                echo ' ' . esc_html( translate_user_role( $emmwt_role_name ) );
                                echo '</label>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php esc_html_e( 'IP Whitelist', 'easy-maintenance-timer' ); ?><br>
                            <small style="font-weight:normal; color:#666;"><?php esc_html_e( 'Enter one IP address per line.', 'easy-maintenance-timer' ); ?></small>
                        </th>
                        <td>
                            <textarea name="emmwt_bypass_ips" class="emmwt-dependent large-text" rows="4" placeholder="e.g. 192.168.1.1"><?php echo esc_textarea( $saved_ips ); ?></textarea>
                            <?php if ( $current_ip ) : ?>
                                <p class="description">
                                    <?php esc_html_e( 'Your current IP address is:', 'easy-maintenance-timer' ); ?> 
                                    <strong id="emmwt-current-ip"><?php echo esc_html( $current_ip ); ?></strong>
                                    <button type="button" class="button button-small" id="emmwt-add-my-ip" style="margin-left: 10px;"><?php esc_html_e( 'Add My IP', 'easy-maintenance-timer' ); ?></button>
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Passcode Bypass', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="password" name="emmwt_bypass_password" class="emmwt-dependent regular-text" value="<?php echo esc_attr( get_option( 'emmwt_bypass_password', '' ) ); ?>" placeholder="e.g. secret123" />
                            <p class="description"><?php esc_html_e( 'Allows visitors to enter this simple password on the landing page to bypass maintenance mode.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php esc_html_e( 'Page Exclusions', 'easy-maintenance-timer' ); ?><br>
                            <small style="font-weight:normal; color:#666;"><?php esc_html_e( 'Enter one URL path per line.', 'easy-maintenance-timer' ); ?></small>
                        </th>
                        <td>
                            <textarea name="emmwt_page_exclusions" class="emmwt-dependent large-text" rows="4" placeholder="e.g. /contact-us/"><?php echo esc_textarea( get_option( 'emmwt_page_exclusions', '' ) ); ?></textarea>
                            <p class="description"><?php esc_html_e( 'Specified pages will remain accessible to the public. Leave blank to block the whole site.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Block REST API', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_block_rest_api">
                                <input type="checkbox" id="emmwt_block_rest_api" name="emmwt_block_rest_api" value="1" <?php checked( 1, get_option( 'emmwt_block_rest_api', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Search Engine Bot Whitelist', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_bypass_bots">
                                <input type="checkbox" id="emmwt_bypass_bots" name="emmwt_bypass_bots" value="1" <?php checked( 1, get_option( 'emmwt_bypass_bots', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <p class="description">
                                <?php esc_html_e( 'Allows verified search engine crawlers (Googlebot, Bingbot, Baiduspider, Yandex, DuckDuckBot) to bypass the maintenance screen to prevent indexing interruptions.', 'easy-maintenance-timer' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="tab-social" class="emmwt-tab-pane" style="display: none;">
        <div class="emmwt-card">
            <div class="emmwt-card-header">
                <h3><span class="dashicons dashicons-share"></span> <?php esc_html_e( 'Social, Contact & Sharing Links', 'easy-maintenance-timer' ); ?></h3>
            </div>
            <div class="emmwt-card-body">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Contact Icons', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_social">
                                <input type="checkbox" id="emmwt_enable_social" class="emmwt-dependent" name="emmwt_enable_social" value="1" <?php checked( 1, get_option( 'emmwt_enable_social', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                        </td>
                    </tr>
                </table>
                <table class="form-table" style="margin-top: 15px; border-top: 1px solid #f0f0f1;">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Social Sharing', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_share">
                                <input type="checkbox" id="emmwt_enable_share" name="emmwt_enable_share" value="1" <?php checked( 1, get_option( 'emmwt_enable_share', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <p class="description"><?php esc_html_e( 'Displays social sharing buttons (Facebook, X/Twitter, LinkedIn, WhatsApp) on the landing page.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                </table>
                <table class="form-table" style="margin-top: 15px; border-top: 1px solid #f0f0f1;">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Landing Page Contact Form', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_contact">
                                <input type="checkbox" id="emmwt_enable_contact" name="emmwt_enable_contact" value="1" <?php checked( 1, get_option( 'emmwt_enable_contact', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <div style="margin-top: 10px;">
                                <label for="emmwt_contact_email"><?php esc_html_e( 'Contact Form Destination Email:', 'easy-maintenance-timer' ); ?></label>
                                <input type="email" id="emmwt_contact_email" name="emmwt_contact_email" class="regular-text" value="<?php echo esc_attr( get_option( 'emmwt_contact_email', get_option( 'admin_email' ) ) ); ?>" />
                            </div>
                            <p class="description"><?php esc_html_e( 'Allows visitors to submit messages directly from the maintenance page. Emails will be sent to this destination inbox.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                </table>
                
                <div id="emmwt-social-wrapper" style="display: <?php echo get_option('emmwt_enable_social', 0) ? 'block' : 'none'; ?>; padding-top: 15px; border-top: 1px solid #f0f0f1;">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Email Address', 'easy-maintenance-timer' ); ?></th>
                            <td><input type="email" name="emmwt_social_email" class="regular-text" value="<?php echo esc_attr( get_option('emmwt_social_email', '') ); ?>" placeholder="hello@yoursite.com" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'WhatsApp Number', 'easy-maintenance-timer' ); ?></th>
                            <td>
                                <?php $emmwt_clean_wa = str_replace( array('http://', 'https://'), '', get_option('emmwt_social_wa', '') ); ?>
                                <input type="text" name="emmwt_social_wa" class="regular-text" value="<?php echo esc_attr( $emmwt_clean_wa ); ?>" placeholder="e.g. +1234567890" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Facebook URL', 'easy-maintenance-timer' ); ?></th>
                            <td><input type="url" name="emmwt_social_fb" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_fb', '') ); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'X (Twitter) URL', 'easy-maintenance-timer' ); ?></th>
                            <td><input type="url" name="emmwt_social_tw" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_tw', '') ); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'LinkedIn URL', 'easy-maintenance-timer' ); ?></th>
                            <td><input type="url" name="emmwt_social_li" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_li', '') ); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Instagram URL', 'easy-maintenance-timer' ); ?></th>
                            <td><input type="url" name="emmwt_social_ig" class="regular-text" value="<?php echo esc_url( get_option('emmwt_social_ig', '') ); ?>" /></td>
                        </tr>
                    </table>
                </div>

                <table class="form-table" style="margin-top: 15px; border-top: 1px solid #f0f0f1;">
                    <tr>
                        <th colspan="2" style="padding-bottom: 5px;">
                            <h4 style="margin: 0; font-size: 15px;"><?php esc_html_e( '🖼️ Open Graph & Social Preview Card', 'easy-maintenance-timer' ); ?></h4>
                            <p class="description" style="margin-top: 3px; font-weight: normal;"><?php esc_html_e( 'Control the image, title, and description displayed when your website link is shared on WhatsApp, Facebook, X (Twitter), LinkedIn, iMessage, and Slack.', 'easy-maintenance-timer' ); ?></p>
                        </th>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Social Preview Title', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="text" name="emmwt_og_title" class="regular-text" value="<?php echo esc_attr( get_option( 'emmwt_og_title', '' ) ); ?>" placeholder="<?php esc_attr_e( 'Leave blank to use SEO Page Title', 'easy-maintenance-timer' ); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Social Preview Description', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <textarea name="emmwt_og_desc" class="large-text" rows="2" placeholder="<?php esc_attr_e( 'Leave blank to use SEO Meta Description', 'easy-maintenance-timer' ); ?>"><?php echo esc_textarea( get_option( 'emmwt_og_desc', '' ) ); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Social Banner Image', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <div style="display:flex; gap:10px; align-items:flex-start;">
                                <input type="text" class="regular-text" id="emmwt_og_image" name="emmwt_og_image" value="<?php echo esc_url( get_option( 'emmwt_og_image', '' ) ); ?>" placeholder="<?php esc_attr_e( 'Recommended: 1200x630px image', 'easy-maintenance-timer' ); ?>" />
                                <button class="button" type="button" id="emmwt_og_image_upload"><?php esc_html_e( 'Select', 'easy-maintenance-timer' ); ?></button>
                            </div>
                            <?php $emmwt_og_preview = get_option( 'emmwt_og_image', '' ); if ( $emmwt_og_preview ) : ?>
                                <img src="<?php echo esc_url( $emmwt_og_preview ); ?>" class="emmwt-og-preview" style="max-width: 220px; height: auto; margin-top: 10px; border-radius: 6px; border: 1px solid #ddd;" alt="Social Preview" />
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="tab-css" class="emmwt-tab-pane" style="display: none;">
        <div class="emmwt-card">
            <div class="emmwt-card-header">
                <h3><span class="dashicons dashicons-admin-customizer"></span> <?php esc_html_e( 'Custom Styles & Tracking Scripts', 'easy-maintenance-timer' ); ?></h3>
            </div>
            <div class="emmwt-card-body">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Custom CSS', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <textarea name="emmwt_custom_css" class="large-text" rows="6" placeholder="body.emmwt-maintenance-mode { background-color: #000; }"><?php echo esc_textarea( get_option( 'emmwt_custom_css', '' ) ); ?></textarea>
                            <p class="description"><?php esc_html_e( 'Add your own custom CSS rules to fine-tune the maintenance page styling.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Custom Tracking Scripts', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <textarea name="emmwt_custom_scripts" class="large-text" rows="5" placeholder="<?php esc_attr_e( 'Enter your Google Analytics, Meta Pixel, or custom JS tracking snippet...', 'easy-maintenance-timer' ); ?>"><?php echo esc_textarea( get_option( 'emmwt_custom_scripts', '' ) ); ?></textarea>
                            <p class="description"><?php esc_html_e( 'Injected safely into the maintenance page head for analytics and tracking.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Show "Powered by" Badge', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_credit">
                                <input type="checkbox" id="emmwt_enable_credit" class="emmwt-dependent" name="emmwt_enable_credit" value="1" <?php checked( 1, get_option( 'emmwt_enable_credit', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <p class="description"><?php esc_html_e( 'Display a subtle "Powered by Easy Maintenance Timer" badge at the bottom of the maintenance screen. Helps support our free, zero-bloat development!', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="emmwt-card" style="margin-top: 24px;">
            <div class="emmwt-card-header">
                <h3><span class="dashicons dashicons-database-export"></span> <?php esc_html_e( 'Settings Import & Export Tools', 'easy-maintenance-timer' ); ?></h3>
            </div>
            <div class="emmwt-card-body">
                <p style="margin: 0 0 16px 0; color: #475569;"><?php esc_html_e( 'Export your current configuration to a JSON backup file, or migrate settings to/from another WordPress installation.', 'easy-maintenance-timer' ); ?></p>
                <div style="display:flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=emmwt_export_settings' ), 'emmwt_export_nonce_action', 'emmwt_export_nonce' ) ); ?>" class="button button-secondary">
                        <span class="dashicons dashicons-upload"></span> 
                        <span><?php esc_html_e( 'Export Settings (JSON)', 'easy-maintenance-timer' ); ?></span>
                    </a>
                    
                    <button type="button" class="button button-secondary" onclick="document.getElementById('emmwt-import-file-input').click();">
                        <span class="dashicons dashicons-download"></span> 
                        <span><?php esc_html_e( 'Import Settings (JSON)', 'easy-maintenance-timer' ); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-subscribers" class="emmwt-tab-pane" style="display: none;">
        <div class="emmwt-card">
            <div class="emmwt-card-header">
                <h3><span class="dashicons dashicons-groups"></span> <?php esc_html_e( 'Lead Capture & Email Subscribers', 'easy-maintenance-timer' ); ?></h3>
            </div>
            <div class="emmwt-card-body">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Subscription Form', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_subscribe">
                                <input type="checkbox" id="emmwt_enable_subscribe" name="emmwt_enable_subscribe" value="1" <?php checked( 1, get_option( 'emmwt_enable_subscribe', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable GDPR Consent Checkbox', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_gdpr">
                                <input type="checkbox" id="emmwt_enable_gdpr" name="emmwt_enable_gdpr" value="1" <?php checked( 1, get_option( 'emmwt_enable_gdpr', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <div style="margin-top: 10px;">
                                <input type="text" name="emmwt_gdpr_text" class="regular-text" value="<?php echo esc_attr( get_option( 'emmwt_gdpr_text', 'I consent to having this website store my submitted email so they can send updates.' ) ); ?>" placeholder="GDPR Consent Checkbox Text..." />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Webhook Integration URL', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <input type="url" name="emmwt_webhook_url" class="regular-text" value="<?php echo esc_url( get_option( 'emmwt_webhook_url', '' ) ); ?>" placeholder="https://hooks.zapier.com/hooks/catch/..." />
                            <p class="description"><?php esc_html_e( 'Send new leads directly to Zapier, Make, Mailchimp, or custom CRM via an instant JSON POST webhook payload.', 'easy-maintenance-timer' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Subscriber Welcome Email', 'easy-maintenance-timer' ); ?></th>
                        <td>
                            <label class="emmwt-toggle" for="emmwt_enable_subscriber_email">
                                <input type="checkbox" id="emmwt_enable_subscriber_email" name="emmwt_enable_subscriber_email" value="1" <?php checked( 1, get_option( 'emmwt_enable_subscriber_email', 0 ) ); ?> />
                                <span class="emmwt-slider"></span>
                            </label>
                            <p class="description"><?php esc_html_e( 'Automatically send a confirmation email to new subscribers.', 'easy-maintenance-timer' ); ?></p>
                            
                            <div id="emmwt-subscriber-email-fields" style="margin-top: 15px; display: <?php echo get_option( 'emmwt_enable_subscriber_email', 0 ) ? 'block' : 'none'; ?>;">
                                <div style="margin-bottom: 10px;">
                                    <label for="emmwt_subscriber_email_subject" style="display:block; font-weight:600; margin-bottom:4px;"><?php esc_html_e( 'Email Subject:', 'easy-maintenance-timer' ); ?></label>
                                    <input type="text" id="emmwt_subscriber_email_subject" name="emmwt_subscriber_email_subject" class="regular-text" value="<?php echo esc_attr( get_option( 'emmwt_subscriber_email_subject', 'Thank you for subscribing! We will be live soon.' ) ); ?>" />
                                </div>
                                <div>
                                    <label for="emmwt_subscriber_email_body" style="display:block; font-weight:600; margin-bottom:4px;"><?php esc_html_e( 'Email Body:', 'easy-maintenance-timer' ); ?></label>
                                    <textarea id="emmwt_subscriber_email_body" name="emmwt_subscriber_email_body" class="large-text" rows="5"><?php echo esc_textarea( get_option( 'emmwt_subscriber_email_body', "Hello,\n\nThank you for subscribing to our updates! We are currently working hard behind the scenes and will notify you as soon as our website goes live.\n\nBest regards,\n{site_name}\n{site_url}" ) ); ?></textarea>
                                    <p class="description"><?php esc_html_e( 'Supported template tags: {site_name}, {site_url}, {email}', 'easy-maintenance-timer' ); ?></p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e2e4e7;">

                <!-- Analytics Conversion Grid -->
                <?php
                $emmwt_views             = (int) get_option( 'emmwt_views_count', 0 );
                $emmwt_subscribers_count = isset( $total_subscribers ) ? (int) $total_subscribers : ( is_array( $subscribers ) ? count( $subscribers ) : 0 );
                $emmwt_conversion_rate   = ( $emmwt_views > 0 ) ? round( ( $emmwt_subscribers_count / $emmwt_views ) * 100, 1 ) : 0;
                ?>
                <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
                    <div class="emmwt-analytics-card" style="flex: 1; min-width: 140px;">
                        <div style="font-size: 24px; font-weight: 800; color: #2563eb;"><?php echo esc_html( number_format_i18n( $emmwt_views ) ); ?></div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e( 'Total Page Views', 'easy-maintenance-timer' ); ?></div>
                    </div>
                    <div class="emmwt-analytics-card" style="flex: 1; min-width: 140px;">
                        <div style="font-size: 24px; font-weight: 800; color: #10b981;"><?php echo esc_html( number_format_i18n( $emmwt_subscribers_count ) ); ?></div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e( 'Subscribers Captured', 'easy-maintenance-timer' ); ?></div>
                    </div>
                    <div class="emmwt-analytics-card" style="flex: 1; min-width: 140px;">
                        <div style="font-size: 24px; font-weight: 800; color: #6366f1;"><?php echo esc_html( (string) $emmwt_conversion_rate ); ?>%</div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e( 'Conversion Rate', 'easy-maintenance-timer' ); ?></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h4 style="margin: 0;"><?php esc_html_e( 'Subscribers List', 'easy-maintenance-timer' ); ?></h4>
                    <?php if ( ! empty( $subscribers ) ) : ?>
                        <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=emmwt_export_csv' ), 'emmwt_export_nonce' ) ); ?>" class="button button-primary">
                            <span class="dashicons dashicons-download"></span> 
                            <span><?php esc_html_e( 'Export CSV', 'easy-maintenance-timer' ); ?></span>
                        </a>
                    <?php else : ?>
                        <button type="button" class="button button-secondary" disabled>
                            <span class="dashicons dashicons-download"></span> 
                            <span><?php esc_html_e( 'Export CSV', 'easy-maintenance-timer' ); ?></span>
                        </button>
                    <?php endif; ?>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 60px;"><?php esc_html_e( 'ID', 'easy-maintenance-timer' ); ?></th>
                            <th><?php esc_html_e( 'Email Address', 'easy-maintenance-timer' ); ?></th>
                            <th><?php esc_html_e( 'Date Subscribed', 'easy-maintenance-timer' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( ! empty( $subscribers ) ) : ?>
                            <?php 
                            foreach ( $subscribers as $emmwt_sub ) : 
                                $emmwt_formatted_date = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $emmwt_sub->subscribed_at ) );
                            ?>
                                <tr>
                                    <td><?php echo esc_html( $emmwt_sub->id ); ?></td>
                                    <td><strong><?php echo esc_html( $emmwt_sub->email ); ?></strong></td>
                                    <td><?php echo esc_html( $emmwt_formatted_date ); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px;">
                                    <?php esc_html_e( 'No subscribers yet.', 'easy-maintenance-timer' ); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="tab-support" class="emmwt-tab-pane" style="display: none;">
        <div class="emmwt-card" style="border-left: 4px solid #2271b1; padding: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; margin-bottom: 24px;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-top: 0; font-size: 1.1em;"><?php esc_html_e( 'Need Help or Want to Contribute?', 'easy-maintenance-timer' ); ?></h3>
                <p style="margin: 5px 0 0; color: #50575e;">
                    <?php esc_html_e( 'If you encounter any issues, have a feature request, or want to review the code, check out our repository or reach out directly!', 'easy-maintenance-timer' ); ?>
                </p>
            </div>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <a href="<?php echo esc_url( 'https://github.com/abdulnasir1995/easy-maintenance-timer' ); ?>" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                    <span class="dashicons dashicons-editor-code"></span> 
                    <span><?php esc_html_e( 'GitHub Repository', 'easy-maintenance-timer' ); ?></span>
                </a>
            </div>
        </div>

        <div class="emmwt-card">
            <div class="emmwt-card-header">
                <h3><span class="dashicons dashicons-format-chat"></span> <?php esc_html_e( 'Submit a Support Ticket', 'easy-maintenance-timer' ); ?></h3>
            </div>
            <div class="emmwt-card-body">
                <div id="emmwt-support-notice" style="display:none; padding:10px; margin: 15px 0; border-left:4px solid;"></div>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="emmwt_support_type"><?php esc_html_e( 'Type of Inquiry', 'easy-maintenance-timer' ); ?></label></th>
                        <td>
                            <select id="emmwt_support_type" class="regular-text">
                                <option value="Bug Report"><?php esc_html_e( 'Bug Report', 'easy-maintenance-timer' ); ?></option>
                                <option value="Feature Request"><?php esc_html_e( 'Feature Request', 'easy-maintenance-timer' ); ?></option>
                                <option value="General Support"><?php esc_html_e( 'General Support', 'easy-maintenance-timer' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="emmwt_support_message"><?php esc_html_e( 'Your Message', 'easy-maintenance-timer' ); ?></label></th>
                        <td>
                            <textarea id="emmwt_support_message" class="large-text" rows="5" placeholder="<?php esc_attr_e( 'Please describe your issue or feature request in detail...', 'easy-maintenance-timer' ); ?>"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"></th>
                        <td>
                            <button type="button" id="emmwt_submit_support" class="button button-primary">
                                <span class="dashicons dashicons-email-alt"></span> 
                                <span><?php esc_html_e( 'Send Message', 'easy-maintenance-timer' ); ?></span>
                            </button>
                            <span id="emmwt-support-spinner" class="spinner" style="float: none; margin-top: 4px;"></span>
                        </td>
                    </tr>
                </table>
                <?php wp_nonce_field( 'emmwt_support_nonce', 'emmwt_support_nonce_field' ); ?>
            </div>
        </div>
    </div>

    <!-- Premium Sticky Save Bar -->
    <div class="emmwt-sticky-footer" style="position: sticky; bottom: 0; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(8px); border-top: 1px solid #c3c4c7; box-shadow: 0 -4px 20px rgba(0,0,0,0.06); padding: 15px 20px; margin: 30px -20px -20px -20px; z-index: 999; display: flex; justify-content: space-between; align-items: center;">
        <div style="color: #646970; font-size: 13px;">
            <span class="dashicons dashicons-info" style="vertical-align: middle; margin-top: -3px; font-size: 18px;"></span>
            <span style="vertical-align: middle; margin-left: 5px;"><?php esc_html_e( 'Changes take effect immediately after saving.', 'easy-maintenance-timer' ); ?></span>
        </div>
        <div>
            <?php 
            submit_button( 
                __( 'Save All Changes', 'easy-maintenance-timer' ), 
                'primary', 
                'submit', 
                false, 
                array( 'style' => 'background: #2271b1; border: none; box-shadow: 0 4px 12px rgba(34,113,177,0.3); border-radius: 6px; padding: 0 35px; font-size: 15px; font-weight: 500; height: 42px; line-height: 42px; cursor: pointer; transition: all 0.3s ease;' ) 
            ); 
            ?>
        </div>
    </div>

</form>
    </div><!-- .emmwt-settings-panel -->
</div><!-- .emmwt-layout-wrapper -->
</div><!-- .wrap.emmwt-wrap -->

<!-- Standalone Settings Import Form -->
<form id="emmwt-import-form" method="post" enctype="multipart/form-data" style="display:none;">
    <?php wp_nonce_field( 'emmwt_import_nonce_action', 'emmwt_import_nonce' ); ?>
    <input type="file" name="emmwt_import_file" id="emmwt-import-file-input" accept=".json" onchange="document.getElementById('emmwt-import-submit-btn').click();">
    <input type="submit" name="emmwt_import_settings" id="emmwt-import-submit-btn">
</form>

<!-- Interactive In-Dashboard Live Preview Sandbox Modal -->
<div id="emmwt-sandbox-modal" style="display: none;">
    <div class="emmwt-sandbox-backdrop"></div>
    <div class="emmwt-sandbox-content">
        <div class="emmwt-sandbox-topbar">
            <div class="emmwt-sandbox-title">
                <span class="dashicons dashicons-visibility" style="vertical-align: middle; margin-top: -3px; color: #2563eb;"></span>
                <strong style="margin-left: 5px;"><?php esc_html_e( 'Live Preview Sandbox', 'easy-maintenance-timer' ); ?></strong>
                <span style="color:#64748b; font-size:12px; margin-left:8px;"><?php esc_html_e( '(Remember to save changes to see recent updates)', 'easy-maintenance-timer' ); ?></span>
            </div>
            <div class="emmwt-sandbox-devices">
                <button type="button" class="emmwt-device-btn emmwt-device-active" data-width="100%" title="<?php esc_attr_e( 'Desktop View (100%)', 'easy-maintenance-timer' ); ?>">
                    <span class="dashicons dashicons-desktop"></span> <?php esc_html_e( 'Desktop', 'easy-maintenance-timer' ); ?>
                </button>
                <button type="button" class="emmwt-device-btn" data-width="768px" title="<?php esc_attr_e( 'Tablet View (768px)', 'easy-maintenance-timer' ); ?>">
                    <span class="dashicons dashicons-tablet"></span> <?php esc_html_e( 'Tablet', 'easy-maintenance-timer' ); ?>
                </button>
                <button type="button" class="emmwt-device-btn" data-width="375px" title="<?php esc_attr_e( 'Mobile View (375px)', 'easy-maintenance-timer' ); ?>">
                    <span class="dashicons dashicons-smartphone"></span> <?php esc_html_e( 'Mobile', 'easy-maintenance-timer' ); ?>
                </button>
            </div>
            <div class="emmwt-sandbox-actions">
                <button type="button" id="emmwt-sandbox-refresh" class="button button-small" title="<?php esc_attr_e( 'Reload Preview', 'easy-maintenance-timer' ); ?>">
                    <span class="dashicons dashicons-update" style="vertical-align: middle; margin-top: -2px;"></span>
                </button>
                <button type="button" id="emmwt-sandbox-close" class="button button-small" style="font-weight: bold; margin-left: 5px;">✕</button>
            </div>
        </div>
        <div class="emmwt-sandbox-frame-wrap">
            <iframe id="emmwt-sandbox-iframe" src="<?php echo esc_url( $preview_url ); ?>" frameborder="0"></iframe>
        </div>
    </div>
</div>

