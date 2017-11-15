/*
 * Application
 */

/*
 * jQuery things and other plugin stuff.
 */
jQuery(document).ready(function($) {
    var EMAIL_HOST = 'rafmuseum.org',
        DATEPICKER_SELECTOR = 'input[type="datepicker"]',
        SMOOTHSCROLL_SELECTOR = 'a[href^="#"].smooth-scroll',
        SENDEMAIL_SELECTOR = '[data-sendemail]',
        TOOLTIP_SELECTOR = '[data-toggle="tooltip"]',
        TOGGLE_DATEPICKER_MODE_CLASS = '.toggle-datepicker-mode',
        INPUT_GROUP_CLASS = '.input-group',
        FORM_CONTROL_CLASS = '.form-control',
        IMAGE_ZOOM_SELECTOR = 'img.image-zoom',
        ZOOM_CONTROLS_SELECTOR = '.zoom-controls',
        $header = $('.navbar-autohide'),
        imageZoom = document.querySelector('img.image-zoom'),
        scrolling = false,
        previousTop = 0,
        currentTop = 0,
        scrollDelta = 10,
        scrollOffset = 150,
        datepickerConfig = {
            format: "d MM yyyy",
            autoclose: true,
            startView: 'years',
            defaultViewDate: {
                year: '1914',
                month: '06',
                day: '01'
            }
        };

    // Email link.
    $(SENDEMAIL_SELECTOR).on('click', sendEmail);

    // Initialise tooltips.
    $(this).tooltip({selector: TOOLTIP_SELECTOR});

    // Initialise datepicker for DOB
    datepickerConfig.defaultViewDate.year = '1890';
    $(DATEPICKER_SELECTOR + '[name="birth_date"]').datepicker(datepickerConfig);
    // ...and the rest of them.
    datepickerConfig.defaultViewDate.year = '1914';
    $(DATEPICKER_SELECTOR).datepicker(datepickerConfig);

    // Datepicker mode change.
    $(TOGGLE_DATEPICKER_MODE_CLASS).on('click', toggleDatepickerMode);

    // Scroll animation.
    $(SMOOTHSCROLL_SELECTOR).on('click', smoothScroll);

    // Initialise image zoom.
    initImageZoom(IMAGE_ZOOM_SELECTOR, ZOOM_CONTROLS_SELECTOR);

     // Initialise the feedback widget.
    // Feedback({
    //     h2cPath:'/casualty-forms/themes/casualtyforms/assets/vendor/feedback-js-master/html2canvas.js',
    //     url:'feedback'
    // });

    // Auto hide navbar.
    $(window).on('scroll', function(){
        if (!scrolling) {
            scrolling = true;

            if (!window.requestAnimationFrame) {
                setTimeout(autoHideHeader, 250);
            } else {
                requestAnimationFrame(autoHideHeader);
            }
        }
    }), 'swing'

    /**
     * Initialises the image zoom.
     * @param {string} selector Selector for actual image.
     * @param {string} controlsSel Selector for controls.
     */
    function initImageZoom(selector, controlsSel) {
        var imageZoom = document.querySelector(selector);

        if (imageZoom) {
            wheelzoom(imageZoom, { maxZoom: 5 });
            addImageZoomListeners(imageZoom, controlsSel);
        }
    }

    /**
     * Adds listeners for loading and controls.
     * @param {object} imageZoom The image zoom object.
     * @param {string} controlsSel The selector for the controls.
     */
    function addImageZoomListeners(imageZoom, controlsSel) {
        var imgLoaded = false, imageZoomLoaded = false;

        // Add some checks.
        imageZoom.addEventListener('load', function(event) {
            console.log('imageZoom Loaded.', event);
        });
        imageZoom.addEventListener('error', function(error) {
            console.error('imageZoom Error.', error);
        });

        // ...add the zoom controls.
        $(controlsSel + '__reset').on('click', function(event) {
            event.preventDefault();
            imageZoom.dispatchEvent(new CustomEvent('wheelzoom.reset'));
        });

        $(controlsSel + '__zoomin').on('click', function(event) {
            event.preventDefault();
            imageZoom.dispatchEvent(new CustomEvent('zoomin'));
        });

        $(controlsSel + '__zoomout').on('click', function(event) {
            event.preventDefault();
            imageZoom.dispatchEvent(new CustomEvent('zoomout'));
        });

        // window.setTimeout(function(event) {
        //     console.log('Tiemout');
        // }, 2000);
    }

    /**
     * Auto hides the top header section.
     */
    function autoHideHeader() {
        var currentTop = $(window).scrollTop();

        // Scrolling up
        if (previousTop - currentTop > scrollDelta) {
            $header.removeClass('is-hidden');
        } else if (currentTop - previousTop > scrollDelta && currentTop > scrollOffset) {
            // Scrolling down
            $header.addClass('is-hidden');
        }

        previousTop = currentTop;
        scrolling = false;
    }

    /**
     * Opens the email client.
     * @param {object} event The click event.
     */
    function sendEmail(event) {
        var recipient = event.target.dataset.recipient
                ? event.target.dataset.recipient.split(',') : ['info'],
            subject = event.target.dataset.subject || 'Hello',
            recipients = '', i;

        // Concatenate all the emails together.
        for (i in recipient) {
            recipients += recipient[i] + '@' + EMAIL_HOST + ',';
        }

        // Open email client.
        window.location.href = 'mailto:' + recipients + '?subject=' + subject;

        event.preventDefault();
    }

    /**
     * Triggers a smooth scroll to target.
     * @param {object} event The scroll event.
     */
    function smoothScroll(event) {
        $('html, body').animate({
            scrollTop: $(this.hash).offset().top
        }, 400);

        event.preventDefault();
    }

    /**
     * Toggles the "month only" mode for the datepicker.
     * @param {object} event The event obj.
     */
    function toggleDatepickerMode(event) {
        // Find the datepicker and get the mode.
        var $datepicker = $(this).parents(INPUT_GROUP_CLASS).find(DATEPICKER_SELECTOR),
            mode = event.currentTarget.dataset.datepickerMode,
            datepickerModeConfig = {}, newDatepickerConfig = {};

        switch(mode) {
            case 'year': // Just year.
                datepickerModeConfig = {minViewMode: 2, format: "yyyy"};
                break;
            case 'month': // Just month and year.
                datepickerModeConfig = {minViewMode: 1, format: "MM yyyy"};
                break;
            case 'day': // Just day (default)
            default:
                newDatepickerConfig = datepickerConfig;
                break;
        }

        // Merge in the new config with original.
        newDatepickerConfig = $.extend({}, datepickerConfig, datepickerModeConfig);

        // Destroy then rebuild with the right config.
        $datepicker.val('').datepicker('destroy')
            .datepicker(newDatepickerConfig);

        event.preventDefault();
    }

    // Ready.
    console.log("System Security Interface\nVersion 4.0.5, Alpha E\nReady...");
});
