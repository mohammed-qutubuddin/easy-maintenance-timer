/**
 * Easy Maintenance Mode - Admin JS
 * Organized in a clean, modular structure.
 */
jQuery(document).ready(function ($) {
    'use strict';

    const EMMWT_Admin = {

        init: function () {
            this.initColorPicker();
            this.initDatePicker();
            this.initFieldToggles();
            this.initMediaUploader();
            this.initIPWhitelist();
            this.initTabs();
            this.initSupportForm();
            this.initDeactivationModal();
        },

        // 1. Native WP Color Picker
        initColorPicker: function () {
            if ($('.emmwt-color-picker').length) {
                $('.emmwt-color-picker').wpColorPicker();
            }
        },

        // 2. Flatpickr Datepicker with Apply Button
        initDatePicker: function () {
            if ($('#emmwt_datepicker').length) {
                flatpickr('#emmwt_datepicker', {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    minDate: "today",
                    onReady: function (selectedDates, dateStr, instance) {
                        const btn = document.createElement("button");
                        btn.className = "button button-primary";
                        btn.style.cssText = "width: 100%; margin-top: 10px;";
                        btn.innerHTML = "OK / Apply";
                        btn.onclick = function () {
                            instance.close();
                        };
                        instance.calendarContainer.appendChild(btn);
                    }
                });
            }
        },

        // 3. Dependent Fields Toggle
        initFieldToggles: function () {
            const $chkEnabled = $('#emmwt_enabled');
            const $chkSocial = $('#emmwt_enable_social');

            // General Enable/Disable Toggle
            if ($chkEnabled.length) {
                const toggleFields = () => {
                    $('.emmwt-dependent').prop('disabled', !$chkEnabled.is(':checked'));
                };
                $chkEnabled.on('change', toggleFields);
                toggleFields(); // Initial run
            }

            // Social Icons Toggle
            if ($chkSocial.length) {
                $chkSocial.on('change', function () {
                    if ($(this).is(':checked')) {
                        $('#emmwt-social-wrapper').slideDown('fast');
                    } else {
                        $('#emmwt-social-wrapper').slideUp('fast');
                    }
                });
            }
        },

        // 4. WordPress Native Media Uploader
        initMediaUploader: function () {
            const setupUploader = (btnSelector, inputSelector, customTitle) => {
                let frame;
                $(btnSelector).on('click', function (e) {
                    e.preventDefault();

                    if (frame) {
                        frame.open();
                        return;
                    }

                    frame = wp.media({
                        title: customTitle || 'Select Media',
                        button: { text: 'Use this media' },
                        multiple: false,
                        library: { type: 'image' }
                    });

                    frame.on('select', function () {
                        const attachment = frame.state().get('selection').first().toJSON();
                        $(inputSelector).val(attachment.url).trigger('change');

                        if (inputSelector === '#emmwt_logo_url') {
                            $('.emmwt-logo-preview').attr('src', attachment.url).show();
                        }
                    });

                    frame.open();
                });
            };

            const titleLogo = (typeof emmwt_admin !== 'undefined') ? emmwt_admin.title_logo : 'Select Logo';
            const titleBg = (typeof emmwt_admin !== 'undefined') ? emmwt_admin.title_bg : 'Select Background Image';

            setupUploader('#emmwt_logo_upload', '#emmwt_logo_url', titleLogo);
            setupUploader('#emmwt_bg_upload', '#emmwt_bg_url', titleBg);
        },

        // 5. Add Current IP to Whitelist
        initIPWhitelist: function () {
            $('#emmwt-add-my-ip').on('click', function (e) {
                e.preventDefault();
                const currentIp = $('#emmwt-current-ip').text().trim();
                const $ipBox = $('textarea[name="emmwt_bypass_ips"]');
                let existingIps = $ipBox.val().trim();
                
                const ipArray = existingIps ? existingIps.split(/\r?\n/) : [];
                
                if ($.inArray(currentIp, ipArray) === -1) {
                    $ipBox.val(existingIps === '' ? currentIp : existingIps + '\n' + currentIp);
                    $(this).text('Added!').prop('disabled', true);
                }
            });
        },

        // 6. Tabs Switcher Logic (With LocalStorage)
        initTabs: function () {
            $('.emmwt-nav-tabs .nav-tab').on('click', function (e) {
                e.preventDefault();
                
                $('.emmwt-nav-tabs .nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                $('.emmwt-tab-pane').hide();
                const target = $(this).attr('href');
                $(target).show();
                
                localStorage.setItem('emmwt_active_tab', target);
            });
            
            const activeTab = localStorage.getItem('emmwt_active_tab');
            if (activeTab && $(activeTab).length) {
                $('.emmwt-nav-tabs .nav-tab[href="' + activeTab + '"]').click();
            }
        },

        // 7. Support Ticket AJAX Form
        initSupportForm: function () {
            $('#emmwt_submit_support').on('click', function (e) {
                e.preventDefault();
                
                const $btn = $(this);
                const $spinner = $('#emmwt-support-spinner');
                const $notice = $('#emmwt-support-notice');
                const type = $('#emmwt_support_type').val();
                const message = $('#emmwt_support_message').val().trim();
                const nonce = $('#emmwt_support_nonce_field').val();
                
                if (!message) {
                    $notice.html('<p style="color:#d63638; margin:0;"><strong>Error:</strong> Please enter a message before sending.</p>')
                           .css({'border-color': '#d63638', 'background': '#fcf0f1'})
                           .slideDown();
                    return;
                }

                $btn.prop('disabled', true);
                $spinner.addClass('is-active');
                $notice.slideUp();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'emmwt_submit_support',
                        security: nonce,
                        type: type,
                        message: message
                    },
                    success: function (response) {
                        $btn.prop('disabled', false);
                        $spinner.removeClass('is-active');
                        
                        if (response.success) {
                            $notice.html('<p style="color:#00a32a; margin:0;"><strong>Success:</strong> ' + response.data + '</p>')
                                   .css({'border-color': '#00a32a', 'background': '#f3faef'})
                                   .slideDown();
                            $('#emmwt_support_message').val('');
                        } else {
                            $notice.html('<p style="color:#d63638; margin:0;"><strong>Error:</strong> ' + response.data + '</p>')
                                   .css({'border-color': '#d63638', 'background': '#fcf0f1'})
                                   .slideDown();
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false);
                        $spinner.removeClass('is-active');
                        $notice.html('<p style="color:#d63638; margin:0;"><strong>Error:</strong> An unexpected server error occurred.</p>')
                               .css({'border-color': '#d63638', 'background': '#fcf0f1'})
                               .slideDown();
                    }
                });
            });
        },

        // 8. Plugin Deactivation Modal
        initDeactivationModal: function () {
            if (typeof emmwt_deactivation_data !== 'undefined') {
                let deactivationLink = '';
                const pluginSlug = emmwt_deactivation_data.plugin_slug;
                const ajaxUrl = emmwt_deactivation_data.ajax_url;
                const nonce = emmwt_deactivation_data.nonce;

                $('#the-list').on('click', 'a[id*="deactivate-' + pluginSlug.split('/')[0] + '"]', function (e) {
                    e.preventDefault();
                    deactivationLink = $(this).attr('href');
                    $('#emmwt-deactivate-overlay').css('display', 'flex');
                });

                $('#emmwt-cancel-deactivate, #emmwt-deactivate-overlay').on('click', function (e) {
                    if (e.target === this) {
                        $('#emmwt-deactivate-overlay').hide();
                    }
                });

                $('#emmwt-only-deactivate').on('click', function (e) {
                    e.preventDefault();
                    window.location.href = deactivationLink;
                });

                $('input[name="emmwt_reason"]').on('change', function () {
                    if ($(this).val() === 'Technical Issue') {
                        $('#emmwt-tech-details-wrapper').slideDown('fast');
                        $('#emmwt-tech-desc').focus();
                    } else {
                        $('#emmwt-tech-details-wrapper').slideUp('fast');
                        $('#emmwt-tech-desc').val('');
                    }
                });

                $('#emmwt-deactivate-form').on('submit', function (e) {
                    e.preventDefault();
                    const submitBtn = $('#emmwt-submit-deactivate');
                    submitBtn.text('Submitting...').prop('disabled', true);

                    const reason = $('input[name="emmwt_reason"]:checked').val();
                    const techDesc = $('#emmwt-tech-desc').val();

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
                            window.location.href = deactivationLink;
                        }
                    });
                });
            }
        }
    };

    // Boot the admin scripts
    EMMWT_Admin.init();

});