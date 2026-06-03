<?php
/**
 * Deactivation Feedback Modal and Email Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// 1. Output the HTML, CSS, and JS only on the Plugins page
add_action( 'admin_footer-plugins.php', 'emmwt_deactivation_popup_html' );
function emmwt_deactivation_popup_html() {
    $plugin_slug = 'easy-maintenance-timer/easy-maintenance-timer.php';
    ?>
    <style>
        #emmwt-deactivate-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        #emmwt-deactivate-modal {
            background: #fff;
            padding: 30px;
            width: 100%;
            max-width: 550px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        #emmwt-deactivate-modal h3 {
            margin-top: 0;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .emmwt-reason-option {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .emmwt-reason-option input[type="radio"] {
            margin-right: 10px;
        }
        .emmwt-modal-footer {
            margin-top: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .emmwt-btn-submit {
            background: #3b5998; 
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
        }
        .emmwt-btn-submit:hover {
            background: #2d4373;
            color: #fff;
        }
        .emmwt-btn-skip {
            background: transparent;
            color: #3b5998;
            border: 1px solid #3b5998;
            padding: 9px 19px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
        }
        .emmwt-btn-skip:hover {
            background: #f0f4f8;
            color: #3b5998;
        }
        .emmwt-btn-cancel {
            margin-left: auto;
            color: #3b5998;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>

    <div id="emmwt-deactivate-overlay">
        <div id="emmwt-deactivate-modal">
            <h3>If you have a moment, please let us know why you are deactivating:</h3>
            
            <form id="emmwt-deactivate-form">
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="I stopped using Easy Maintenance Timer on my site" checked>
                    I stopped using Easy Maintenance Timer on my site
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="Other reason">
                    Other reason
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="It is only temporary">
                    It is only temporary
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="I switched to another plugin">
                    I switched to another plugin
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="Technical Issue">
                    Technical Issue
                </label>
                <label class="emmwt-reason-option">
                    <input type="radio" name="emmwt_reason" value="I miss a feature">
                    I miss a feature
                </label>
                
                <div class="emmwt-modal-footer">
                    <button type="submit" class="emmwt-btn-submit" id="emmwt-submit-deactivate">Submit & Deactivate</button>
                    <button type="button" class="emmwt-btn-skip" id="emmwt-only-deactivate">Only Deactivate</button>
                    <a class="emmwt-btn-cancel" id="emmwt-cancel-deactivate">Don't deactivate</a>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var deactivationLink = '';
            var pluginSlug = '<?php echo esc_js( $plugin_slug ); ?>';
            
            // Intercept the specific plugin's deactivate link
            $('#the-list').on('click', 'a[id*="deactivate-' + pluginSlug.split('/')[0] + '"]', function(e) {
                e.preventDefault();
                deactivationLink = $(this).attr('href');
                $('#emmwt-deactivate-overlay').css('display', 'flex');
            });

            // Close modal / Cancel
            $('#emmwt-cancel-deactivate, #emmwt-deactivate-overlay').on('click', function(e) {
                if (e.target === this) {
                    $('#emmwt-deactivate-overlay').hide();
                }
            });

            // Only Deactivate (Skip feedback)
            $('#emmwt-only-deactivate').on('click', function(e) {
                e.preventDefault();
                window.location.href = deactivationLink;
            });

            // Submit & Deactivate
            $('#emmwt-deactivate-form').on('submit', function(e) {
                e.preventDefault();
                
                var submitBtn = $('#emmwt-submit-deactivate');
                submitBtn.text('Submitting...').prop('disabled', true);
                
                var reason = $('input[name="emmwt_reason"]:checked').val();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'emmwt_submit_deactivation_reason',
                        reason: reason,
                        // FIXED: Added esc_attr() around the nonce output
                        security: '<?php echo esc_attr( wp_create_nonce( "emmwt_deactivation_nonce" ) ); ?>'
                    },
                    complete: function() {
                        // Regardless of success/fail of email, deactivate the plugin
                        window.location.href = deactivationLink;
                    }
                });
            });
        });
    </script>
    <?php
}

// 2. Handle the AJAX request and send the email
add_action( 'wp_ajax_emmwt_submit_deactivation_reason', 'emmwt_handle_deactivation_feedback' );
function emmwt_handle_deactivation_feedback() {
    check_ajax_referer( 'emmwt_deactivation_nonce', 'security' );

    // FIXED: Added wp_unslash() before sanitize_text_field()
    $reason = isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : 'No reason provided';
    
    // Configure Email
    $to      = 'muhammed.qutubuddin786+plugin@gmail.com';
    $subject = 'Plugin Deactivation Feedback: Easy Maintenance Timer';
    $message = "A user has deactivated Easy Maintenance Timer on their site.\n\n";
    $message .= "Site URL: " . esc_url( site_url() ) . "\n";
    $message .= "Reason provided: " . $reason . "\n";

    $headers = array('Content-Type: text/plain; charset=UTF-8');

    // Send the email
    wp_mail( $to, $subject, $message, $headers );

    wp_send_json_success();
}