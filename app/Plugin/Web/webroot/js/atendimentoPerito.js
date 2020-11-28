jQuery(function ($) {
    window.intervalReload = setInterval(function () {
            location.reload(true);

        }, 10000);

    window.onbeforeunload = function() {
        clearInterval(window.intervalReload);
    }
});