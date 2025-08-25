document.addEventListener("DOMContentLoaded", function () {
    if (typeof emmwt_data === "undefined" || !emmwt_data.date) {
        return; // no countdown data
    }

    var countdownEl = document.getElementById("emmwt_countdown");
    if (!countdownEl) return;

    var countDownDate = new Date(emmwt_data.date).getTime();

    var timer = setInterval(function () {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        if (distance <= 0) {
            clearInterval(timer);
            countdownEl.textContent = emmwt_data.complete_text || "Maintenance complete!";
            return;
        }

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownEl.textContent = days + "d " + hours + "h " + minutes + "m " + seconds + "s";
    }, 1000);
});
