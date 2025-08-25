jQuery(document).ready(function ($) {
    // Logo Upload
    var $logoBtn = $('#emmwt_logo_upload');
    var $logoInput = $('#emmwt_logo_url');

    if ($logoBtn.length) {
        $logoBtn.on('click', function (e) {
            e.preventDefault();
            var frame = wp.media({
                title: emmwt_admin.title, // localized string from PHP
                multiple: false,
                library: { type: 'image' }
            });

            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                $logoInput.val(attachment.url).trigger('change');
            });

            frame.open();
        });
    }

    // Toggle dependent fields
    var $chk = $('#emmwt_enabled');
    if ($chk.length) {
        function toggleFields() {
            $('.emmwt-dependent').prop('disabled', !$chk.is(':checked'));
        }
        $chk.on('change', toggleFields);
        toggleFields(); // run on load
    }
});
