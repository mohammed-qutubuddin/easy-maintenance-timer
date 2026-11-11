/**
 * Easy Maintenance Mode - Admin JS
 * Organized in a clean, modular structure.
 * ES5 syntax used for maximum backward compatibility across older WP versions and browsers.
 */
jQuery(document).ready(function ($) {
    'use strict';

    var EMMWT_Admin = {

        init: function () {
            this.initColorPicker();
            this.initDatePicker();
            this.initFieldToggles();
            this.initMediaUploader();
            this.initIPWhitelist();
            this.initTabs();
            this.initSupportForm();
            this.initDeactivationModal();
            this.initSandboxModal();
        },

        // 1. Native WP Color Picker
        initColorPicker: function () {
            // Safety check to ensure wpColorPicker is loaded
            if ($.fn.wpColorPicker && $('.emmwt-color-picker').length) {
                $('.emmwt-color-picker').wpColorPicker();
            }
        },

        // 2. Flatpickr Datepicker with Apply Button
        initDatePicker: function () {
            if (typeof flatpickr !== 'undefined') {
                if ($('#emmwt_datepicker').length) {
                    flatpickr('#emmwt_datepicker', {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        minDate: "today",
                        onReady: function (selectedDates, dateStr, instance) {
                            var btn = document.createElement("button");
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

                if ($('#emmwt_schedule_start, #emmwt_schedule_end').length) {
                    flatpickr('#emmwt_schedule_start, #emmwt_schedule_end', {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        minDate: "today"
                    });
                }
            }
        },

        // 3. Dependent Fields Toggle
        initFieldToggles: function () {
            var $chkSocial = $('#emmwt_enable_social');
            var $chkSubEmail = $('#emmwt_enable_subscriber_email');

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

            // Subscriber Confirmation Email Toggle
            if ($chkSubEmail.length) {
                $chkSubEmail.on('change', function () {
                    if ($(this).is(':checked')) {
                        $('#emmwt-subscriber-email-fields').slideDown('fast');
                    } else {
                        $('#emmwt-subscriber-email-fields').slideUp('fast');
                    }
                });
            }
        },

        // 4. WordPress Native Media Uploader
        initMediaUploader: function () {
            var setupUploader = function (btnSelector, inputSelector, customTitle) {
                var frame;
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
                        var attachment = frame.state().get('selection').first().toJSON();
                        $(inputSelector).val(attachment.url).trigger('change');

                        if (inputSelector === '#emmwt_logo_url') {
                            $('.emmwt-logo-preview').attr('src', attachment.url).show();
                        } else if (inputSelector === '#emmwt_og_image') {
                            $('.emmwt-og-preview').attr('src', attachment.url).show();
                        }
                    });

                    frame.open();
                });
            };

            var titleLogo = (typeof emmwt_admin !== 'undefined') ? emmwt_admin.title_logo : 'Select Logo';
            var titleBg = (typeof emmwt_admin !== 'undefined') ? emmwt_admin.title_bg : 'Select Background Image';
            var titleOg = (typeof emmwt_admin !== 'undefined') ? emmwt_admin.title_og : 'Select Social Preview Image';

            setupUploader('#emmwt_logo_upload', '#emmwt_logo_url', titleLogo);
            setupUploader('#emmwt_bg_upload', '#emmwt_bg_url', titleBg);
            setupUploader('#emmwt_og_image_upload', '#emmwt_og_image', titleOg);
        },

        // 5. Add Current IP to Whitelist
        initIPWhitelist: function () {
            $('#emmwt-add-my-ip').on('click', function (e) {
                e.preventDefault();
                var currentIp = $('#emmwt-current-ip').text().trim();
                var $ipBox = $('textarea[name="emmwt_bypass_ips"]');
                var existingIps = $ipBox.val().trim();
                
                var ipArray = existingIps ? existingIps.split(/\r?\n/) : [];
                
                if ($.inArray(currentIp, ipArray) === -1) {
                    $ipBox.val(existingIps === '' ? currentIp : existingIps + '\n' + currentIp);
                    $(this).text('Added!').prop('disabled', true);
                }
            });
        },

        // 6. Tabs Switcher Logic (With LocalStorage)
        initTabs: function () {
            $('.emmwt-sidebar-nav .emmwt-nav-item, .emmwt-nav-tabs .nav-tab').on('click', function (e) {
                e.preventDefault();
                
                $('.emmwt-sidebar-nav .emmwt-nav-item').removeClass('emmwt-nav-item-active');
                $('.emmwt-nav-tabs .nav-tab').removeClass('nav-tab-active');
                
                var target = $(this).attr('href');
                $('.emmwt-sidebar-nav .emmwt-nav-item[href="' + target + '"]').addClass('emmwt-nav-item-active');
                $('.emmwt-nav-tabs .nav-tab[href="' + target + '"]').addClass('nav-tab-active');
                
                $('.emmwt-tab-pane').hide();
                $(target).show();
                
                localStorage.setItem('emmwt_active_tab', target);
            });
            
            var activeTab = localStorage.getItem('emmwt_active_tab');
            if (activeTab && $(activeTab).length) {
                $('.emmwt-sidebar-nav .emmwt-nav-item[href="' + activeTab + '"], .emmwt-nav-tabs .nav-tab[href="' + activeTab + '"]').first().click();
            }
        },

        // 7. Support Ticket AJAX Form
        initSupportForm: function () {
            $('#emmwt_submit_support').on('click', function (e) {
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

                $btn.prop('disabled', true);
                $spinner.addClass('is-active');
                $notice.slideUp();

                $.ajax({
                    url: ajaxurl, // Native WordPress global var
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
                        
                        // XSS Protection via text() instead of concatenation where possible, 
                        // though WP sends safe JSON response.data
                        var responseText = response.data ? response.data : 'Action completed.';

                        if (response.success) {
                            $notice.html('<p style="color:#00a32a; margin:0;"><strong>Success:</strong> <span></span></p>')
                                   .css({'border-color': '#00a32a', 'background': '#f3faef'});
                            $notice.find('span').text(responseText);
                            $notice.slideDown();
                            $('#emmwt_support_message').val('');
                        } else {
                            $notice.html('<p style="color:#d63638; margin:0;"><strong>Error:</strong> <span></span></p>')
                                   .css({'border-color': '#d63638', 'background': '#fcf0f1'});
                            $notice.find('span').text(responseText);
                            $notice.slideDown();
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
                var deactivationLink = '';
                var pluginSlug = emmwt_deactivation_data.plugin_slug;
                var ajaxUrl = emmwt_deactivation_data.ajax_url;
                var nonce = emmwt_deactivation_data.nonce;

                var deactivateSelector = 'tr[data-plugin*="easy-maintenance-timer"] span.deactivate a, ' +
                                         'tr[data-slug="easy-maintenance-timer"] span.deactivate a, ' +
                                         '#deactivate-easy-maintenance-timer, ' +
                                         'a[href*="action=deactivate"][href*="easy-maintenance-timer"]';

                $(document).on('click', deactivateSelector, function (e) {
                    var href = $(this).attr('href');
                    if (!href) return;

                    e.preventDefault();
                    deactivationLink = href;
                    $('#emmwt-deactivate-overlay').fadeIn(150).css('display', 'flex');
                });

                $('#emmwt-cancel-deactivate, #emmwt-close-deactivate-modal, .emmwt-modal-backdrop').on('click', function (e) {
                    e.preventDefault();
                    $('#emmwt-deactivate-overlay').fadeOut(150);
                });

                $('#emmwt-deactivate-overlay').on('click', function (e) {
                    if (e.target === this) {
                        $('#emmwt-deactivate-overlay').fadeOut(150);
                    }
                });

                $('#emmwt-only-deactivate').on('click', function (e) {
                    e.preventDefault();
                    if (deactivationLink) {
                        window.location.href = deactivationLink;
                    }
                });

                $('input[name="emmwt_reason"]').on('change', function () {
                    var val = $(this).val();
                    if (val === 'Technical Issue' || val === 'I miss a feature' || val === 'Other reason') {
                        $('#emmwt-tech-details-wrapper').slideDown('fast');
                        $('#emmwt-tech-desc').focus();
                    } else {
                        $('#emmwt-tech-details-wrapper').slideUp('fast');
                        $('#emmwt-tech-desc').val('');
                    }
                });

                $('#emmwt-deactivate-form').on('submit', function (e) {
                    e.preventDefault();
                    var submitBtn = $('#emmwt-submit-deactivate');
                    submitBtn.text('Deactivating...').prop('disabled', true);

                    var reason = $('input[name="emmwt_reason"]:checked').val();
                    var techDesc = $('#emmwt-tech-desc').val();

                    // Timeout safety: if AJAX takes more than 2.5 seconds, redirect anyway!
                    var redirectTimer = setTimeout(function () {
                        if (deactivationLink) {
                            window.location.href = deactivationLink;
                        }
                    }, 2500);

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
                            clearTimeout(redirectTimer);
                            if (deactivationLink) {
                                window.location.href = deactivationLink;
                            }
                        }
                    });
                });
            }
        },

        // 9. Interactive Live Preview Sandbox Modal
        initSandboxModal: function () {
            var $modal = $('#emmwt-sandbox-modal');
            var $iframe = $('#emmwt-sandbox-iframe');
            if (!$modal.length) return;

            $('#emmwt-open-sandbox-btn').on('click', function (e) {
                e.preventDefault();
                $modal.fadeIn('fast');
                if ($iframe.length) {
                    $iframe.attr('src', $iframe.attr('src'));
                }
            });

            $('#emmwt-sandbox-close, .emmwt-sandbox-backdrop').on('click', function (e) {
                e.preventDefault();
                $modal.fadeOut('fast');
            });

            $('#emmwt-sandbox-refresh').on('click', function (e) {
                e.preventDefault();
                if ($iframe.length) {
                    $iframe.attr('src', $iframe.attr('src'));
                }
            });

            $('.emmwt-device-btn').on('click', function (e) {
                e.preventDefault();
                $('.emmwt-device-btn').removeClass('emmwt-device-active');
                $(this).addClass('emmwt-device-active');
                var width = $(this).data('width');
                $iframe.css('width', width);
            });

            // Close on Escape key press
            $(document).on('keyup', function (e) {
                if (e.key === 'Escape' && $modal.is(':visible')) {
                    $modal.fadeOut('fast');
                }
            });
        }
    };

    // Boot the admin scripts
    EMMWT_Admin.init();

});