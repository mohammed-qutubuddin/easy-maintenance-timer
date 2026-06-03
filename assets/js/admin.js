jQuery(document).ready(function ($) {
    // Reusable function for WP Media Uploaders
    function setupMediaUploader(buttonSelector, inputSelector, title) {
        var $btn = $(buttonSelector);
        var $input = $(inputSelector);

        if ($btn.length) {
            $btn.on('click', function (e) {
                e.preventDefault();
                var frame = wp.media({
                    title: title, 
                    multiple: false,
                    library: { type: 'image' }
                });

                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url).trigger('change');
                });

                frame.open();
            });
        }
    }

    // Initialize uploaders
    setupMediaUploader('#emmwt_logo_upload', '#emmwt_logo_url', emmwt_admin.title_logo);
    setupMediaUploader('#emmwt_bg_upload', '#emmwt_bg_url', emmwt_admin.title_bg);

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