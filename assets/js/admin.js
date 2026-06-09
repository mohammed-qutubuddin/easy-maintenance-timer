jQuery(document).ready(function ($) {
    'use strict';

    // --- Initialize Flatpickr Custom Datepicker ---
    if ($('#emmwt_datepicker').length) {
        flatpickr('#emmwt_datepicker', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            // This line adds the OK/Apply button functionality you requested
            onReady: function(selectedDates, dateStr, instance) {
                const btn = document.createElement("button");
                btn.className = "button button-primary";
                btn.style.width = "100%";
                btn.style.marginTop = "10px";
                btn.innerHTML = "OK / Apply";
                btn.onclick = function () {
                    instance.close();
                };
                instance.calendarContainer.appendChild(btn);
            }
        });
    }

    // --- 1. Toggle Dependent Fields in Settings ---
    var $chk = $('#emmwt_enabled');
    if ($chk.length) {
        function toggleFields() {
            $('.emmwt-dependent').prop('disabled', !$chk.is(':checked'));
        }
        $chk.on('change', toggleFields);
        toggleFields(); // run on load
    }

    // --- NEW: Toggle Social Fields ---
    $('#emmwt_enable_social').on('change', function() {
        if ($(this).is(':checked')) {
            $('#emmwt-social-wrapper').slideDown('fast');
        } else {
            $('#emmwt-social-wrapper').slideUp('fast');
        }
    });

    // --- 2. WordPress Media Uploader Logic ---
    function setupMediaUploader(buttonSelector, inputSelector, customTitle) {
        var frame; // FIX: Scoped locally so each button gets its own unique uploader instance
        
        $(buttonSelector).on('click', function (e) {
            e.preventDefault();

            // If the uploader object has already been created for this specific button, reopen it
            if (frame) {
                frame.open();
                return;
            }

            // Fallback title in case localization isn't loaded
            var frameTitle = customTitle || 'Select Media';

            // Create the wp.media object
            frame = wp.media({
                title: frameTitle,
                button: {
                    text: 'Use this media'
                },
                multiple: false,
                library: { type: 'image' }
            });

            // When a file is selected, grab the URL and set it as the text field's value
            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                $(inputSelector).val(attachment.url).trigger('change');

                // If it's the logo field, update the preview image in real-time
                if (inputSelector === '#emmwt_logo_url') {
                    $('.emmwt-logo-preview').attr('src', attachment.url).show();
                }
            });

            frame.open();
        });
    }

    // Initialize media uploaders
    var titleLogo = (typeof emmwt_admin !== 'undefined') ? emmwt_admin.title_logo : 'Select Logo';
    var titleBg   = (typeof emmwt_admin !== 'undefined') ? emmwt_admin.title_bg : 'Select Background Image';
    
    setupMediaUploader('#emmwt_logo_upload', '#emmwt_logo_url', titleLogo);
    setupMediaUploader('#emmwt_bg_upload', '#emmwt_bg_url', titleBg);

    // --- 3. Deactivation Feedback Modal Logic ---
    if (typeof emmwt_deactivation_data !== 'undefined') {
        var deactivationLink = '';
        var pluginSlug = emmwt_deactivation_data.plugin_slug;
        var ajaxUrl    = emmwt_deactivation_data.ajax_url;
        var nonce      = emmwt_deactivation_data.nonce;

        // Intercept the specific plugin's deactivate link
        $('#the-list').on('click', 'a[id*="deactivate-' + pluginSlug.split('/')[0] + '"]', function (e) {
            e.preventDefault();
            deactivationLink = $(this).attr('href');
            $('#emmwt-deactivate-overlay').css('display', 'flex');
        });

        // Close modal / Cancel
        $('#emmwt-cancel-deactivate, #emmwt-deactivate-overlay').on('click', function (e) {
            if (e.target === this) {
                $('#emmwt-deactivate-overlay').hide();
            }
        });

        // Only Deactivate (Skip feedback)
        $('#emmwt-only-deactivate').on('click', function (e) {
            e.preventDefault();
            window.location.href = deactivationLink;
        });

        // Show/Hide Technical Issue Textarea
        $('input[name="emmwt_reason"]').on('change', function () {
            if ($(this).val() === 'Technical Issue') {
                $('#emmwt-tech-details-wrapper').slideDown('fast');
                $('#emmwt-tech-desc').focus();
            } else {
                $('#emmwt-tech-details-wrapper').slideUp('fast');
                $('#emmwt-tech-desc').val(''); // Clear the textarea if they select something else
            }
        });

        // Submit & Deactivate
        $('#emmwt-deactivate-form').on('submit', function (e) {
            e.preventDefault();

            var submitBtn = $('#emmwt-submit-deactivate');
            submitBtn.text('Submitting...').prop('disabled', true);

            var reason = $('input[name="emmwt_reason"]:checked').val();
            var techDesc = $('#emmwt-tech-desc').val();

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'emmwt_submit_deactivation_reason',
                    reason: reason,
                    tech_desc: techDesc,
                    security: nonce
                },
                complete: function () {
                    // Regardless of email success/fail, deactivate the plugin
                    window.location.href = deactivationLink;
                }
            });
        });
    }

    // --- Add Current IP to Whitelist ---
    $('#emmwt-add-my-ip').on('click', function(e) {
        e.preventDefault();
        var currentIp = $('#emmwt-current-ip').text().trim();
        var $ipBox = $('textarea[name="emmwt_bypass_ips"]');
        var existingIps = $ipBox.val().trim();
        
        // Convert to array to prevent duplicate exact matches
        var ipArray = existingIps ? existingIps.split(/\r?\n/) : [];
        if ($.inArray(currentIp, ipArray) === -1) {
            $ipBox.val(existingIps === '' ? currentIp : existingIps + '\n' + currentIp);
            // Optional visual feedback
            $(this).text('Added!').prop('disabled', true);
        }
    });

    // --- Tabs Switcher Logic ---
    $('.emmwt-nav-tabs .nav-tab').on('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs
        $('.emmwt-nav-tabs .nav-tab').removeClass('nav-tab-active');
        // Add active class to clicked tab
        $(this).addClass('nav-tab-active');
        
        // Hide all panes
        $('.emmwt-tab-pane').hide();
        // Show target pane
        var target = $(this).attr('href');
        $(target).show();
        
        // Save the active tab in local storage to keep it active after form save
        localStorage.setItem('emmwt_active_tab', target);
    });
    
    // Persist tab on page load
    var activeTab = localStorage.getItem('emmwt_active_tab');
    if (activeTab && $(activeTab).length) {
        $('.emmwt-nav-tabs .nav-tab[href="' + activeTab + '"]').click();
    }

    // --- Support Form AJAX Submission ---
    $('#emmwt_submit_support').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var $spinner = $('#emmwt-support-spinner');
        var $notice = $('#emmwt-support-notice');
        var type = $('#emmwt_support_type').val();
        var message = $('#emmwt_support_message').val().trim();
        var nonce = $('#emmwt_support_nonce_field').val();
        
        if (!message) {
            $notice.html('<p style="color:#d63638; margin:0;"><strong>Error:</strong> Please enter a message before sending.</p>')
                   .css({'border-color': '#d63638', 'background': '#fcf0f1'})
                   .slideDown();
            return;
        }

        // Show spinner and disable button
        $btn.prop('disabled', true);
        $spinner.addClass('is-active');
        $notice.slideUp();

        // AJAX Request
        $.ajax({
            url: ajaxurl, // Native WordPress global var
            type: 'POST',
            data: {
                action: 'emmwt_submit_support',
                security: nonce,
                type: type,
                message: message
            },
            success: function(response) {
                $btn.prop('disabled', false);
                $spinner.removeClass('is-active');
                
                if (response.success) {
                    $notice.html('<p style="color:#00a32a; margin:0;"><strong>Success:</strong> ' + response.data + '</p>')
                           .css({'border-color': '#00a32a', 'background': '#f3faef'})
                           .slideDown();
                    $('#emmwt_support_message').val(''); // Reset message box
                } else {
                    $notice.html('<p style="color:#d63638; margin:0;"><strong>Error:</strong> ' + response.data + '</p>')
                           .css({'border-color': '#d63638', 'background': '#fcf0f1'})
                           .slideDown();
                }
            },
            error: function() {
                $btn.prop('disabled', false);
                $spinner.removeClass('is-active');
                $notice.html('<p style="color:#d63638; margin:0;"><strong>Error:</strong> An unexpected server error occurred.</p>')
                       .css({'border-color': '#d63638', 'background': '#fcf0f1'})
                       .slideDown();
            }
        });
    });
});