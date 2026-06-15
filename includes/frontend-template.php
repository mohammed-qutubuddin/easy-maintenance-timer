<?php
/**
 * Frontend Maintenance Template Renderer
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

function emmwt_frontend_maintenance_redirect() {
    
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $is_preview = ( isset( $_GET['emmwt_preview'] ) && $_GET['emmwt_preview'] === 'true' && current_user_can( 'manage_options' ) );

    if ( ! get_option( 'emmwt_enabled', 0 ) && ! $is_preview ) return;

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( isset( $_GET['emmwt_bypass'] ) && $_GET['emmwt_bypass'] === 'true' ) {
        setcookie( 'emmwt_bypass_token', '1', time() + ( 86400 * 7 ), COOKIEPATH, COOKIE_DOMAIN );
        wp_safe_redirect( remove_query_arg( 'emmwt_bypass' ) );
        exit;
    }

    if ( emmwt_check_bypass_access() ) return;

    if ( $is_preview ) {
        add_filter( 'show_admin_bar', '__return_false', 99 );
        remove_action( 'wp_head', '_admin_bar_bump_cb' );
    }

    $date = (string) get_option( 'emmwt_countdown_date', '' );
    $seconds_remaining = 3600; 
    
    if ( ! empty( $date ) ) {
        $expiry_timestamp  = strtotime( $date );
        $current_timestamp = current_time( 'timestamp' );
        
        if ( $expiry_timestamp && $current_timestamp >= $expiry_timestamp ) {
            $seconds_remaining = 0; 
        } else {
            $seconds_remaining = $expiry_timestamp - $current_timestamp;
        }
    }

    // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
    if ( ! defined( 'DONOTCACHEPAGE' ) ) define( 'DONOTCACHEPAGE', true );
    if ( ! defined( 'DONOTCACHEOBJECT' ) ) define( 'DONOTCACHEOBJECT', true );
    if ( ! defined( 'DONOTMINIFY' ) ) define( 'DONOTMINIFY', true );
    // phpcs:enable

    nocache_headers(); 

    $status_type = get_option( 'emmwt_status_type', 'maintenance' );
    if ( $status_type === 'coming_soon' ) {
        status_header( 200 );
    } else {
        status_header( 503 );
        header( 'Retry-After: ' . max( 60, $seconds_remaining ) ); 
    }
    
    $msg                = (string) get_option( 'emmwt_maint_message', 'Site Under Maintenance. Please check back soon.' );
    $desc               = (string) get_option( 'emmwt_maint_description', '' );
    $msg_color          = (string) get_option( 'emmwt_msg_color', '#000000' );
    $desc_color         = (string) get_option( 'emmwt_desc_color', '#50575e' );
    $logo               = (string) get_option( 'emmwt_logo_url', '' );
    $bg_url             = (string) get_option( 'emmwt_bg_url', '' );
    $bg_overlay_color   = (string) get_option( 'emmwt_bg_overlay_color', '#000000' );
    $bg_overlay_opacity = (int) get_option( 'emmwt_bg_overlay_opacity', 50 );
    $bg_animation       = get_option( 'emmwt_bg_animation', 'none' );

    $seo_title  = (string) get_option( 'emmwt_seo_title', '' );
    $seo_desc   = (string) get_option( 'emmwt_seo_meta_desc', '' );
    $page_title = ! empty( $seo_title ) ? $seo_title : get_bloginfo( 'name' ) . ' - ' . __( 'Maintenance', 'easy-maintenance-timer' );

    $font_setting = get_option( 'emmwt_font_family', 'system' );
    $font_css = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif'; 
    if ( $font_setting === 'sans-serif' ) {
        $font_css = '"Helvetica Neue", Helvetica, Arial, sans-serif';
    } elseif ( $font_setting === 'serif' ) {
        $font_css = 'Georgia, "Times New Roman", Times, serif';
    } elseif ( $font_setting === 'monospace' ) {
        $font_css = 'Menlo, Monaco, Consolas, "Courier New", monospace';
    }

    $hex = ltrim( $bg_overlay_color, '#' );
    if ( empty( $hex ) ) $hex = '000000';
    $r = hexdec( strlen( $hex ) == 3 ? str_repeat( substr( $hex, 0, 1 ), 2 ) : substr( $hex, 0, 2 ) );
    $g = hexdec( strlen( $hex ) == 3 ? str_repeat( substr( $hex, 1, 1 ), 2 ) : substr( $hex, 2, 2 ) );
    $b = hexdec( strlen( $hex ) == 3 ? str_repeat( substr( $hex, 2, 1 ), 2 ) : substr( $hex, 4, 2 ) );
    $alpha = $bg_overlay_opacity / 100;
    $rgba = "rgba($r, $g, $b, $alpha)";

    // --- SMART UI LOGIC (Fix for Missing Box over Images) ---
    $has_bg_image = ! empty( $bg_url );
    $is_gradient = ( $bg_animation === 'gradient' );
    $use_glass_wrapper = ( $has_bg_image || $is_gradient );

    if ( $use_glass_wrapper ) {
        // Apply Premium Frosted Glass Box if Image or Gradient is used
        $wrapper_style = "width: 90%; max-width: 650px; padding: 45px 30px; box-sizing: border-box; margin: auto; background: rgba(15, 23, 42, 0.65); border-radius: 16px; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);";
        // Force text to White for perfect contrast on Glass Box
        $msg_color = '#ffffff';
        $desc_color = 'rgba(255, 255, 255, 0.85)';
    } else {
        $wrapper_style = "width: 100%; max-width: 800px; padding: 20px; box-sizing: border-box; margin: auto;";
    }

    // --- BUTTON CONTRAST MATH ---
    $btn_hex = ltrim( $msg_color, '#' );
    if ( empty( $btn_hex ) ) $btn_hex = '000000';
    $r_btn = hexdec( strlen( $btn_hex ) == 3 ? str_repeat( substr( $btn_hex, 0, 1 ), 2 ) : substr( $btn_hex, 0, 2 ) );
    $g_btn = hexdec( strlen( $btn_hex ) == 3 ? str_repeat( substr( $btn_hex, 1, 1 ), 2 ) : substr( $btn_hex, 2, 2 ) );
    $b_btn = hexdec( strlen( $btn_hex ) == 3 ? str_repeat( substr( $btn_hex, 2, 1 ), 2 ) : substr( $btn_hex, 4, 2 ) );
    $brightness = ( ( $r_btn * 299 ) + ( $g_btn * 587 ) + ( $b_btn * 114 ) ) / 1000;
    
    // If msg_color (Button BG) is Light, use Dark text. If Dark, use White text.
    $btn_text_color = ( $brightness > 128 ) ? '#0f172a' : '#ffffff'; 

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo esc_html( $page_title ); ?></title>
        <?php if ( ! empty( $seo_desc ) ) : ?>
            <meta name="description" content="<?php echo esc_attr( $seo_desc ); ?>">
        <?php endif; ?>
        <?php wp_head(); ?>

        <style>
            /* BASE STRUCTURE */
            body.emmwt-maintenance-mode {
                font-family: <?php echo esc_attr( $font_css ); ?>;
                margin: 0; padding: 0; min-height: 100vh;
                display: flex; flex-direction: column;
                align-items: center; justify-content: center; text-align: center;
                position: relative; z-index: 1; overflow: hidden;
                
                <?php if ( $is_gradient ) : ?>
                    background: linear-gradient(270deg, #1e3c72, #2a5298, #6dd5ed);
                    background-size: 300% 300%;
                    animation: emmwtBgAnimation 15s ease infinite;
                <?php else : ?>
                    background-color: <?php echo esc_attr( $bg_overlay_color ); ?>;
                <?php endif; ?>
            }

            <?php if ( $is_gradient ) : ?>
            @keyframes emmwtBgAnimation {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            <?php endif; ?>

            <?php if ( ! $is_gradient ) : ?>
            .emmwt-bg-layer {
                position: absolute; top: -5%; left: -5%; right: -5%; bottom: -5%; z-index: -1;
                <?php if ( $has_bg_image ) : ?>
                    background: linear-gradient(<?php echo esc_attr( $rgba ); ?>, <?php echo esc_attr( $rgba ); ?>), url('<?php echo esc_url( $bg_url ); ?>') no-repeat center center;
                    background-size: cover;
                <?php else : ?>
                    background-color: <?php echo esc_attr( $bg_overlay_color ); ?>;
                <?php endif; ?>
            }
            <?php endif; ?>

            <?php if ( $bg_animation === 'zoom' && $has_bg_image ) : ?>
            .emmwt-bg-layer { animation: emmwt-zoom 25s ease-in-out infinite alternate; }
            @keyframes emmwt-zoom { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }
            <?php endif; ?>

            /* TYPOGRAPHY PROTECTION */
            .emmwt-maintenance-mode h1, .emmwt-time-val {
                color: <?php echo esc_attr( $msg_color ); ?> !important;
                <?php if ( $use_glass_wrapper ) echo "text-shadow: 0 2px 4px rgba(0,0,0,0.4) !important;"; ?>
            }
            .emmwt-maintenance-mode p, .emmwt-time-label, .emmwt-complete-msg {
                color: <?php echo esc_attr( $desc_color ); ?> !important;
                <?php if ( $use_glass_wrapper ) echo "text-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;"; ?>
            }
            .emmwt-default-logo { fill: <?php echo esc_attr( $msg_color ); ?> !important; }

            /* FORM & BUTTON PROTECTION (Fix for Theme CSS overrides) */
            .emmwt-subscribe-form {
                display: flex; justify-content: center; max-width: 480px; margin: 0 auto; gap: 10px; flex-wrap: wrap;
            }
            .emmwt-subscribe-input {
                flex: 1 !important; padding: 14px 18px !important; border: 1px solid rgba(0,0,0,0.1) !important;
                border-radius: 8px !important; font-size: 16px !important; min-width: 220px !important; outline: none !important;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05) inset !important; color: #1e293b !important;
                background: #ffffff !important; margin: 0 !important; height: auto !important; line-height: normal !important;
            }
            .emmwt-subscribe-input:focus {
                border-color: <?php echo esc_attr( $msg_color ); ?> !important;
                box-shadow: 0 0 0 3px rgba(255,255,255,0.2) !important;
            }
            
            /* The Button Fix: Using 'background' instead of 'background-color' beats aggressive themes */
            .emmwt-subscribe-btn {
                background: <?php echo esc_attr( $msg_color ); ?> !important;
                color: <?php echo esc_attr( $btn_text_color ); ?> !important;
                border: 1px solid <?php echo esc_attr( $msg_color ); ?> !important;
                padding: 14px 28px !important; border-radius: 8px !important; font-size: 16px !important; font-weight: bold !important;
                cursor: pointer !important; transition: all 0.3s ease !important; text-transform: none !important;
                text-shadow: none !important; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
                line-height: normal !important; margin: 0 !important; height: auto !important;
            }
            .emmwt-subscribe-btn:hover {
                opacity: 0.9 !important; transform: translateY(-2px) !important; box-shadow: 0 6px 12px rgba(0,0,0,0.15) !important;
            }

            /* SOCIAL ICONS */
            .emmwt-social-icon {
                display: flex !important; align-items: center !important; justify-content: center !important;
                width: 44px !important; height: 44px !important; border-radius: 50% !important;
                border: 1px solid <?php echo $use_glass_wrapper ? 'rgba(255,255,255,0.2)' : esc_attr($desc_color); ?> !important;
                background: <?php echo $use_glass_wrapper ? 'rgba(255,255,255,0.05)' : 'transparent'; ?> !important;
                transition: all 0.3s ease !important; text-decoration: none !important;
            }
            .emmwt-social-icon svg {
                width: 20px !important; height: 20px !important; fill: <?php echo esc_attr( $desc_color ); ?> !important;
                transition: all 0.3s ease !important;
            }
            .emmwt-social-icon:hover {
                transform: translateY(-4px) !important;
                background: <?php echo esc_attr( $msg_color ); ?> !important;
                border-color: <?php echo esc_attr( $msg_color ); ?> !important;
            }
            .emmwt-social-icon:hover svg {
                fill: <?php echo esc_attr( $btn_text_color ); ?> !important;
            }
        </style>
    </head>
    <body class="emmwt-maintenance-mode">
        <?php wp_body_open(); ?>
        
        <?php if ( ! $is_gradient ) : ?>
            <div class="emmwt-bg-layer"></div>
        <?php endif; ?>
        
        <div class="emmwt-content-wrapper" style="<?php echo esc_attr( $wrapper_style ); ?>">
            
            <?php if ( $logo ) : ?>
                <img src="<?php echo esc_url( $logo ); ?>" alt="Logo" style="max-width:150px; margin-bottom: 20px;">
            <?php else : ?>
                <svg class="emmwt-default-logo" style="width:80px; height:80px; margin-bottom:20px; opacity:0.9;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
                    <path d="M13 7h-2v6h6v-2h-4z"></path>
                </svg>
            <?php endif; ?>

            <h1 style="font-weight: bold; font-size: 2.8em; margin-top: 0;"><?php echo esc_html( $msg ); ?></h1>
            
            <?php if ( $desc ) : ?>
                <p style="font-size: 1.2em; margin-top: 10px; margin-bottom: 25px; line-height: 1.6;">
                    <?php echo nl2br( esc_html( $desc ) ); ?>
                </p>
            <?php endif; ?>

            <div id="emmwt_countdown"></div>

            <?php if ( get_option( 'emmwt_enable_subscribe', 0 ) ) : ?>
                <div class="emmwt-subscribe-wrapper" style="margin-top: 35px; margin-bottom: 10px; width: 100%;">
                    <form id="emmwt-subscribe-form" class="emmwt-subscribe-form">
                        <input type="email" id="emmwt-subscribe-email" class="emmwt-subscribe-input" placeholder="<?php esc_attr_e( 'Enter your email address...', 'easy-maintenance-timer' ); ?>" required>
                        <button type="submit" id="emmwt-subscribe-btn" class="emmwt-subscribe-btn">
                            <?php esc_html_e( 'Notify Me', 'easy-maintenance-timer' ); ?>
                        </button>
                    </form>
                    <div id="emmwt-subscribe-msg" style="margin-top: 15px; font-size: 1em; display: none; font-weight: 500; text-shadow: none !important;"></div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var form = document.getElementById('emmwt-subscribe-form');
                    if(form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            var btn = document.getElementById('emmwt-subscribe-btn');
                            var msgBox = document.getElementById('emmwt-subscribe-msg');
                            btn.disabled = true; btn.style.opacity = '0.6'; msgBox.style.display = 'none';
                            
                            var formData = new FormData();
                            formData.append('action', 'emmwt_subscribe_email');
                            formData.append('email', document.getElementById('emmwt-subscribe-email').value);
                            formData.append('security', '<?php echo esc_js( wp_create_nonce( "emmwt_subscribe_nonce" ) ); ?>');

                            fetch('<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>', { method: 'POST', body: formData })
                            .then(function(res) { return res.json(); })
                            .then(function(data) {
                                btn.disabled = false; btn.style.opacity = '1'; msgBox.style.display = 'block';
                                if(data.success) {
                                    msgBox.innerHTML = '<span style="color: <?php echo $use_glass_wrapper ? '#a5d6a7' : '#4caf50'; ?>;">' + data.data + '</span>';
                                    document.getElementById('emmwt-subscribe-email').value = '';
                                } else {
                                    msgBox.innerHTML = '<span style="color: <?php echo $use_glass_wrapper ? '#ef9a9a' : '#f44336'; ?>;">' + data.data + '</span>';
                                }
                            }).catch(function() {
                                btn.disabled = false; btn.style.opacity = '1'; msgBox.style.display = 'block';
                                msgBox.innerHTML = '<span style="color: <?php echo $use_glass_wrapper ? '#ef9a9a' : '#f44336'; ?>;"><?php esc_html_e( "Network error.", "easy-maintenance-timer" ); ?></span>';
                            });
                        });
                    }
                });
                </script>
            <?php endif; ?>

            <?php if ( get_option( 'emmwt_enable_social', 0 ) ) : ?>
                <div class="emmwt-social-icons" style="margin-top: 25px; display: flex; justify-content: center; gap: 15px;">
                    <?php 
                    $socials = [
                        'email' => ['url' => get_option('emmwt_social_email', ''), 'prefix' => 'mailto:', 'svg' => '<path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>'],
                        'wa'    => ['url' => preg_replace('/[^0-9]/', '', get_option('emmwt_social_wa', '')), 'prefix' => 'https://wa.me/', 'svg' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>'],
                        'fb'    => ['url' => get_option('emmwt_social_fb', ''), 'prefix' => '', 'svg' => '<path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/>'],
                        'tw'    => ['url' => get_option('emmwt_social_tw', ''), 'prefix' => '', 'svg' => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>'],
                        'ig'    => ['url' => get_option('emmwt_social_ig', ''), 'prefix' => '', 'svg' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>'],
                        'li'    => ['url' => get_option('emmwt_social_li', ''), 'prefix' => '', 'svg' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>']
                    ];
                    foreach ($socials as $key => $data) {
                        if ( !empty($data['url']) ) {
                            $link = $key === 'email' || $key === 'wa' ? $data['prefix'] . $data['url'] : $data['url'];
                            echo '<a href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer" class="emmwt-social-icon">';
                            echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
                            echo wp_kses( $data['svg'], array( 'path' => array( 'd' => true ) ) );
                            echo '</svg></a>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>

        </div> 
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    exit;
}
add_action( 'template_redirect', 'emmwt_frontend_maintenance_redirect' );