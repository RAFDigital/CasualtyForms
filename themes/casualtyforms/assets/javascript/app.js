/*
 * Application
 */

/*
 * Auto hide navbar and other jQuery objects.
 */
jQuery(document).ready(function($) {
    var $header = $('.navbar-autohide'),
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

    // Initialise tooltips.
    $(this).tooltip({
        selector: '[data-toggle="tooltip"]'
    });

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

    // Initialise image zoom and controls.
    wheelzoom(document.querySelectorAll('img.image-zoom'));

    $('.zoom-reset').on('click', function(event) {
        event.preventDefault();
        document.querySelector('img.image-zoom').dispatchEvent(new CustomEvent('wheelzoom.reset'));
    });

    /*
     * Auto hide navbar and other jQuery objects.
     */
    $(window).on('scroll', function(){
        if (!scrolling) {
            scrolling = true;

            if (!window.requestAnimationFrame) {
                setTimeout(autoHideHeader, 250);
            }
            else {
                requestAnimationFrame(autoHideHeader);
            }
        }
    }), 'swing'

    function autoHideHeader() {
        var currentTop = $(window).scrollTop();

        // Scrolling up
        if (previousTop - currentTop > scrollDelta) {
            $header.removeClass('is-hidden');
        }
        else if (currentTop - previousTop > scrollDelta && currentTop > scrollOffset) {
            // Scrolling down
            $header.addClass('is-hidden');
        }

        previousTop = currentTop;
        scrolling = false;
    }

    // Ready.
    console.log("System Security Interface\nVersion 4.0.5, Alpha E\nReady...");
});
