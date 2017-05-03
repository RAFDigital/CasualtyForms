/**
 * TranscriptionForm.js
 * All the scripts for the transcription form component.
 */
var TranscriptionForm = (function(exports) {
    'use strict';

    /* Consts */
    var TOGGLE_ILLEGIBLE_CLASS = '.toggle-illegible',
        TOGGLE_NODATA_CLASS = '.toggle-nodata',
        TRANSCRIBE_STAGE_DATA_FIELD = '[name="completed_by_id"]',
        APPROVAL_STAGE_DATA_FIELD = '[name="approved_by_id"]',
        ILLEGIBLE_CHAR = { text: '?', datepicker: '0001-01-01' },
        FORM_TAG = 'form',
        INPUT_SELECTOR = 'input',
        REQUIRED_INPUT_SELCTOR = INPUT_SELECTOR + '[required]',
        SUBMIT_BUTTON = '[type="submit"]',
        INPUT_GROUP_CLASS = '.input-group',
        FORM_CONTROL_CLASS = '.form-control',
        CHILD_TOGGLE_SECTIONS = '#childHidden, #childShow',
        RETARD_IMAGE_ZOOM_SELECTOR = 'img.image-zoom-retard',
    /* Vars */
        backToApproval = false,
        imageZoomsInitialised = false;

    /**
     * Function to toggle the illegible action.
     * @param {object} event The click event.
     */
    function toggleIllegibleClick(event) {
        var $this = $(this),
            $input = $this.parents(INPUT_GROUP_CLASS).find(FORM_CONTROL_CLASS);

        $input.trigger('change'); // For approval change detection.
        $this.toggleClass('marked');
        $this.find('span').toggleClass('hidden');

        // Change the input.
        if ($this.hasClass('marked')) {
            $input.val(ILLEGIBLE_CHAR[$input.attr('type')]).prop('readonly', true);
        } else {
            $input.val('').prop('readonly', false);
        }

        event.preventDefault();
    }

    /**
     * Function for when fields have been changed in the approval stage.
     * @param {object} event The event object.
     * @param {object} $approvedDataField The jQuery object for the hidden field.
     */
    function approvalChange(event, $approvedDataField) {
        var $submitButton = $(SUBMIT_BUTTON),
            input = event.target,
            confirmMessage = 'Changing this value means the form goes back ' +
            'into the approval stage. Are you sure you want to do this?';

        // Let's ask the user if they want to do this.
        if (!confirm(confirmMessage)) {
            // This is a fix for the jQuery toggle things.
            if ($(input.parentNode).hasClass('toggle')) {
                $(input.parentNode).toggleClass('off');
            }

            // Change input back.
            input.checked = input.defaultChecked;
            input.value = input.defaultValue;

            return;
        }

        // Change the hidden approval input to a completed input.
        $approvedDataField.attr('name', 'completed_by_id');

        // Change the submit button visually.
        $submitButton.text('Save')
            .removeClass('btn-success')
            .addClass('btn-primary');

        $(input.form).find('.alert-approval').show();

        // We only want to do all this once.
        backToApproval = true;
    }

    /**
     * This translates the awful object you get from serializeArray to
     * something usable.
     * @param {object} object The form item object
     * @param {object} item The key value pair.
     * @return {object} The formatted object.
     */
    function translateSeriasedData(object, item) {
        object[item.name] = item.value;

        return object;
    }

    /**
     * Checks dates and sets custom validity.
     * @param {string} first First date name.
     * @param {string} last Last date name.
     * @param {object} data The form data.
     * @param {object} input The current input element.
     */
    function compareDates(first, last, data, input) {
        // Check we have both the dates present.
        if (data[first] && data[last]) {
            var firstDate = new Date(data[first]),
                lastDate = new Date(data[last]);

            // Now do the check.
            if (firstDate >= lastDate) {
                input.setCustomValidity('The `' + last + '` must be after the `' + first + '`.');
            } else {
                input.setCustomValidity('');
            }
        }
    }

    /**
     * Starts the JS tour.
     * @var {string} type The tour page type.
     */
    function startTour(type) {
        if (!type) return;

        // Instance the tour
        var tour = new Tour({
            name: 'tour-' + type,
            template: document.getElementById('tour-template').innerHTML,
            //storage: false,
            steps: tourConfig[type],
            onEnd: function(tour) {
                $('html, body').animate({scrollTop: 0}, 400);
            }
        });

        // Initialize and start the tour
        tour.init();
        tour.start();
    }

    /**
     * Exposed function for checking dates are in the right order.
     * @param {object} input The current input element.
     */
    exports.checkDates = function(input) {
        var $form = $(input.form),
            data = $form.serializeArray().reduce(translateSeriasedData, {});

        // Check the birth/death dates.
        compareDates('birth_date', 'death_date', data, input);
        // Check the report dates.
        compareDates('report_date_first', 'report_date_last', data, input)
    }

    /**
     * Exposed function for toggling the child selection section.
     */
    exports.toggleChildForm = function() {
        var $toggleSections = $(CHILD_TOGGLE_SECTIONS),
            $requiredInputs = $toggleSections.find(REQUIRED_INPUT_SELCTOR);

        // Show and hide the right sections.
        $toggleSections.slideToggle();
        // Toggle the required inputs so they don't block the form submission.
        $requiredInputs.prop('disabled', function(i, v) { return !v; });

        if (!imageZoomsInitialised) {
            // Initialise image zoom and controls for the new stuff.
            wheelzoom(document.querySelectorAll(RETARD_IMAGE_ZOOM_SELECTOR));
            // (Only once)
            imageZoomsInitialised = true;
        }
    }

    /**
     * Event handler for mousemove to detect inactivity.
     */
    jQuery(document).ready(function($) {
        var $illegibleToggles = $(TOGGLE_ILLEGIBLE_CLASS),
            $nodataToggles = $(TOGGLE_NODATA_CLASS),
            $approvedDataField = $(APPROVAL_STAGE_DATA_FIELD),
            $transcribeDataField = $(TRANSCRIBE_STAGE_DATA_FIELD);

        if ($illegibleToggles.length > 0) {
            // Add the event listeners to the illegible toggles.
            $illegibleToggles.each(function(index) {
                $(this).click(toggleIllegibleClick);
            });
        }

        // This means we're in the approval stage.
        if ($approvedDataField.length > 0) {
            // Listen for changed inputs when
            $(this).on('change', INPUT_SELECTOR, function(event) {
                if (! backToApproval)
                    approvalChange(event, $approvedDataField);
            });

            // Start the approval tour.
            startTour('approve');
        } else if ($transcribeDataField.length > 0) {
            // Start the transcription tour.
            startTour('transcribe');
        }
    });

    return exports;
}(TranscriptionForm || {}));
