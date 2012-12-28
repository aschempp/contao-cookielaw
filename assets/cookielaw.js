window.cookielaw = window.cookielaw || {};
window.cookielaw.onPermission = (function() {
"use strict";

    var callbacks = [];
    var cookiesAllowed = Cookie.read(('legalcookie-'+window.location.hostname));

    var execCallbacks = function() {
        if (cookiesAllowed === true) {
            for(var i=0; i<callbacks.length; i++) {
                try {
                    callbacks[i]();
                } catch (e) {}
            }

            // Reset array, new callbacks might be added later
            callbacks = [];
        }
    };

    var askForPermission = function() {
        if (cookiesAllowed === null) {
            cookiesAllowed = false;

            // Check the list of blocked pages. This could be a description about the cookie law (would not make sense to show the popup there)
            if (window.cookielaw.blockedUrls && window.cookielaw.blockedUrls.indexOf && window.cookielaw.blockedUrls.indexOf(window.location.href) > -1) {
                return;
            }

            // Check if cookies are enabled in the browser (if not, we dont need to ask for it)
            Cookie.write('can_use_cookies', true);
            var cookiesEnabled = !!Cookie.read('can_use_cookies');
            Cookie.dispose('can_use_cookies');

            if (cookiesEnabled) {

                // load style sheet only when necessary
                new Element('link', {
                    rel: 'stylesheet',
                    media: 'screen',
                    type: 'text/css',
                    href: 'system/modules/cookielaw/assets/cookielaw.min.css'
                }).inject(document.head);

                // Hide flash elements, they would be above the popup
                var hideFlash = setTimeout(function() { $$('.mod_flash').setStyle('display', 'none'); }, 2000);

                var overlay = new Element('div', {'id':'cookielawOverlay'});
                var popup = new Element('div', {'id':'cookielawPopup', 'html':window.cookielaw.messageBody});

                var writeCookie = function(state) {
                    cookiesAllowed = state;
                    Cookie.write(('legalcookie-'+window.location.hostname), state);
                    overlay.destroy();
                    popup.destroy();
                    clearTimeout(hideFlash);
                    $$('.mod_flash').setStyle('display', 'block');

                    if (state) {
                        execCallbacks();
                        if (window.cookielaw.confirmUrl) {
                            new Request({url:window.cookielaw.confirmUrl, method:'post', data:('site='+window.location.href)}).send();
                        }
                    }
                };

                popup.adopt(
                    new Element('a', {'class':'decline', 'href':'#', 'text':window.cookielaw.declineButton, 'styles':{}}).addEvent('click', function() {
                        writeCookie(false);
                        return false;
                    }),
                    new Element('a', {'class':'accept', 'href':'#', 'text':window.cookielaw.acceptButton, 'styles':{}}).addEvent('click', function() {
                        writeCookie(true);
                        return false;
                    })
                );

                document.id(document.body).adopt(overlay, popup);

                // Position in the center of the screen
                popup.setStyles({
                    'top': ((window.getSize().y/2) - (popup.getSize().y/2) - 100),
                    'left': (window.getSize().x/2 - 150)
                });
            }
        }
    };

    return function(func) {
        callbacks.push(func);
        execCallbacks(); // try to run immediately, will only run if permission is already given
        askForPermission(); // will only show popup if not yet asked
    };
})();