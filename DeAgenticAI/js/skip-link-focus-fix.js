/**
 * Aura Theme - Skip Link Focus Fix
 */

(function() {
    var isIe = /(trident|msie)/i.test(navigator.userAgent);

    if (isIe && document.getElementById && window.addEventListener) {
        window.addEventListener('hashchange', function() {
            var element = document.getElementById(location.hash.substring(1));
            if (element) {
                if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
                    element.tabIndex = -1;
                }
                element.focus();
            }
        }, false);
    }
})();
