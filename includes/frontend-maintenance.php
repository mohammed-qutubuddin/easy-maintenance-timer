<?php
if (get_option('emm_enabled', 0)) {
add_action('template_redirect', function() {
    if (! current_user_can('manage_options')) {
        $date = get_option('emm_countdown_date', '2025-08-01 12:00');
        $msg = get_option('emm_maint_message', 'Site Under Maintenance. Please check back soon.');
        $logo = get_option('emm_logo_url', '');
        ?>
        <div style="text-align:center;margin-top:100px;">
            <?php if ($logo): ?>
                <img src="<?php echo esc_url($logo); ?>" alt="Logo" style="max-width:150px;"><br><br>
            <?php endif; ?>
            <h1><?php echo esc_html($msg); ?></h1>
            <div id="emm_countdown" style="font-size:2em;"></div>
        </div>
        <script>
        var countDownDate = new Date("<?php echo esc_js($date); ?>").getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById("emm_countdown").innerHTML = days + "d " + hours + "h "
            + minutes + "m " + seconds + "s ";
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("emm_countdown").innerHTML = "Maintenance complete!";
            }
        }, 1000);
        </script>
        <?php
        exit();
    }
});
}


