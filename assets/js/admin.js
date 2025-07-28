jQuery(document).ready(function($){
    $('#emm_logo_upload').on('click', function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Select or Upload Logo',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var url = uploaded_image.toJSON().url;
            $('#emm_logo_url').val(url);
        });
    });
});

