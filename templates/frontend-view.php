<?php
/**
 * Frontend Maintenance Template View
 *
 * Variables passed from controller:
 * @var string $page_title
 * @var string $seo_desc
 * @var bool   $is_gradient
 * @var string $bg_overlay_color
 * @var string $font_css
 * @var bool   $has_bg_image
 * @var string $bg_url
 * @var string $bg_animation
 * @var string $rgba
 * @var bool   $use_glass_wrapper
 * @var string $msg_color
 * @var string $desc_color
 * @var string $logo
 * @var string $msg
 * @var string $desc
 * @var string $wrapper_style
 * @var string $btn_text_color
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$emmwt_google_font = get_option( 'emmwt_font_google', '' );
$emmwt_bg_video = get_option( 'emmwt_bg_video_url', '' );
$emmwt_enable_progress = get_option( 'emmwt_enable_progress', 0 );
$emmwt_progress_percentage = (int) get_option( 'emmwt_progress_percentage', 50 );
$emmwt_enable_gdpr = get_option( 'emmwt_enable_gdpr', 0 );
$emmwt_gdpr_text = get_option( 'emmwt_gdpr_text', 'I consent to having this website store my submitted email so they can send updates.' );
$emmwt_enable_share = get_option( 'emmwt_enable_share', 0 );
$emmwt_passcode_enabled = ! empty( get_option( 'emmwt_bypass_password', '' ) );
$emmwt_enable_contact = get_option( 'emmwt_enable_contact', 0 );
$emmwt_enable_credit = (bool) get_option( 'emmwt_enable_credit', 0 );

// Open Graph & Social Preview details
$emmwt_og_title = (string) get_option( 'emmwt_og_title', '' );
if ( empty( $emmwt_og_title ) ) {
    $emmwt_og_title = $page_title;
}
$emmwt_og_desc = (string) get_option( 'emmwt_og_desc', '' );
if ( empty( $emmwt_og_desc ) ) {
    $emmwt_og_desc = ! empty( $seo_desc ) ? $seo_desc : $desc;
}
$emmwt_og_image = (string) get_option( 'emmwt_og_image', '' );
if ( empty( $emmwt_og_image ) ) {
    $emmwt_og_image = ! empty( $logo ) ? $logo : $bg_url;
}

// Determine background font family
$emmwt_font_family_css = ! empty( $emmwt_google_font ) ? '"' . esc_attr( $emmwt_google_font ) . '", sans-serif' : esc_attr( $font_css );
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

    <!-- Open Graph & Social Card Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr( $emmwt_og_title ); ?>">
    <?php if ( ! empty( $emmwt_og_desc ) ) : ?>
        <meta property="og:description" content="<?php echo esc_attr( $emmwt_og_desc ); ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?php echo esc_url( site_url() ); ?>">
    <?php if ( ! empty( $emmwt_og_image ) ) : ?>
        <meta property="og:image" content="<?php echo esc_url( $emmwt_og_image ); ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr( $emmwt_og_title ); ?>">
    <?php if ( ! empty( $emmwt_og_desc ) ) : ?>
        <meta name="twitter:description" content="<?php echo esc_attr( $emmwt_og_desc ); ?>">
    <?php endif; ?>
    <?php if ( ! empty( $emmwt_og_image ) ) : ?>
        <meta name="twitter:image" content="<?php echo esc_url( $emmwt_og_image ); ?>">
    <?php endif; ?>
    
    <?php wp_head(); ?>

    <style>
        /* BASE STRUCTURE */
        body.emmwt-maintenance-mode {
            font-family: <?php echo esc_attr( $emmwt_font_family_css ); ?>;
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

        /* VIDEO BACKGROUND LAYER */
        .emmwt-video-bg {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; z-index: -2; pointer-events: none;
        }
        .emmwt-video-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: <?php echo esc_attr( $rgba ); ?>; z-index: -1;
        }

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

        /* PROGRESS BAR */
        .emmwt-progress-bar-container {
            width: 100%; max-width: 480px; margin: 25px auto; background: rgba(255, 255, 255, 0.1);
            border-radius: 8px; height: 12px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .emmwt-progress-bar-fill {
            height: 100%; background: <?php echo esc_attr( $msg_color ); ?>;
            width: <?php echo esc_attr( $progress_percentage ); ?>%;
            transition: width 1s ease-in-out;
        }
        .emmwt-progress-text {
            font-size: 0.95em; font-weight: bold; margin-top: 5px; color: <?php echo esc_attr( $desc_color ); ?>;
        }

        /* FORM & BUTTON PROTECTION */
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

        /* GDPR */
        .emmwt-gdpr-container {
            max-width: 480px; margin: 12px auto 0; text-align: left; font-size: 0.85em; color: <?php echo esc_attr( $desc_color ); ?>;
            display: flex; gap: 8px; align-items: flex-start; line-height: 1.4;
        }
        .emmwt-gdpr-container input { margin-top: 3px; cursor: pointer; }

        /* PASSCODE FORM */
        .emmwt-passcode-toggle {
            margin-top: 30px; font-size: 0.9em; cursor: pointer; text-decoration: underline; color: <?php echo esc_attr( $desc_color ); ?>;
        }
        .emmwt-passcode-form {
            display: none; margin-top: 15px; justify-content: center; gap: 10px;
        }
        .emmwt-passcode-form input[type="password"] {
            padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.05); color: #fff; outline: none;
        }
        .emmwt-passcode-form button {
            padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold;
            background: <?php echo esc_attr( $msg_color ); ?>; color: <?php echo esc_attr( $btn_text_color ); ?>;
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

        /* LAYOUT PRESET CUSTOM OVERRIDES */
        <?php if ( $layout_preset === 'split' ) : ?>
            @media (min-width: 768px) {
                body.emmwt-maintenance-mode {
                    flex-direction: row !important; align-items: stretch !important; text-align: left !important;
                }
                .emmwt-content-wrapper {
                    width: 50% !important; max-width: 50% !important; min-height: 100vh !important;
                    margin: 0 !important; border-radius: 0 !important; border: none !important;
                    display: flex !important; flex-direction: column !important; justify-content: center !important;
                    padding: 50px 80px !important; box-shadow: none !important;
                    background: rgba(15, 23, 42, 0.95) !important;
                }
                .emmwt-video-bg, .emmwt-bg-layer, .emmwt-video-overlay {
                    width: 50% !important; left: 50% !important; height: 100% !important;
                }
                .emmwt-countdown-container, .emmwt-subscribe-form {
                    justify-content: flex-start !important; margin-left: 0 !important;
                }
                .emmwt-gdpr-container {
                    margin-left: 0 !important;
                }
            }
        <?php elseif ( $layout_preset === 'minimal' ) : ?>
            .emmwt-content-wrapper {
                background: transparent !important; box-shadow: none !important; border: none !important;
                padding: 20px !important; max-width: 600px !important;
            }
            .emmwt-subscribe-input {
                background: rgba(255, 255, 255, 0.15) !important;
                color: #fff !important;
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }
            .emmwt-subscribe-input::placeholder {
                color: rgba(255, 255, 255, 0.6) !important;
            }
        <?php endif; ?>
    </style>
</head>
<body class="emmwt-maintenance-mode">
    <?php wp_body_open(); ?>
    
    <!-- Video background handling -->
    <?php if ( ! empty( $emmwt_bg_video ) ) : ?>
        <div class="emmwt-video-overlay"></div>
        <?php if ( strpos( $emmwt_bg_video, 'youtube.com' ) !== false || strpos( $emmwt_bg_video, 'youtu.be' ) !== false ) : 
            // YouTube embed background
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $emmwt_bg_video, $match);
            $emmwt_youtube_id = ! empty($match[1]) ? $match[1] : '';
        ?>
            <iframe class="emmwt-video-bg" src="https://www.youtube.com/embed/<?php echo esc_attr($emmwt_youtube_id); ?>?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&mute=1&playlist=<?php echo esc_attr($emmwt_youtube_id); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        <?php else : ?>
            <video class="emmwt-video-bg" autoplay loop muted playsinline>
                <source src="<?php echo esc_url($emmwt_bg_video); ?>" type="video/mp4">
            </video>
        <?php endif; ?>
    <?php elseif ( ! $is_gradient ) : ?>
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

        <!-- Progress Bar Indicator -->
        <?php if ( $emmwt_enable_progress ) : ?>
            <div class="emmwt-progress-bar-container">
                <div class="emmwt-progress-bar-fill"></div>
            </div>
            <div class="emmwt-progress-text"><?php echo esc_html( $emmwt_progress_percentage ); ?>% <?php esc_html_e( 'Completed', 'easy-maintenance-timer' ); ?></div>
        <?php endif; ?>

        <!-- Lead Capture Form -->
        <?php if ( get_option( 'emmwt_enable_subscribe', 0 ) ) : ?>
            <div class="emmwt-subscribe-wrapper" style="margin-top: 35px; margin-bottom: 10px; width: 100%;">
                <form id="emmwt-subscribe-form" class="emmwt-subscribe-form">
                    <input type="email" id="emmwt-subscribe-email" class="emmwt-subscribe-input" placeholder="<?php esc_attr_e( 'Enter your email address...', 'easy-maintenance-timer' ); ?>" required>
                    
                    <!-- Honeypot Field -->
                    <input type="text" name="emmwt_hp" class="emmwt-hp-field" style="display:none !important;" tabindex="-1" autocomplete="off">
                    
                    <button type="submit" id="emmwt-subscribe-btn" class="emmwt-subscribe-btn">
                        <?php esc_html_e( 'Notify Me', 'easy-maintenance-timer' ); ?>
                    </button>
                </form>
                
                <?php if ( $emmwt_enable_gdpr ) : ?>
                    <div class="emmwt-gdpr-container">
                        <input type="checkbox" id="emmwt-gdpr-checkbox" required>
                        <label for="emmwt-gdpr-checkbox"><?php echo esc_html( $emmwt_gdpr_text ); ?></label>
                    </div>
                <?php endif; ?>

                <div id="emmwt-subscribe-msg" style="margin-top: 15px; font-size: 1em; display: none; font-weight: 500; text-shadow: none !important;"></div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var form = document.getElementById('emmwt-subscribe-form');
                if(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        <?php if ( $emmwt_enable_gdpr ) : ?>
                        var gdprCheck = document.getElementById('emmwt-gdpr-checkbox');
                        if (gdprCheck && !gdprCheck.checked) {
                            return; 
                        }
                        <?php endif; ?>

                        var btn = document.getElementById('emmwt-subscribe-btn');
                        var msgBox = document.getElementById('emmwt-subscribe-msg');
                        btn.disabled = true; btn.style.opacity = '0.6'; msgBox.style.display = 'none';
                        
                        var formData = new FormData();
                        formData.append('action', 'emmwt_subscribe_email');
                        formData.append('email', document.getElementById('emmwt-subscribe-email').value);
                        formData.append('emmwt_hp', form.querySelector('[name="emmwt_hp"]').value);
                        formData.append('security', '<?php echo esc_js( wp_create_nonce( "emmwt_subscribe_nonce" ) ); ?>');

                        fetch('<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>', { method: 'POST', body: formData })
                        .then(function(res) { return res.json(); })
                        .then(function(data) {
                            btn.disabled = false; btn.style.opacity = '1'; msgBox.style.display = 'block';
                            msgBox.textContent = data.data || '';
                            if (data.success) {
                                msgBox.style.color = '<?php echo $use_glass_wrapper ? '#a5d6a7' : '#4caf50'; ?>';
                                document.getElementById('emmwt-subscribe-email').value = '';
                                if (document.getElementById('emmwt-gdpr-checkbox')) {
                                    document.getElementById('emmwt-gdpr-checkbox').checked = false;
                                }
                            } else {
                                msgBox.style.color = '<?php echo $use_glass_wrapper ? '#ef9a9a' : '#f44336'; ?>';
                            }
                        }).catch(function() {
                            btn.disabled = false; btn.style.opacity = '1'; msgBox.style.display = 'block';
                            msgBox.style.color = '<?php echo $use_glass_wrapper ? '#ef9a9a' : '#f44336'; ?>';
                            msgBox.textContent = '<?php esc_html_e( "Network error.", "easy-maintenance-timer" ); ?>';
                        });
                    });
                }
            });
            </script>
        <?php endif; ?>

        <!-- Contact Form Section -->
        <?php if ( $emmwt_enable_contact ) : ?>
            <div class="emmwt-contact-wrapper" style="margin-top: 30px; width: 100%;">
                <div id="emmwt-contact-toggle" style="cursor:pointer; text-decoration:underline; font-size:0.95em; color: <?php echo esc_attr( $desc_color ); ?>;" onclick="document.getElementById('emmwt-contact-form-container').style.display='block'; this.style.display='none';">
                    <?php esc_html_e( 'Contact Us', 'easy-maintenance-timer' ); ?>
                </div>
                <div id="emmwt-contact-form-container" style="display:none; max-width:480px; margin: 15px auto 0; text-align:left; background:rgba(255,255,255,0.05); padding:20px; border-radius:8px; border:1px solid rgba(255,255,255,0.1);">
                    <form id="emmwt-contact-form" style="display:flex; flex-direction:column; gap:10px;">
                        <input type="text" id="emmwt-contact-name" placeholder="<?php esc_attr_e( 'Your Name', 'easy-maintenance-timer' ); ?>" style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.1); color:#fff; font-family: inherit;" required>
                        <input type="email" id="emmwt-contact-email" placeholder="<?php esc_attr_e( 'Your Email Address', 'easy-maintenance-timer' ); ?>" style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.1); color:#fff; font-family: inherit;" required>
                        <textarea id="emmwt-contact-message" rows="3" placeholder="<?php esc_attr_e( 'How can we help?', 'easy-maintenance-timer' ); ?>" style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.1); color:#fff; font-family: inherit;" required></textarea>
                        
                        <!-- Honeypot -->
                        <input type="text" name="emmwt_hp" class="emmwt-hp-field" style="display:none !important;" tabindex="-1" autocomplete="off">
                        
                        <button type="submit" id="emmwt-contact-btn" style="padding:12px; border-radius:6px; border:none; cursor:pointer; font-weight:bold; background:<?php echo esc_attr( $msg_color ); ?>; color:<?php echo esc_attr( $btn_text_color ); ?>; font-family: inherit;">
                            <?php esc_html_e( 'Send Message', 'easy-maintenance-timer' ); ?>
                        </button>
                    </form>
                    <div id="emmwt-contact-msg" style="margin-top: 15px; font-size: 0.95em; font-weight: 500; display: none;"></div>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var cForm = document.getElementById('emmwt-contact-form');
                if (cForm) {
                    cForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        var btn = document.getElementById('emmwt-contact-btn');
                        var msgBox = document.getElementById('emmwt-contact-msg');
                        btn.disabled = true; btn.style.opacity = '0.6'; msgBox.style.display = 'none';

                        var formData = new FormData();
                        formData.append('action', 'emmwt_submit_contact');
                        formData.append('name', document.getElementById('emmwt-contact-name').value);
                        formData.append('email', document.getElementById('emmwt-contact-email').value);
                        formData.append('message', document.getElementById('emmwt-contact-message').value);
                        formData.append('emmwt_hp', cForm.querySelector('[name="emmwt_hp"]').value);
                        formData.append('security', '<?php echo esc_js( wp_create_nonce( "emmwt_contact_nonce" ) ); ?>');

                        fetch('<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>', { method: 'POST', body: formData })
                        .then(function(res) { return res.json(); })
                        .then(function(data) {
                            btn.disabled = false; btn.style.opacity = '1'; msgBox.style.display = 'block';
                            msgBox.textContent = data.data || '';
                            if (data.success) {
                                msgBox.style.color = '<?php echo $use_glass_wrapper ? '#a5d6a7' : '#4caf50'; ?>';
                                document.getElementById('emmwt-contact-name').value = '';
                                document.getElementById('emmwt-contact-email').value = '';
                                document.getElementById('emmwt-contact-message').value = '';
                            } else {
                                msgBox.style.color = '<?php echo $use_glass_wrapper ? '#ef9a9a' : '#f44336'; ?>';
                            }
                        }).catch(function() {
                            btn.disabled = false; btn.style.opacity = '1'; msgBox.style.display = 'block';
                            msgBox.style.color = '<?php echo $use_glass_wrapper ? '#ef9a9a' : '#f44336'; ?>';
                            msgBox.textContent = '<?php esc_html_e( "Network error.", "easy-maintenance-timer" ); ?>';
                        });
                    });
                }
            });
            </script>
        <?php endif; ?>

        <?php if ( get_option( 'emmwt_enable_social', 0 ) ) : ?>
            <div class="emmwt-social-icons" style="margin-top: 25px; display: flex; justify-content: center; gap: 15px;">
                <?php 
                $emmwt_socials = [
                    'email' => ['url' => get_option('emmwt_social_email', ''), 'prefix' => 'mailto:', 'svg' => '<path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>'],
                    'wa'    => ['url' => preg_replace('/[^0-9]/', '', get_option('emmwt_social_wa', '')), 'prefix' => 'https://wa.me/', 'svg' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>'],
                    'fb'    => ['url' => get_option('emmwt_social_fb', ''), 'prefix' => '', 'svg' => '<path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/>'],
                    'tw'    => ['url' => get_option('emmwt_social_tw', ''), 'prefix' => '', 'svg' => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>'],
                    'ig'    => ['url' => get_option('emmwt_social_ig', ''), 'prefix' => '', 'svg' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>'],
                    'li'    => ['url' => get_option('emmwt_social_li', ''), 'prefix' => '', 'svg' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>']
                ];
                foreach ( $emmwt_socials as $emmwt_key => $emmwt_data ) {
                    if ( ! empty( $emmwt_data['url'] ) ) {
                        $emmwt_link = ( $emmwt_key === 'email' || $emmwt_key === 'wa' ) ? $emmwt_data['prefix'] . $emmwt_data['url'] : $emmwt_data['url'];
                        echo '<a href="' . esc_url( $emmwt_link ) . '" target="_blank" rel="noopener noreferrer" class="emmwt-social-icon">';
                        echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
                        echo wp_kses( $emmwt_data['svg'], array( 'path' => array( 'd' => true ) ) );
                        echo '</svg></a>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Social Sharing Feature -->
        <?php if ( $emmwt_enable_share ) : 
            $emmwt_share_url   = esc_url( site_url() );
            $emmwt_share_title = rawurlencode( $page_title );
        ?>
            <div style="margin-top: 30px;">
                <p style="font-size: 0.9em; margin-bottom: 10px; color: <?php echo esc_attr( $desc_color ); ?>;"><?php esc_html_e( 'Share this page:', 'easy-maintenance-timer' ); ?></p>
                <div style="display: flex; justify-content: center; gap: 10px;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $emmwt_share_url ); ?>" target="_blank" rel="noopener noreferrer" class="emmwt-social-icon" title="<?php esc_attr_e( 'Share on Facebook', 'easy-maintenance-timer' ); ?>">
                        <svg viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1V12h3v3h-3v6.8c4.56-.93 8-4.96 8-9.8z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url( $emmwt_share_url ); ?>&text=<?php echo esc_attr( $emmwt_share_title ); ?>" target="_blank" rel="noopener noreferrer" class="emmwt-social-icon" title="<?php esc_attr_e( 'Share on X', 'easy-maintenance-timer' ); ?>">
                        <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_url( $emmwt_share_url ); ?>" target="_blank" rel="noopener noreferrer" class="emmwt-social-icon" title="<?php esc_attr_e( 'Share on LinkedIn', 'easy-maintenance-timer' ); ?>">
                        <svg viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo esc_attr( $emmwt_share_title ); ?>%20<?php echo esc_url( $emmwt_share_url ); ?>" target="_blank" rel="noopener noreferrer" class="emmwt-social-icon" title="<?php esc_attr_e( 'Share on WhatsApp', 'easy-maintenance-timer' ); ?>">
                        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Passcode Bypass form -->
        <?php if ( $emmwt_passcode_enabled ) : ?>
            <div class="emmwt-passcode-toggle" onclick="document.getElementById('emmwt-passcode-form').style.display = 'flex'; this.style.display = 'none';">
                <?php esc_html_e( 'Enter passcode to bypass', 'easy-maintenance-timer' ); ?>
            </div>
            <form id="emmwt-passcode-form" class="emmwt-passcode-form" method="POST">
                <?php wp_nonce_field( 'emmwt_passcode_action', 'emmwt_passcode_nonce' ); ?>
                <input type="password" name="emmwt_passcode" placeholder="<?php esc_attr_e( 'Enter passcode...', 'easy-maintenance-timer' ); ?>" required>
                <button type="submit"><?php esc_html_e( 'Submit', 'easy-maintenance-timer' ); ?></button>
            </form>
        <?php endif; ?>

        <?php if ( $emmwt_enable_credit ) : ?>
            <div class="emmwt-credit-badge" style="margin-top: 25px; font-size: 11px; opacity: 0.7; text-align: center;">
                <a href="<?php echo esc_url( 'https://wordpress.org/plugins/easy-maintenance-timer/' ); ?>" target="_blank" rel="noopener noreferrer" style="color: <?php echo esc_attr( $desc_color ); ?>; text-decoration: none; font-weight: 500;">
                    <?php esc_html_e( 'Powered by Easy Maintenance Timer', 'easy-maintenance-timer' ); ?>
                </a>
            </div>
        <?php endif; ?>

    </div> 
    <?php wp_footer(); ?>
</body>
</html>
