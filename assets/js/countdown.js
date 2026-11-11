'use strict';

document.addEventListener("DOMContentLoaded", function () {
    if (typeof emmwt_data === "undefined" || !emmwt_data.date) {
        return; 
    }

    var countdownEl = document.getElementById("emmwt_countdown");
    if (!countdownEl) return;

    countdownEl.className = "emmwt-countdown-container";
    countdownEl.style.display = "flex";
    countdownEl.style.justifyContent = "center";
    countdownEl.style.gap = "15px";
    countdownEl.style.flexWrap = "wrap";
    countdownEl.style.marginTop = "20px";

    var countDownDate = (emmwt_data.timestamp && emmwt_data.timestamp > 0)
        ? emmwt_data.timestamp
        : new Date(emmwt_data.date).getTime();
    var timerInterval;

    function updateTimer() {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        if (distance <= 0) {
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            countdownEl.innerHTML = '<span class="emmwt-complete-msg" style="font-size: 1.2em; font-weight: bold;">' + (emmwt_data.complete_text || "Maintenance complete!") + '</span>';
            
            setTimeout(function() {
                if (emmwt_data.redirect_url) {
                    window.location.href = emmwt_data.redirect_url;
                } else {
                    window.location.reload();
                }
            }, 3000);
            return;
        }

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        hours = (hours < 10) ? "0" + hours : hours;
        minutes = (minutes < 10) ? "0" + minutes : minutes;
        seconds = (seconds < 10) ? "0" + seconds : seconds;

        var html = '';
        var boxStyle = 'display: flex; flex-direction: column; align-items: center; min-width: 60px;';
        var valStyle = 'font-size: 2.5em; font-weight: 700; line-height: 1;';
        var labelStyle = 'font-size: 0.85em; text-transform: uppercase; opacity: 0.8; margin-top: 5px; letter-spacing: 1px;';

        if (days > 0) {
            html += '<div class="emmwt-time-box" style="' + boxStyle + '"><span class="emmwt-time-val" style="' + valStyle + '">' + days + '</span><span class="emmwt-time-label" style="' + labelStyle + '">Days</span></div>';
        }
        
        html += '<div class="emmwt-time-box" style="' + boxStyle + '"><span class="emmwt-time-val" style="' + valStyle + '">' + hours + '</span><span class="emmwt-time-label" style="' + labelStyle + '">Hours</span></div>';
        html += '<div class="emmwt-time-box" style="' + boxStyle + '"><span class="emmwt-time-val" style="' + valStyle + '">' + minutes + '</span><span class="emmwt-time-label" style="' + labelStyle + '">Mins</span></div>';
        html += '<div class="emmwt-time-box" style="' + boxStyle + '"><span class="emmwt-time-val" style="' + valStyle + '">' + seconds + '</span><span class="emmwt-time-label" style="' + labelStyle + '">Secs</span></div>';

        countdownEl.innerHTML = html;
    }

    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
});