/*
 * Application
 */

/*
 * jQuery things and other plugin stuff.
 */
jQuery(document).ready(function($) {
    var EMAIL_HOST = 'rafmuseum.org',
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
    $('[data-sendemail]').on('click', sendEmail);

    // Initialise tooltips.
    $(this).tooltip({selector: '[data-toggle="tooltip"]'});

    // Initialise datepicker for DOB
    datepickerConfig.defaultViewDate.year = '1890';
    $('input[type="datepicker"][name="birth_date"]').datepicker(datepickerConfig);
    // ...and the rest of them.
    datepickerConfig.defaultViewDate.year = '1914';
    $('input[type="datepicker"]').datepicker(datepickerConfig);

    // Scroll animation.
    $('a[href^="#"].smooth-scroll').on('click', function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $(this.hash).offset().top
        }, 400);
    });

    // Initialise image zoom.
    wheelzoom(imageZoom, { maxZoom: 5 });

    // ...add the zoom controls.
    $('.zoom-controls__reset').on('click', function(event) {
        event.preventDefault();
        imageZoom.dispatchEvent(new CustomEvent('wheelzoom.reset'));
    });

    $('.zoom-controls__zoomin').on('click', function(event) {
        event.preventDefault();
        imageZoom.dispatchEvent(new CustomEvent('zoomin'));
    });

    $('.zoom-controls__zoomout').on('click', function(event) {
        event.preventDefault();
        imageZoom.dispatchEvent(new CustomEvent('zoomout'));
    });

     // Initialise the feedback widget.
    // Feedback({
    //     h2cPath:'/casualty-forms/themes/casualtyforms/assets/vendor/feedback-js-master/examples/js/html2canvas.js',
    //     url:'send.php'
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
        var recipient = event.target.dataset.recipient || 'info',
            subject = event.target.dataset.subject || 'Hello';

        // Open email client.
        window.location.href = 'mailto:' + recipient + '@' + EMAIL_HOST + '?subject=' + subject;

        event.preventDefault();
    }

    // Ready.
    console.log("System Security Interface\nVersion 4.0.5, Alpha E\nReady...");
});
