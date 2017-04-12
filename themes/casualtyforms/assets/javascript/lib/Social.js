/**
 * Social.js
 * All social sharing stuff.
 */
if (typeof jQuery == 'undefined') { throw new Error("Social.js requires jQuery"); }

var Social = (function(exports) {
    'use strict';

    /* Constants. */
    var SHARE = '#facebook-share',
        TWEET = '#twitter-tweet',
        LINK = '#linkedin-link',
        EMAIL = '#email-emanation',
        FACEBOOK_URL = 'https://www.facebook.com/sharer/sharer.php',
        FACEBOOK_IMG = 'themes/casualtyforms/assets/images/theme-preview.png',
        TWITTER_URL = 'https://twitter.com/intent/tweet',
        LINKEDIN_URL = 'https://www.linkedin.com/shareArticle',
        POPUP_CONFIG = {
            width: 670,
            height: 380,
            scrollbars: 'no'
        };


    /**
     * Opens a Facebook share popup
     * @param {object} event The event object.
     */
    function facebookShare(event) {
        window.open(
            FACEBOOK_URL + '?u=' + event.currentTarget.href + '&p[images][0]=' + FACEBOOK_IMG,
            'pop', popupConfig(POPUP_CONFIG)
        );
        return false;
    }

    /**
     * Opens a Twitter tweet popup
     * @param {object} event The event object.
     */
    function twitterTweet(event) {
        window.open(
            TWITTER_URL + '?url=' + event.currentTarget.href +
            '&text=' + event.currentTarget.dataset.text,
            'pop', popupConfig(POPUP_CONFIG)
        );
        return false;
    }

    /**
     * Opens a linked in share popup.
     * @param {object} event The event object.
     */
    function linkedinLink(event) {
        window.open(
            LINKEDIN_URL + '?url=' + event.currentTarget.href +
            '&title=Promise or Pay&summary=' + event.currentTarget.dataset.text,
            'pop', popupConfig(POPUP_CONFIG)
        );
        return false;
    }

    /**
     * Stringifies the popup config.
     * @param {array} config The JSON config for the popup.
     * @return {string} The config as a string.
     */
    function popupConfig(config) {
        return JSON.stringify(config)
            .replace(/"/g, '')
            .replace(/:/g, '=')
            .slice(1, -1);
    }

    /**
     * When jQuery is ready, this is executed.
     * Everything in here should be dealing with the DOM.
     */
    jQuery(function ($) {
        var $body = $('body');

        // Click the buttons.
        $body
            .on('click', SHARE, facebookShare)
            .on('click', TWEET, twitterTweet)
            .on('click', LINK, linkedinLink);
    });

    return exports;
}(Social || {}));
